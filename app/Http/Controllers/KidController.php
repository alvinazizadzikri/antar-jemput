<?php

namespace App\Http\Controllers;

use App\Models\Kid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KidController extends Controller
{
    public function index()
    {
        $kids = Kid::where('parent_id', Auth::id())->paginate(5);

        return view('kids.index', compact('kids'));
    }

    public function create()
    {
        return view('kids.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:100',
            'school_name' => 'required|max:150',
            'address' => 'required',
        ]);

        $photo = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('kids', 'public');
        }

        Kid::create([
            'parent_id' => Auth::id(),
            'name' => $request->name,
            'school_name' => $request->school_name,
            'address' => $request->address,
            'pickup_point' => $request->pickup_point,
            'dropoff_point' => $request->dropoff_point,
            'photo' => $photo,
        ]);

        return redirect('/kids')->with('success', 'Data anak berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kid = Kid::where('parent_id', Auth::id())->findOrFail($id);

        return view('kids.edit', compact('kid'));
    }

    public function update(Request $request, $id)
    {

        $kid = Kid::where('parent_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('kids', 'public');
            $kid->photo = $photo;
        }

        $kid->update([
            'name' => $request->name,
            'school_name' => $request->school_name,
            'address' => $request->address,
            'pickup_point' => $request->pickup_point,
            'dropoff_point' => $request->dropoff_point,
        ]);

        $request->validate([
            'name' => 'required|max:100',
            'school_name' => 'required|max:150',
            'address' => 'required',
        ]);

        return redirect('/kids')->with('success', 'Data anak berhasil diupdate');
    }

    public function destroy($id)
    {
        $kid = Kid::where('parent_id', Auth::id())->findOrFail($id);
        $kid->delete();

        return redirect('/kids')->with('success', 'Data anak dihapus');
    }
}
