<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandAmbassador\BrandAmbassadorController;
use App\Http\Controllers\HumanResource\HumanResourceController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\TeamLeader\TeamLeaderController;
use App\Mail\InviteCreated;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\FastExcel\FastExcel;

Route::get('/', [RedirectController::class, 'redirect']);

/**
 * GUEST ROUTE
 * ====================================================
 */

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

/**
 * END OF GUEST ROUTE
 * ====================================================
 */

/**
 * AUTH ROUTE
 * ====================================================
 */

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/invites/accept/{invite:code}', [InviteController::class, 'accept'])->name('invites.accept');
Route::post('/invites/accept/{invite:code}', [InviteController::class, 'register'])->name('invites.register');


/**
 * END OF AUTH ROUTE
 * ====================================================
 */

/**
 * ADMIN ROUTE
 * ====================================================
 */

Route::group([
    'middleware' => ['auth', 'role:'.User::ADMIN],
    'as' => 'admin.',
    'prefix' => '/admin',
], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('/invites', [InviteController::class, 'create'])->name('invites.store');
    Route::put('/invites/{invite:code}', [InviteController::class, 'update'])->name('invites.update');
    Route::get('/invites/resend/{invite:code}', [InviteController::class, 'resend'])->name('invites.resend');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
});

/**
 * END OF ADMIN ROUTE
 * ====================================================
 */

 /**
 * TEAM LEADER ROUTE
 * ====================================================
 */

Route::group([
    'middleware' => ['auth', 'role:'.User::TEAM_LEADER],
    'as' => 'team-leader.',
    'prefix' => '/team-leader',
], function () {
    Route::get('/', [TeamLeaderController::class, 'index'])->name('index');
});

/**
 * END OF TEAM LEADER ROUTE
 * ====================================================
 */

/**
 * HUMAN RESOURCE ROUTE
 * ====================================================
 */

Route::group([
    'middleware' => ['auth', 'role:'.User::HUMAN_RESOURCE],
    'as' => 'human-resource.',
    'prefix' => '/human-resource',
], function () {
    Route::get('/', [HumanResourceController::class, 'index'])->name('index');
});

/**
 * END OF HUMAN RESOURCE ROUTE
 * ====================================================
 */

/**
 * BRAND AMBASSADOR ROUTE
 * ====================================================
 */

Route::group([
    'middleware' => ['auth', 'role:'.User::BRAND_AMBASSADOR],
    'as' => 'brand-ambassador.',
    'prefix' => '/brand-ambassador',
], function () {
    Route::get('/', [BrandAmbassadorController::class, 'index'])->name('index');
});

/**
 * END OF BRAND AMBASSADOR ROUTE
 * ====================================================
 */

Route::get('/team-leader/brand-ambassadors', function () {
    return view('team-leader.brand-ambassadors');
})->name('team-leader.brand_ambassadors');

Route::get('/team-leader/data', function () {
    return view('team-leader.data', ['sales' => Sale::all(), 'totalSales' => Sale::count()]);
})->name('team-leader.data');

Route::get('/team-leader/tracking', function () {
    return view('team-leader.tracking');
})->name('team-leader.tracking');

Route::get('/brand-ambassador/data', function () {
    return view('brand-ambassador.data', ['sales' => Sale::all(), 'totalSales' => Sale::count()]);
})->name('brand-ambassador.data');

Route::get('/brand-ambassador/tracking', function () {
    return view('brand-ambassador.tracking');
})->name('brand-ambassador.tracking');

Route::get('/brand-ambassador/schedule', function () {
    return view('brand-ambassador.schedule');
})->name('brand-ambassador.schedule');

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

Route::get('/mail', function () {
    return new InviteCreated();
});
