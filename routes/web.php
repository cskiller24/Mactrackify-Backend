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
use App\Mail\SendAvailabilityNotification;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
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
    Route::get('/brand-ambassadors', [TeamLeaderController::class, 'brandAmbassadorsIndex'])->name('brand_ambassadors');
    Route::get('/data', [TeamLeaderController::class, 'dataIndex'])->name('data');
    Route::post('/data/export', [TeamLeaderController::class, 'dataExport'])->name('data.export');
    Route::get('/tracking', [TeamLeaderController::class, 'trackingIndex'])->name('tracking');
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
    Route::get('/brand-ambassador', [HumanResourceController::class, 'brandAmbassadorsIndex'])->name('brand-ambassador');
    Route::get('/deployment', [HumanResourceController::class, 'deploymentIndex'])->name('deployment');
    Route::get('/send-notification/{user}', [HumanResourceController::class, 'sendNotification'])->name('notification-send');
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
    Route::get('/data', [BrandAmbassadorController::class, 'dataIndex'])->name('data');
    Route::post('/data', [BrandAmbassadorController::class, 'dataStore'])->name('data.store');
    Route::get('/tracking', [BrandAmbassadorController::class, 'trackingIndex'])->name('tracking');
    Route::post('/test-track', [BrandAmbassadorController::class, 'toggleTracking'])->name('test.track');
});

/**
 * END OF BRAND AMBASSADOR ROUTE
 * ====================================================
 */


Route::get('/brand-ambassador/schedule', function () {
    return view('brand-ambassador.schedule');
})->name('brand-ambassador.schedule');


Route::get('/download/{path}', function ($path) {
    try {
        $data = Crypt::decryptString($path);

        return response()->download(storage_path($data), null, [], null);
    } catch(DecryptException $e) {
        abort(404);
    }
})->name('download');

Route::get('test', function () {
    return Inertia::render('Main');
});

Route::get('/mail', function () {
    return new SendAvailabilityNotification(auth()->user());
});
