<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\KidAbsenceController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatAntarJemputController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPackageController;
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
// SUBSCRIPTIONS
// =======================

Route::resource('subscriptions', SubscriptionController::class)
    ->middleware('auth');

// Route::post(
//     '/subscriptions/{id}/pause',
//     [SubscriptionController::class, 'pause']
// )->name('subscriptions.pause');

// Route::post(
//     '/subscriptions/{id}/resume',
//     [SubscriptionController::class, 'resume']
// )->name('subscriptions.resume');

Route::get(
    '/subscriptions/{id}/cash',
    [SubscriptionController::class, 'cashPayment']
)->name('subscriptions.cash');

Route::post(
    '/subscriptions/{id}/cash-confirm',
    [SubscriptionController::class, 'cashConfirm']
)->name('subscriptions.cash.confirm');

// =======================
// PROFILE
// =======================

Route::get('/profile', [ProfileController::class, 'index'])
    ->middleware('auth');

Route::post('/profile', [ProfileController::class, 'update'])
    ->middleware('auth');

// / =======================
// SUBSCRIPTIONS
// =======================

Route::resource('subscriptions', SubscriptionController::class)
    ->middleware('auth');

// PEMBAYARAN QRIS PER SUBSCRIPTION LAMA
Route::get(
    '/subscriptions/{id}/payment',
    [SubscriptionController::class, 'payment']
)
    ->middleware('auth')
    ->name('subscriptions.payment');

Route::post(
    '/subscriptions/{id}/pay',
    [SubscriptionController::class, 'startPayment']
)
    ->middleware('auth')
    ->name('subscriptions.pay');

Route::post(
    '/transactions/{id}/simulate-success',
    [SubscriptionController::class, 'simulatePaymentSuccess']
)
    ->middleware('auth')
    ->name('transactions.simulateSuccess');

// PEMBAYARAN CASH
Route::get(
    '/subscriptions/{id}/cash',
    [SubscriptionController::class, 'cashPayment']
)
    ->middleware('auth')
    ->name('subscriptions.cash');

Route::post(
    '/subscriptions/{id}/cash-confirm',
    [SubscriptionController::class, 'cashConfirm']
)
    ->middleware('auth')
    ->name('subscriptions.cash.confirm');

// PEMBAYARAN GABUNGAN / MULTI ANAK
Route::get(
    '/subscriptions/payment-groups/{id}/payment',
    [SubscriptionController::class, 'groupPayment']
)
    ->middleware('auth')
    ->name('subscriptions.groupPayment');

Route::post(
    '/subscriptions/payment-groups/{id}/simulate-success',
    [SubscriptionController::class, 'simulateGroupPaymentSuccess']
)
    ->middleware('auth')
    ->name('subscriptions.groupPayment.simulateSuccess');

// =======================
// RIWAYAT ORANG TUA
// =======================

Route::get('/riwayat', [RiwayatAntarJemputController::class, 'parentHistory'])
    ->middleware('auth')
    ->name('riwayat.parent');

// =======================
// ADMIN
// =======================

Route::middleware(['auth'])
    ->prefix('admin')
    ->group(function () {

        Route::resource('packages', SubscriptionPackageController::class)
            ->except(['show']);

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

        Route::post(
            '/transaksi/{id}/verify-cash',
            [SubscriptionController::class, 'verifyCash']
        )->name('admin.transaksi.verifyCash');

        // HISTORY DRIVER
        Route::get(
            '/drivers/{id}/history',
            [DriverController::class, 'history']
        );

        Route::get(
            '/izin-anak',
            [KidAbsenceController::class, 'adminIndex']
        )->name('admin.absences.index');

    });

// =======================
// IZIN ANAK - PARENT
// =======================

Route::get('/izin-anak', [KidAbsenceController::class, 'index'])
    ->middleware('auth')
    ->name('absences.index');

Route::get('/izin-anak/create', [KidAbsenceController::class, 'create'])
    ->middleware('auth')
    ->name('absences.create');

Route::post('/izin-anak', [KidAbsenceController::class, 'store'])
    ->middleware('auth')
    ->name('absences.store');

Route::delete('/izin-anak/{id}', [KidAbsenceController::class, 'destroy'])
    ->middleware('auth')
    ->name('absences.destroy');
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
