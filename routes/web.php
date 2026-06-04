<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatAntarJemputController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');

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

Route::get('/riwayat', [RiwayatAntarJemputController::class, 'parentHistory'])
    ->middleware('auth')
    ->name('riwayat.parent');

// =======================
// ADMIN
// =======================

Route::middleware(['auth'])
    ->prefix('admin')
    ->group(function () {

        // DRIVER
        Route::resource('drivers', DriverController::class);

        // ASSIGN DRIVER DAN RIWAYAT PERJALANAN
        Route::resource('trips', RiwayatAntarJemputController::class);

        Route::get(
            '/transaksi',
            [SubscriptionController::class, 'adminTransaksi']
        )->name('admin.transaksi');

        Route::post(
            '/transaksi/{id}/verifikasi',
            [SubscriptionController::class, 'verifikasiPembayaran']
        )->name('admin.transaksi.verifikasi');

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
