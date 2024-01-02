<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalesResource;
use App\Models\Deployment;
use App\Models\Sale;
use App\Models\Status;
use App\Models\Track;
use App\Notifications\SpoofingAlertNotification;
use Illuminate\Http\Request;
use Notification;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class BrandAmbassadorController extends Controller
{
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

        $deployment = Deployment::tommorow()->whereUserId($user->id)->first();

        if(! $deployment) {
            return response()->json([
                'has_deployment' => false,
                'message' => 'No deployment',
            ]);
        }

        return response()->json([
            'data' => $deployment,
            'has_deployment' => true,
            'message' => 'Successfully retrieve deployment'
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
}
