<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::resource('kids', KidController::class)->middleware('auth');

Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth');
Route::post('/profile', [AuthController::class, 'updateProfile'])->middleware('auth');

Route::resource('subscriptions', SubscriptionController::class);

Route::post('/subscriptions/{id}/pause',
    [SubscriptionController::class, 'pause'])
    ->name('subscriptions.pause');

Route::post('/subscriptions/{id}/resume',
    [SubscriptionController::class, 'resume'])
    ->name('subscriptions.resume');
