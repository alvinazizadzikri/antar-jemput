<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KidController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login']);

Route::get('/register',[AuthController::class,'showRegister']);
Route::post('/register',[AuthController::class,'register']);

Route::get('/logout',[AuthController::class,'logout']);

Route::get('/dashboard', function(){
    return "HALAMAN DASHBOARD";
})->middleware('auth');

Route::resource('kids', KidController::class)->middleware('auth');