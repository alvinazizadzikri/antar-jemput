<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::all();

        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        SubscriptionPackage::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('packages.index')->with('success', 'Package berhasil ditambahkan');
    }

    public function edit($id)
    {
        $package = SubscriptionPackage::findOrFail($id);

        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = SubscriptionPackage::findOrFail($id);

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('packages.index')->with('success', 'Package berhasil diupdate');
    }

    public function destroy($id)
    {
        $package = SubscriptionPackage::findOrFail($id);
        $package->delete();

        return redirect()->route('packages.index')->with('success', 'Package berhasil dihapus');
    }
}
