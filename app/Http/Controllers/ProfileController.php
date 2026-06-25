<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        ini_set('memory_limit', '1024M');

        $user = Auth::user();

        // ❌ HAPUS LIMIT 2MB
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {

            // hapus lama
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');

            $filename = time().'.jpg';
            $path = 'avatars/'.$filename;

            $image = Image::make($file->getPathname())
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 75);

            Storage::disk('public')->put($path, (string) $image);

            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
