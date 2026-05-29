<?php

namespace App\Http\Controllers;

use App\Models\Kid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KidController extends Controller
{
    public function index(Request $request)
    {

        $query = Kid::where('parent_id', auth()->id());

        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('school_name', 'like', '%'.$request->search.'%');
        }

        if ($request->school) {
            $query->where('school_name', $request->school);
        }

        $kids = $query->paginate(5);

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
            'parent_id' => auth()->id(),

            'name' => $request->name,
            'school_name' => $request->school_name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'pickup_point' => $request->pickup_point,
            'dropoff_point' => $request->dropoff_point,
            'photo' => $photo ?? null,
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
        $request->validate([
            'name' => 'required|max:100',
            'school_name' => 'required|max:150',
            'address' => 'required',
        ]);

        $kid = Kid::where('parent_id', Auth::id())->findOrFail($id);

        $data = [
            'name' => $request->name,
            'school_name' => $request->school_name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'pickup_point' => $request->pickup_point,
            'dropoff_point' => $request->dropoff_point,
        ];

        // upload foto baru
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('kids', 'public');
        }

        $kid->update($data);

        return redirect('/kids')->with('success', 'Data anak berhasil diupdate');
    }

    public function show(Kid $kid)
    {
        return view('kids.show', compact('kid'));
    }

    public function destroy($id)
    {
        $kid = Kid::where('parent_id', Auth::id())->findOrFail($id);
        $kid->delete();

        return redirect('/kids')->with('success', 'Data anak dihapus');
    }
}
