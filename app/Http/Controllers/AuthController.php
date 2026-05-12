<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // TAMPILKAN LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/dashboard');
        }

        return back()->with('error', 'Login gagal');
    }

    // TAMPILKAN REGISTER
    public function showRegister()
    {
        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'parent',
        ]);

        return redirect('/login')->with('success', 'Register berhasil');
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {

        $user = auth()->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diupdate');
    }
}
