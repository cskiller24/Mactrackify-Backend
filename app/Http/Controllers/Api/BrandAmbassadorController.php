<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalesResource;
use App\Models\Sale;
use Illuminate\Http\Request;

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
}
