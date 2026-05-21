<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // UPDATE DATA
        $user->name = $request->name;
        $user->email = $request->email;

        // UPLOAD FOTO
        if ($request->hasFile('avatar')) {

            // hapus foto lama
            if ($user->avatar) {

                Storage::disk('public')->delete($user->avatar);

            }

            // ambil file
            $file = $request->file('avatar');

            // nama file unik
            $filename = time().'.'.$file->getClientOriginalExtension();

            // simpan file
            $file->storeAs('avatars', $filename, 'public');

            // simpan path ke database
            $user->avatar = 'avatars/'.$filename;
        }

        // SIMPAN USER
        $user->save();

        // DEBUG
        // dd($user);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
