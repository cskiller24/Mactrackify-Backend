<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeploymentResource;
use App\Http\Resources\SalesResource;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\Deployment;
use App\Models\Sale;
use App\Models\Status;
use App\Models\Track;
use App\Models\Transaction;
use App\Models\WarehouseItem;
use App\Notifications\SpoofingAlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Notification;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class BrandAmbassadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle')->except('locationStore');
    }
    public function getSales()
    {
        $sales = Sale::brandAmbassadorWide()->with('brandAmbassador')->latest()->get();

        return response()->json([
            'message' => 'Sales collected successfully',
            'data' => SalesResource::collection($sales)
        ]);
    }

    public function dataStore(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_contact' => 'required',
            'customer_age' => 'required|gt:17',
            'product' => 'required',
            'product_quantity' => 'required',
            'promo' => 'required',
            'promo_quantity' => 'required',
            'signature' => 'required'
        ]);

        $signature = $request->file('signature')->store('', 'customer_images');

        Sale::query()->create([
            'team_id' => $request->input('team_id'),
            'team_leader_id' => $request->input('team_leader_id'),
            'brand_ambassador_id' => auth()->id(),
            'customer_name' => $request->input('customer_name'),
            'customer_contact' => $request->input('customer_contact'),
            'customer_age' => $request->input('customer_age'),
            'product' => $request->input('product'),
            'product_quantity' => $request->input('product_quantity'),
            'promo' => $request->input('promo'),
            'promo_quantity' => $request->input('promo_quantity'),
            'signature' => $signature
        ]);

        flash('Sales added succesfully');

        return redirect()->route('brand-ambassador.data');
    }

    public function locationStore(Request $request)
    {
        $data = $request->validate([
            'latitude' => ['required'],
            'longitude' => ['required'],
            'is_authentic' => ['required', 'boolean'],
        ]);

        $data['brand_ambassador_id'] = $request->user()->id;
        $data['location'] = $request->location ?? null;
        $user = $request->user();
        $leaders = $user->teams->first()->leaders;
        $track = Track::query()->create($data);
        if(! $data['is_authentic']) {
            /** @var User $user */
            Notification::send($leaders, new SpoofingAlertNotification($user, $track));
        }

        return response()->json(['message' => 'Tracking succesfully created'], ResponseCode::HTTP_CREATED);
    }

    public function locationStoreWeb(Request $request)
    {
        $data = $request->validate([
            'latitude' => ['required'],
            'longitude' => ['required'],
            'is_authentic' => ['required', 'boolean'],
        ]);

        $data['brand_ambassador_id'] = auth()->user()->id;
        $data['location'] = $request->location ?? null;
        $user = auth()->user();
        $leaders = $user->teams->first()->leaders;
        $track = Track::query()->create($data);
        if(! $data['is_authentic']) {
            /** @var User $user */
            Notification::send($leaders, new SpoofingAlertNotification($user, $track));
        }

        return response()->json(['message' => 'Tracking succesfully created'], ResponseCode::HTTP_CREATED);
    }

    public function scheduling(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return DeploymentResource::collection(
            Deployment::whereUserId($user->id)->get()
        );
    }

    public function schedulingAccept(Deployment $deployment)
    {
        $user = auth()->user();

        $user->statuses()->create([
            'status' => Status::AVAILABLE,
        ]);
        $deployment->update(['status' => Deployment::ACCEPTED]);

        return response()->json([
            'message' => 'Schedule accepted successfully'
        ]);
    }

    public function schedulingDecline(Deployment $deployment)
    {
        $user = auth()->user();

        $user->statuses()->create([
            'status' => Status::NOT_AVAILABLE,
        ]);
        $deployment->update(['status' => Deployment::DECLINED]);

        return response()->json([
            'message' => 'Schedule accepted successfully'
        ]);
    }

    public function schedulingUpdate(Request $request, Deployment $deployment)
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

        return response()->json([
            'message' => 'Successfully updated scheduling'
        ]);
    }

    public function transactions()
    {
        return TransactionResource::collection(
            Transaction::with(['user', 'items', 'account'])->whereUserId(auth()->id())->get()
        );
    }

    public function transactionsShow($uuid)
    {
        return TransactionResource::make(
            Transaction::with(['user', 'items', 'account'])->whereUuid($uuid)->firstOrFail()
        );
    }

    public function accounts()
    {
        return response()->json([
            'message' => 'Accounts retrieved sucessfully',
            'data' => Account::all(),
        ]);
    }

    public function warehouseItems()
    {
        return response()->json([
            'message' => 'Warehouse retrieved successfully',
            'data' => WarehouseItem::all()
        ]);
    }

    public function transactionsStore(Request $request)
    {
        $request->validate([
            'account_name' => ['required'],
            'items' => ['required', 'array'],
            'items.*.product_name' => ['required', 'exists:warehouse_items,name'],
            'items.*.quantity' => ['required', 'numeric']
        ]);

        $totals = [];

        foreach ($request->items as $item) {
            $itemId = $item['product_name'];
            $quantity = intval($item['quantity']);

            if (isset($totals[$itemId])) {
                $totals[$itemId] += $quantity;
            } else {
                $totals[$itemId] = $quantity;
            }
        }

        $transaction = Transaction::query()->create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->id(),
            'status' => 'Pending',
            'account_id' => Account::query()->where('name', $request->account_name)->first()->id
        ]);

        foreach($totals as $itemName => $quantity) {
            $transaction->items()->create(['quantity' => $quantity, 'warehouse_item_id' => WarehouseItem::query()->whereName($itemName)->first()->id]);
        }

        return response()->json([
            'message' => 'added successfully'
        ]);
    }
}
