<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RiwayatAntarJemputController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});


// =======================
// AUTH
// =======================

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);

Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);


// =======================
// DASHBOARD
// =======================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');


// =======================
// KIDS
// =======================

Route::resource('kids', KidController::class)
    ->middleware('auth');


// =======================
// PROFILE
// =======================

Route::get('/profile', [ProfileController::class, 'index'])
    ->middleware('auth');

Route::post('/profile', [ProfileController::class, 'update'])
    ->middleware('auth');


// =======================
// SUBSCRIPTIONS
// =======================

Route::resource('subscriptions', SubscriptionController::class)
    ->middleware('auth');

Route::post(
    '/subscriptions/{id}/pause',
    [SubscriptionController::class, 'pause']
)->name('subscriptions.pause');

Route::post(
    '/subscriptions/{id}/resume',
    [SubscriptionController::class, 'resume']
)->name('subscriptions.resume');


// =======================
// ADMIN
// =======================

Route::middleware(['auth'])
    ->prefix('admin')
    ->group(function () {

        // DRIVER
        Route::resource('drivers', DriverController::class);

        // ASSIGN DRIVER / TRIP
        Route::resource('trips', RiwayatAntarJemputController::class);

        // HISTORY DRIVER
        Route::get(
            '/drivers/{id}/history',
            [DriverController::class, 'history']
        );

    });


// =======================
// DRIVER
// =======================

Route::prefix('driver')
    ->middleware('auth')
    ->group(function () {

        Route::get(
            '/jobs',
            [RiwayatAntarJemputController::class, 'driverJobs']
        );

        Route::put(
            '/jobs/{id}/status',
            [RiwayatAntarJemputController::class, 'updateStatus']
        );

    });