<?php

use App\Models\Sale;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\FastExcel\FastExcel;

Route::get('/', function () {
    return inertia('Main');
});


Route::view('/login', 'guest.login');
Route::post('/login', function () {

})->name('login');

Route::post('/register', function () {

})->name('register');

Route::get('/admin', function() {
    return view('admin.home');
});

Route::get('/admin/invites', function () {
    return view('admin.invites.index');
})->name('admin.invites');

Route::get('/admin/teams', function () {
    return view('admin.teams.index');
})->name('admin.teams');

Route::get('/team-leader', function () {
    return view('team-leader.index');
})->name('team-leader.index');

Route::get('/team-leader/brand-ambassadors', function () {
    return view('team-leader.brand-ambassadors');
})->name('team-leader.brand_ambassadors');

Route::get('/team-leader/data', function () {
    return view('team-leader.data', ['sales' => Sale::all(), 'totalSales' => Sale::count()]);
})->name('team-leader.data');

Route::get('/team-leader/tracking', function () {
    return view('team-leader.tracking');
})->name('team-leader.tracking');

Route::get('/brand-ambassador', function () {
    return view('brand-ambassador.index');
})->name('brand-ambassador.index');

Route::get('/brand-ambassador/data', function () {
    return view('brand-ambassador.data', ['sales' => Sale::all(), 'totalSales' => Sale::count()]);
})->name('brand-ambassador.data');

Route::get('/brand-ambassador/tracking', function () {
    return view('brand-ambassador.tracking');
})->name('brand-ambassador.tracking');

Route::get('/brand-ambassador/schedule', function () {
    return view('brand-ambassador.schedule');
})->name('brand-ambassador.schedule');

Route::get('/human-resource', function () {
    return view('human-resource.index');
})->name('human-resource.index');

Route::get('/human-resource/brand-ambassador', function () {
    return view('human-resource.brand-ambassador');
})->name('human-resource.brand-ambassador');

Route::get('/human-resource/deployment', function () {
    return view('human-resource.deployment');
})->name('human-resource.deployment');

Route::post('/data', function (Request $request) {
    $request->validate([
        'team_leader_name' => 'required',
        'brand_ambassador_name' => 'required',
        'customer_name' => 'required',
        'customer_contact' => 'required',
        'product' => 'required',
        'product_quantity' => 'required',
        'promo' => 'required',
        'promo_quantity' => 'required',
        'signature' => 'required'
    ]);

    $file = $request->file('signature')->store('', 'customer_images');

    $data = $request->only([
        'team_leader_name',
        'brand_ambassador_name',
        'customer_name',
        'customer_contact',
        'product',
        'product_quantity',
        'promo',
        'promo_quantity',
    ]);

    $data['signature'] = $file;
    $data['team_name'] = 'Team A';

    Sale::create($data);

    flash('Successfully Added Sales');
    return redirect()->route('brand-ambassador.data');
})->name('data.store');

Route::get('/download/{path}', function ($path) {
    try {
        $data = Crypt::decryptString($path);

        return response()->download(storage_path($data), null, [], null);
    } catch(DecryptException $e) {
        abort(404);
    }
})->name('download');

Route::get('/data/export', function () {
    $sales = Sale::all();

    $sales = $sales->map(function (Sale $sale) {
        return [
            'Team Name' => $sale->team_name,
            'Team Leader' => $sale->team_leader_name,
            'Brand Ambassador' => $sale->brand_ambassador_name,
            'Customer Name' => $sale->customer_name,
            'Customer Contact' => $sale->customer_contact,
            'Product Name' => $sale->product,
            'Product Quantity' => $sale->product_quantity,
            'Promo' => $sale->promo,
            'Promo Quantity' => $sale->promo_quantity,
            'Signature' => route('download', $sale->signature_url)
        ];
    });

    return (new FastExcel($sales))->download(date('Y-m-d').'-sales.xlsx');
})->name('data.export');
