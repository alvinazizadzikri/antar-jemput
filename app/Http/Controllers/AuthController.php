<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // TAMPILKAN LOGIN
    public function showLogin(){
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request){
        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials)){
            return redirect('/dashboard');
        }

        return back()->with('error','Email atau password salah');
    }

    // TAMPILKAN REGISTER
    public function showRegister(){
        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request){
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'parent'
        ]);

        return redirect('/login')->with('success','Register berhasil');
    }

    // LOGOUT
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}