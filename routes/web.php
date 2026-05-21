<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RiwayatAntarJemputController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);

// DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// KIDS
Route::resource('kids', KidController::class)->middleware('auth');

// PROFILE
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth');
Route::post('/profile', [AuthController::class, 'updateProfile'])->middleware('auth');

// ADMIN
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // DRIVER
    Route::resource('drivers', DriverController::class);

    // ASSIGN DRIVER / TRIP
    Route::resource('trips', RiwayatAntarJemputController::class);

});

// DRIVER
Route::prefix('driver')->group(function () {

    Route::get('/jobs', [RiwayatAntarJemputController::class, 'driverJobs'])
        ->middleware('auth');

    // update status driver
    Route::put('/jobs/{id}/status',
        [RiwayatAntarJemputController::class, 'updateStatus']
    )->middleware('auth');

});

Route::get('/admin/drivers/{id}/history',
    [DriverController::class, 'history']);