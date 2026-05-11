<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Driver::with('user')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'vehicle_type' => 'required',
            'plate_number' => 'required',
        ]);

        // buat user driver
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('123456'),
            'role' => 'driver',
        ]);

        // buat data driver
        $driver = Driver::create([
            'user_id' => $user->id,
            'vehicle_type' => $request->vehicle_type,
            'plate_number' => $request->plate_number,
            'status' => 'offline',
        ]);

        return response()->json([
            'message' => 'Driver berhasil dibuat',
            'data' => $driver,
        ]);
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
