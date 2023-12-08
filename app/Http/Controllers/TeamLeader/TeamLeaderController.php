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

class TeamLeaderController extends Controller
{
    public function index()
    {

        return view('team-leader.index');
    }

    public function brandAmbassadorsIndex()
    {
        $team = Team::query()->whereHas('leaders', function ($query) {
            $query->where('users.id', auth()->id());
        })->with(['members.sales'])->first();

        return view('team-leader.brand-ambassadors', compact('team'));
    }

    public function dataIndex()
    {
        $transactions = Transaction::all();


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


        $totalAmount = 0;
        $paidAmount = 0;

        foreach($transaction->collectionHistories as $collectionHistory) {
            $paidAmount += $collectionHistory->balance;
        }

        foreach($transaction->items as $transactionItem) {
            $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
        }
        $transaction->collectionHistories()->create(['balance' => $request->input('amount')]);

        if($totalAmount - $paidAmount - $request->input('amount') == 0) {
            $transaction->update(['status' => 'Fully Paid']);
        } else {
            $transaction->update(['status' => 'Partially Paid']);
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
        $totalAmount = 0;

        foreach($transaction->items as $transactionItem) {
            $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
        }
        $pdf = Pdf::loadView('pdfs.SalesOrder', ['transaction' => $transaction, 'totalAmount' => $totalAmount]);

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

        return inertia('Tracking', [
            'team' => $team,
            'tracking' => $tracking,
        ]);
    }

    public function trackingShow($id)
    {
        $user = User::query()->findOrFail($id);
        $user->load(['latestTracking']);

        abort_if(! $user->isBrandAmbassador(), 404);

        return inertia('TrackingDeployee', [
            'user' => $user
        ]);
    }

    public function notificationIndex()
    {
        /** @var User $user */
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        return view('team-leader.notifications', compact('notifications'));
    }
}
