<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::latest()->get();

        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:subscription_packages,name',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable',
            'is_active' => 'required|in:0,1',
        ]);

        SubscriptionPackage::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect('/admin/packages')
            ->with('success', 'Paket langganan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $package = SubscriptionPackage::findOrFail($id);

        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = SubscriptionPackage::findOrFail($id);

        $request->validate([
            'name' => 'required|max:100|unique:subscription_packages,name,'.$package->id,
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable',
            'is_active' => 'required|in:0,1',
        ]);

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect('/admin/packages')
            ->with('success', 'Paket langganan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $package = SubscriptionPackage::findOrFail($id);

        $package->update([
            'is_active' => false,
        ]);

        return redirect('/admin/packages')
            ->with('success', 'Paket langganan berhasil dinonaktifkan.');
    }
}
