<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandAmbassador\BrandAmbassadorController;
use App\Http\Controllers\HumanResource\HumanResourceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\TeamLeader\TeamLeaderController;
use App\Mail\InviteCreated;
use App\Mail\SendAvailabilityNotification;
use App\Mail\SpoofingMail;
use App\Models\Deployment;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\SpoofingAlertNotification;
use Barryvdh\DomPDF\Facade\Pdf;
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
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');

    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::post('/warehouses/{warehouse}/items', [WarehouseController::class, 'itemsStore'])->name('warehouses.items.store');
    Route::post('/items/{warehouseItem}', [WarehouseController::class, 'itemsAdd'])->name('warehouses.items.add');
    Route::put('/items/{warehouseItem}', [WarehouseController::class, 'itemsUpdate'])->name('warehouses.items.update');

    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::put('/accounts/{account}/add-balance', [AccountController::class, 'addBalance'])->name('accounts.addBalance');
    Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
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
    Route::get('/tracking/{id}', [TeamLeaderController::class, 'trackingShow'])->name('tracking.show');
    Route::get('/tracking-spoofed/{trackId}', [TeamLeaderController::class, 'trackingShowId'])->name('tracking.show.id');
    Route::get('/notifications', [TeamLeaderController::class, 'notificationIndex'])->name('notifications');
    Route::get('/transactions/create', [TeamLeaderController::class, 'transactionsCreate'])->name('transactions.create');
    Route::post('/transactions', [TeamLeaderController::class, 'transactionsStore'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TeamLeaderController::class, 'transactionsShow'])->name('transactions.show');
    Route::post('/transactions/{transaction}/balance', [TeamLeaderController::class, 'transactionsAddBalance'])->name('transactions.addBalance');
    Route::post('/transactions/{transaction}/release', [TeamLeaderController::class, 'transactionsRelease'])->name('transactions.release');
    Route::get('/transactions/{transaction}/receipt', [TeamLeaderController::class, 'transactionsReciept'])->name('transactions.receipt');
    Route::post('/transactions/{transaction}/complete', [TeamLeaderController::class, 'transactionsComplete'])->name('transactions.complete');
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
    Route::get('/deployment/create/{team}', [HumanResourceController::class, 'deploymentCreate'])->name('deployment.create');
    Route::post('/deployment/create/{team}', [HumanResourceController::class, 'deploymentStore'])->name('deployment.store');
    Route::get('/deployment/replace/{deployment}/auto', [HumanResourceController::class, 'deploymentReplaceAuto'])->name('deployment.replace-auto');
    Route::get('/send-notification/{deployment}', [HumanResourceController::class, 'sendNotification'])->name('notification-send');
    Route::get('/send-notification/all/{team}', [HumanResourceController::class, 'sendAllNotification'])->name('notification-send-all');
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
    Route::get('/data/create', [BrandAmbassadorController::class, 'dataCreate'])->name('data.create');
    Route::get('/data/{transaction}', [BrandAmbassadorController::class, 'dataShow'])->name('data.show');

    Route::get('/tracking', [BrandAmbassadorController::class, 'trackingIndex'])->name('tracking');
    Route::post('/test-track', [BrandAmbassadorController::class, 'toggleTracking'])->name('test.track');

    Route::get('/schedule', [BrandAmbassadorController::class, 'scheduleIndex'])->name('schedule');
    Route::put('/schedule/status/update', [BrandAmbassadorController::class, 'scheduleIndex'])->name('schedule.status.update');
    Route::put('/schedule/{deployment}', [BrandAmbassadorController::class, 'scheduleUpdate'])->name('schedule.update');
});

Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.update.password');
});

/**
 * END OF BRAND AMBASSADOR ROUTE
 * ====================================================
 */


// Route::get('/brand-ambassador/schedule', function () {
//     return view('brand-ambassador.schedule');
// })->name('brand-ambassador.schedule');


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
    return new SendAvailabilityNotification(auth()->user(), Deployment::query()->first());
});

Route::get('/schedule', function () {
    /** @var User $user */
    $user = auth()->user();

    if(! $user) {
        abort(404);
    }

    if($user->isBrandAmbassador()) {
        return redirect()->route('brand-ambassador.schedule');
    }

    if($user->isTeamLeader()) {
        return redirect('/');
    }

    flash('You are not deployee or deployer to check availability', 'error');
    return redirect('/');
})->name('schedule');

Route::get('/pdf', function() {
    $transaction = Transaction::query()->first();

    $totalAmount = 0;

    foreach($transaction->items as $transactionItem) {
        $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
    }
    $emptyCount = 20 - $transaction->items->count();
    if($emptyCount < 0) {
        $emptyCount = 0;
    }

    $deployer = $transaction->user->teams->first()->leaders->first();

    return view('pdfs.SalesOrder', ['transaction' => $transaction, 'totalAmount' => $totalAmount, 'emptyCount' => $emptyCount, 'deployer' => $deployer]);
});
Route::get('/pdf2', function() {
    $transaction = Transaction::query()->first();

    $totalAmount = 0;

    foreach($transaction->items as $transactionItem) {
        $totalAmount += $transactionItem->quantity * $transactionItem->warehouseItem->price;
    }
    $pdf = Pdf::loadView('pdfs.SalesOrder', ['transaction' => $transaction, 'totalAmount' => $totalAmount]);

    return $pdf->download();
});

Route::get('/tracks', [TeamLeaderController::class, 'apiTracking'])->name('api.tracks');
Route::get('/tracks/{id}', [TeamLeaderController::class, 'apiShowTracking'])->name('api.tracks.show');
Route::post('/tracking-web', [\App\Http\Controllers\Api\BrandAmbassadorController::class, 'locationStoreWeb'])->middleware('bypass')->name('api.tracks.store');
