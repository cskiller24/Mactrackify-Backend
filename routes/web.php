<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

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
    return view('team-leader.data');
})->name('team-leader.data');

Route::get('/team-leader/tracking', function () {
    return view('team-leader.tracking');
})->name('team-leader.tracking');

Route::get('/brand-ambassador', function () {
    return view('brand-ambassador.index');
})->name('brand-ambassador.index');

Route::get('/brand-ambassador/data', function () {
    return view('brand-ambassador.data');
})->name('brand-ambassador.data');

Route::get('/brand-ambassador/tracking', function () {
    return view('brand-ambassador.tracking');
})->name('brand-ambassador.tracking');

Route::get('/brand-ambassador/schedule', function () {
    return view('brand-ambassador.schedule');
})->name('brand-ambassador.schedule');
