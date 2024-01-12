<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionStoreRequest;
use App\Models\Account;
use App\Models\Sale;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WarehouseItem;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Exception;
use Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Str;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Carbon;

class TeamLeaderController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle')->except(['apiTracking', 'apiShowTracking']);
    }
    public function index()
    {
        $hasBack = false;
        $teamName = Team::teamLeaderWide()->first()?->name ?? 'No Current Team';
        $deployeeCount = Team::with(['members'])->teamLeaderWide()->first()->members->count();
        $transactionsTodayCount = Transaction::query()->whereDate('created_at', Carbon::today())->count();
        $transactionsCount = Transaction::count();
        return view('team-leader.index', compact('hasBack', 'teamName', 'deployeeCount', 'transactionsTodayCount', 'transactionsCount'));
    }

    public function brandAmbassadorsIndex(Request $request)
    {
        $team = Team::query()
            ->whereHas('leaders', function ($query) {
                $query->where('users.id', auth()->id());
            })->with(['members'])->first();

        $members = $team->members->pluck('id');

        $members = User::query()
            ->whereIn('id', $members)
            ->search($request->get('search', ''))->get();

        return view('team-leader.brand-ambassadors', compact('team', 'members'));
    }

    public function dataIndex(Request $request)
    {
        $transactions = Transaction::query()
            ->when($request->get('user_id', false), function ($q) use ($request) {
                $q->where('user_id', $request->get('user_id'));
            })
            ->search($request->get('search', ''))
            ->get();


        return view('team-leader.data', compact('transactions'));
    }

    public function transactionsCreate()
    {
        $accounts = Account::all();

        if($accounts->isEmpty()) {
            toastr()->warning('There are no registered accounts, please ask the admin for the account');

            return redirect()->route('team-leader.data');
        }

        $items = WarehouseItem::all();

        if($items->isEmpty()) {
            toastr()->warning('There are no registered items, please ask the admin for the items');

            return redirect()->route('team-leader.data');
        }

        return view('team-leader.transactions-create', compact('items', 'accounts'));
    }

    public function transactionsStore(TransactionStoreRequest $request)
    {
        $request->validated();

        $totals = [];

        foreach ($request->items as $item) {
            $itemId = $item['item'];
            $quantity = intval($item['quantity']);

            if (isset($totals[$itemId])) {
                $totals[$itemId] += $quantity;
            } else {
                $totals[$itemId] = $quantity;
            }
        }

        try {
            DB::beginTransaction();

            $transaction = Transaction::query()->create([
                'uuid' => Str::uuid(),
                'user_id' => auth()->id(),
                'status' => 'Pending',
                'account_id' => $request->input('account_id')
            ]);

            foreach($totals as $itemId => $quantity) {
                $transaction->items()->create(['quantity' => $quantity, 'warehouse_item_id' => $itemId]);
            }

            DB::commit();

            toastr()->success('Transaction created succesfully');

            return redirect()->route('team-leader.data');
        } catch(Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Something went wrong please try again');

            return redirect()->route('team-leader.transactions.create');
        }

        return redirect()->route('team-leader.data');
    }

    public function transactionsShow(Transaction $transaction)
    {
        $transaction->load(['items', 'items.warehouseItem', 'items.warehouseItem.warehouse', 'collectionHistories']);

        $totalAmount = 0;
        $paidAmount = 0;

        foreach($transaction->collectionHistories as $collectionHistory) {
            $paidAmount += $collectionHistory->balance;
        }

        foreach($transaction->items as $transactionItem) {
            $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
        }

        return view('team-leader.data-show', compact('transaction', 'totalAmount', 'paidAmount'));
    }

    public function transactionsAddBalance(Request $request, Transaction $transaction)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        if($request->get('bank_account', false)) {
            $this->addBalanceUsingBankAccount($request, $transaction);
        } else {
            $this->addBalance($request, $transaction);
        }

        toastr()->success('Added payment succesfully');

        return redirect()->route('team-leader.transactions.show', $transaction->id);
    }

    public function transactionsRelease(Transaction $transaction)
    {
        $items = $transaction->items;

        foreach($items as $item) {
            $warehouseItem = WarehouseItem::query()->where('id', $item->warehouse_item_id)->first();
            $warehouseItem->update(['quantity' => $warehouseItem->quantity - $item->quantity]);
        }

        $transaction->update(['status' => 'Released']);

        toastr()->success('Transaction release successfully');

        return redirect()->route('team-leader.transactions.show', $transaction->id);
    }

    public function transactionsReciept(Transaction $transaction)
    {
        $transaction = Transaction::query()->first();

        $totalAmount = 0;

        foreach($transaction->items as $transactionItem) {
            $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
        }
        $emptyCount = 25 - $transaction->items->count();
        if($emptyCount < 0) {
            $emptyCount = 0;
        }

        $deployer = $transaction->user->teams->first()->leaders->first();
        $pdf = Pdf::loadView('pdfs.SalesOrder', ['transaction' => $transaction, 'totalAmount' => $totalAmount, 'deployer' => $deployer, 'emptyCount' => $emptyCount]);

        return $pdf->download();
    }

    public function transactionsComplete(Transaction $transaction)
    {
        $transaction->update(['status' => 'Completed']);

        return redirect()->route('team-leader.transactions.show', $transaction->id);
    }

    public function dataExport()
    {
        $sales = Sale::teamLeaderWide()->with(['team', 'teamLeader', 'brandAmbassador'])->get();

        $toExcel = $sales->map(function (Sale $sale) {
            return [
                'Name' => $sale->customer_name,
                'Contact Information' => $sale->customer_contact,
                'Age' => $sale->customer_age,
                'Product' => $sale->product,
                'Quantity' => $sale->product_quantity,
                'Promo' => $sale->promo,
                'Quantity' => $sale->promo_quantity,
                'Signature' => route('download', $sale->signatureUrl)
            ];
        });

        return (new FastExcel($toExcel))->download('sales-'.date('Y-m-d').'.xlsx');
    }

    public function trackingIndex()
    {
        $team = Team::teamLeaderWide()->whereHas('members')->first();

        $tracking = $team->members->map(fn ($user) => $user->latest_track);

        return view('team-leader.tracking', compact('team', 'tracking'));
    }

    public function apiTracking()
    {
        $team = Team::teamLeaderWide()->whereHas('members')->first();

        $members = collect();

        foreach($team->members as $user) {
            if($user->hasTrack) {
                $members->add($user);
            }
        }

        return response()->json($members);
    }

    public function apiShowTracking($id)
    {
        $user = User::query()->findOrFail($id);
        $user->load(['latestTracking']);

        abort_if(! $user->isBrandAmbassador(), 404);

        return response()->json($user);
    }

    public function trackingShow($id)
    {
        $user = User::query()->findOrFail($id);
        $user->load(['latestTracking']);

        abort_if(! $user->isBrandAmbassador(), 404);

        return view('team-leader.tracking-show', compact('user'));
    }

    public function notificationIndex()
    {
        /** @var User $user */
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        return view('team-leader.notifications', compact('notifications'));
    }

    protected function addBalanceUsingBankAccount(Request $request, Transaction $transaction)
    {
        if($request->input('amount') > $transaction->account->balance) {
            toastr()->error('Insufficient bank account balance');

            return redirect()->route('team-leader.transactions.show', $transaction->id);
        }

        $totalAmount = 0;
        $paidAmount = 0;

        foreach($transaction->collectionHistories as $collectionHistory) {
            $paidAmount += $collectionHistory->balance;
        }

        foreach($transaction->items as $transactionItem) {
            $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
        }

        $remainingAmount = $totalAmount - $paidAmount;
        $account = $transaction->account;


        if($request->input('amount') - $remainingAmount > 0) {
            $toAddBalance = $request->input('amount') - $remainingAmount;
            $account->update(['balance' => $account->balance - $remainingAmount]);

            toastr()->success('The remaining balance '.$toAddBalance.' is automatically added to account balance');
            $transaction->collectionHistories()->create(['balance' => $remainingAmount]);

            $transaction->update(['status' => 'Fully Paid']);

        } else if($totalAmount - $paidAmount - $request->input('amount') == 0) {
            $transaction->update(['status' => 'Fully Paid']);
            $account->update(['balance' => $account->balance - $request->input('amount')]);
            $transaction->collectionHistories()->create(['balance' => $request->input('amount')]);
        } else {
            $transaction->update(['status' => 'Partially Paid']);
            $account->update(['balance' => $account->balance - $request->input('amount')]);
            $transaction->collectionHistories()->create(['balance' => $request->input('amount')]);
        }
    }

    protected function addBalance(Request $request, Transaction $transaction)
    {
        $totalAmount = 0;
        $paidAmount = 0;

        foreach($transaction->collectionHistories as $collectionHistory) {
            $paidAmount += $collectionHistory->balance;
        }

        foreach($transaction->items as $transactionItem) {
            $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
        }

        if($totalAmount - $paidAmount - $request->input('amount') < 0) {
            $toAddBalance = $request->input('amount') - ($totalAmount + $paidAmount);
            $account = $transaction->account;
            $account->update(['balance' => $account->balance + $toAddBalance]);

            toastr()->success('The remaining balance '.$toAddBalance.' is automatically added to account balance');
            $transaction->collectionHistories()->create(['balance' => $totalAmount - $paidAmount]);

            $transaction->update(['status' => 'Fully Paid']);

        } else if($totalAmount - $paidAmount - $request->input('amount') == 0) {
            $transaction->update(['status' => 'Fully Paid']);
            $transaction->collectionHistories()->create(['balance' => $request->input('amount')]);
        } else {
            $transaction->update(['status' => 'Partially Paid']);
            $transaction->collectionHistories()->create(['balance' => $request->input('amount')]);
        }

        return true;
    }
}
