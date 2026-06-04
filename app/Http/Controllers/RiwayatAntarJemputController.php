<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Kid;
use App\Models\RiwayatAntarJemput;
use Illuminate\Http\Request;

class RiwayatAntarJemputController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'kid_id' => 'required|exists:kids,id',
        ]);

        // cek driver masih ada perjalanan aktif
        $activeTrip = RiwayatAntarJemput::where('driver_id', $request->driver_id)
            ->whereIn('status', [
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
            ])
            ->exists();

        if ($activeTrip) {

            return back()->with('error', 'Driver masih memiliki perjalanan aktif');

        }

        $trip = RiwayatAntarJemput::create([

            'kid_id' => $request->kid_id,
            'driver_id' => $request->driver_id,
            'pickup_time' => $request->pickup_time,
            'status' => $request->status,

        ]);

        return redirect('/admin/trips')
            ->with('success', 'Driver berhasil di assign');
    }

    public function index()
    {
        $trips = RiwayatAntarJemput::with([
            'driver.user',
            'kid',
        ])->get();

        return view('trips.index', compact('trips'));
    }

    public function create()
    {
        $drivers = Driver::with('user')->get();

        $kids = Kid::with([
            'parent',
            'subscription',
        ])->get();

        return view(
            'trips.create',
            compact('drivers', 'kids')
        );
    }

    public function driverJobs()
    {
        $driver = Driver::where('user_id', auth()->id())->first();

        if (! $driver) {
            return 'Driver tidak ditemukan';
        }

        $trips = RiwayatAntarJemput::with([
            'driver.user',
            'kid.parent',
            'kid.subscription',
        ])
            ->where('driver_id', $driver->id)
            ->latest()
            ->get();

        return view('driver.jobs', compact('trips'));
    }

    public function updateStatus(Request $request, $id)
    {
        $trip = RiwayatAntarJemput::findOrFail($id);

        $trip->status = $request->status;

        // waktu jemput
        if ($request->status == 'picked') {
            $trip->pickup_time = now();
        }

        // waktu sampai
        if ($request->status == 'on_delivery') {
            $trip->dropoff_time = now();
        }

        $trip->save();

        return redirect('/driver/jobs')
            ->with('success', 'Status berhasil diupdate');
    }

    public function show($id)
    {
        $trip = RiwayatAntarJemput::with([
            'kid.parent',
            'kid.subscription',
            'driver.user',
        ])->findOrFail($id);

        return view('trips.show', compact('trip'));
    }
}
