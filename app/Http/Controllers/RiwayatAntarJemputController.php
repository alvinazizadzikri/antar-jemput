<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Kid;
use App\Models\RiwayatAntarJemput;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiwayatAntarJemputController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN - RIWAYAT PERJALANAN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $trips = RiwayatAntarJemput::with([
            'driver.user',
            'kid.parent',
            'kid.subscription',
        ])
            ->latest()
            ->get();

        return view('trips.index', compact('trips'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - FORM ASSIGN DRIVER
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $drivers = Driver::with('user')
            ->where('status', 'online')
            ->get();

        $kids = Kid::with([
            'parent',
            'subscription',
        ])
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'active')
                    ->whereDate('start_date', '<=', Carbon::today())
                    ->whereDate('end_date', '>=', Carbon::today());
            })
            ->whereDoesntHave('trips', function ($query) {
                $query->whereIn('status', [
                    'assigned',
                    'on_pickup',
                    'picked',
                    'on_delivery',
                ]);
            })
            ->get();

        return view('trips.create', compact('drivers', 'kids'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - SIMPAN ASSIGN DRIVER
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',

            'kid_ids' => 'required|array|min:1',

            'kid_ids.*' => 'exists:kids,id',

            'pickup_time' => 'required|date_format:H:i',

            'status' => 'required|in:assigned,on_pickup,picked,on_delivery,delivered',
        ]);

        $driver = Driver::where('status', 'online')
            ->findOrFail($request->driver_id);

        $selectedKids = Kid::whereIn('id', $request->kid_ids)
            ->get();

        $activePassengers = RiwayatAntarJemput::where('driver_id', $driver->id)
            ->whereIn('status', [
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
            ])
            ->count();

        $totalPassengers =
    $activePassengers + count($request->kid_ids);

        if ($totalPassengers > $driver->capacity) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jumlah anak melebihi kapasitas kendaraan.'
                );
        }

        foreach ($selectedKids as $kid) {
            $activeKidTrip = RiwayatAntarJemput::where(
                'kid_id',
                $kid->id
            )
                ->whereIn('status', [
                    'assigned',
                    'on_pickup',
                    'picked',
                    'on_delivery',
                ])
                ->exists();

            if ($activeKidTrip) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Anak '.$kid->name.' masih memiliki perjalanan aktif.'
                    );
            }
        }

        $pickupDateTime = Carbon::today()->format('Y-m-d').' '.$request->pickup_time.':00';

        $tripCode = 'TRIP-'.now()->format('YmdHis');

        foreach ($selectedKids as $kid) {

            RiwayatAntarJemput::create([

                'kid_id' => $kid->id,

                'driver_id' => $driver->id,

                'trip_code' => $tripCode,

                'pickup_time' => $pickupDateTime,

                'status' => $request->status,

            ]);

        }

        return redirect('/admin/trips')
            ->with('success', 'Sopir berhasil ditugaskan.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - DETAIL PERJALANAN
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $trip = RiwayatAntarJemput::with([
            'kid.parent',
            'kid.subscription',
            'driver.user',
        ])->findOrFail($id);

        return view('trips.show', compact('trip'));
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER - JOB DRIVER
    |--------------------------------------------------------------------------
    */
    public function driverJobs()
    {
        $driver = Driver::where('user_id', auth()->id())->first();

        if (! $driver) {
            return redirect('/dashboard')
                ->with('error', 'Data sopir untuk akun ini tidak ditemukan.');
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

    /*
    |--------------------------------------------------------------------------
    | DRIVER - UPDATE STATUS PERJALANAN
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:assigned,on_pickup,picked,on_delivery,delivered',
        ]);

        $driver = Driver::where('user_id', auth()->id())
            ->firstOrFail();

        $trip = RiwayatAntarJemput::where(
            'driver_id',
            $driver->id
        )->findOrFail($id);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->status == 'delivered') {
            $updateData['dropoff_time'] = now();
        }

        RiwayatAntarJemput::where(
            'trip_code',
            $trip->trip_code
        )->update($updateData);

        return redirect('/driver/jobs')
            ->with(
                'success',
                'Status seluruh penumpang berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PARENT - RIWAYAT ANTAR JEMPUT
    |--------------------------------------------------------------------------
    */
    public function parentHistory()
    {
        $trips = RiwayatAntarJemput::with([
            'kid',
            'driver.user',
        ])
            ->whereHas('kid', function ($query) {
                $query->where('parent_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('riwayat.index', compact('trips'));
    }

    /*
    |--------------------------------------------------------------------------
    | METHOD RESOURCE KOSONG
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        return redirect('/admin/trips');
    }

    public function update(Request $request, $id)
    {
        return redirect('/admin/trips');
    }

    public function destroy($id)
    {
        $trip = RiwayatAntarJemput::findOrFail($id);
        $trip->delete();

        return redirect('/admin/trips')
            ->with('success', 'Data perjalanan berhasil dihapus.');
    }
}
