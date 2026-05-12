<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::with('user')->get();

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'driver')->get();

        return view('drivers.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'user_id' => 'required',

            'vehicle_type' => 'required',

            'plate_number' => 'required',

            'status' => 'required',

        ]);

        Driver::create([

            'user_id' => $request->user_id,

            'vehicle_type' => $request->vehicle_type,

            'plate_number' => $request->plate_number,

            'status' => $request->status,

        ]);

        return redirect('/admin/drivers')
            ->with('success', 'Driver berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $driver->update($request->only([
            'vehicle_type',
            'plate_number',
            'status',
        ]));

        return response()->json([
            'message' => 'Driver updated',
            'data' => $driver,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return response()->json([
            'message' => 'Driver deleted',
        ]);
    }
}
