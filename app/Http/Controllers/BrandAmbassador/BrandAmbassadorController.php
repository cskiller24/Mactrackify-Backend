<?php

namespace App\Http\Controllers\BrandAmbassador;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionStoreRequest;
use App\Models\Account;
use App\Models\Deployment;
use App\Models\Sale;
use App\Models\Status;
use App\Models\TaskScheduler;
use App\Models\Team;
use App\Models\Track;
use App\Models\Transaction;
use App\Models\WarehouseItem;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Log;
use PhpParser\Node\Expr\Cast\Array_;

class BrandAmbassadorController extends Controller
{
    public function index()
    {
        $hasBack = false;
        $transactionsToday = Transaction::query()->where('user_id', auth()->id())->whereDate('created_at', Carbon::today())->count();
        $transactionsCount = Transaction::query()->where('user_id', auth()->id())->count();
        $trackingCount = Track::query()->where('brand_ambassador_id', auth()->id())->whereDate('created_at', Carbon::today())->count();
        $schedule = Deployment::orderBy('date', 'asc')
            ->where('user_id', auth()->id())
            ->where('date', '>', Carbon::today())
            ->first()?->date ?? 'No upcoming schedules';

        return view('brand-ambassador.index', compact('transactionsToday', 'transactionsCount', 'trackingCount', 'schedule','hasBack'));
    }

    public function dataIndex(Request $request)
    {
        $transactions = Transaction::query()
            ->whereUserId(auth()->id())
            ->search($request->get('search', ''))
            ->get();

        return view('brand-ambassador.data', compact('transactions'));
    }

    public function dataCreate()
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

        return view('brand-ambassador.transactions-create', compact('items', 'accounts'));
    }

    public function dataStore(TransactionStoreRequest $request)
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

            return redirect()->route('brand-ambassador.data');
        } catch(Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Something went wrong please try again');

            return redirect()->route('brand-ambassador.data.create');
        }

        return redirect()->route('brand-ambassador.data');
    }

    public function trackingIndex()
    {
        $tracks = Track::query()->whereBrandAmbassadorId(auth()->id())->latest()->get();
        $isTracking = TaskScheduler::query()
            ->whereCommand("app:test-ba-track")
            ->whereArguments(json_encode(['--user-id' => auth()->id()]))
            ->exists();

        $user = auth()->user();

        return view('brand-ambassador.tracking', compact('tracks', 'isTracking', 'user'));
    }

    public function toggleTracking()
    {
        $command = "app:test-ba-track";
        $tasks = TaskScheduler::query()
            ->whereCommand($command)
            ->whereArguments(json_encode(['--user-id' => auth()->id()]))
            ->first();

        if($tasks) {
            $tasks->delete();

            flash('Test tracking disabled successfully');
        } else {
            TaskScheduler::query()->create([
                'command' => $command,
                'frequency' => 'everyMinute',
                'arguments' => json_encode(['--user-id' => auth()->id()]),
            ]);

            flash('Test tracking enabled successfully, create track every minute');
        }

        return redirect()->route('brand-ambassador.tracking');
    }

    public function scheduleIndex()
    {
        $deployments = Deployment::whereUserId(auth()->id())->latest()->get();

        return view('brand-ambassador.schedule', compact('deployments'));
    }

    public function scheduleUpdate(Request $request, Deployment $deployment)
    {
        $request->validate([
            'status' => ['in:'.Deployment::ACCEPTED.','.Deployment::DECLINED]
        ]);

        $user = auth()->user();

        $currentStatus = $request->input('status') === Deployment::ACCEPTED ? Status::AVAILABLE : Status::NOT_AVAILABLE;
        $user->statuses()->create([
            'status' => $currentStatus,
        ]);
        $deployment->update(['status' => $request->input('status')]);

        flash('You have updated your status successfully');

        return redirect()->route('brand-ambassador.schedule');
    }
}
