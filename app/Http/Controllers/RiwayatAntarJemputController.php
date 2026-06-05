<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Kid;
use App\Models\RiwayatAntarJemput;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $tripGroups = $trips->groupBy(function ($trip) {
            return $trip->trip_code ?: 'OLD-'.$trip->id;
        });

        return view('trips.index', compact('tripGroups'));
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
            ->get()
            ->map(function ($driver) {
                $driver->active_passengers_count = RiwayatAntarJemput::where('driver_id', $driver->id)
                    ->whereIn('status', [
                        'assigned',
                        'on_pickup',
                        'picked',
                        'on_delivery',
                    ])
                    ->count();

                return $driver;
            });

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
        ]);

        $activeStatuses = [
            'assigned',
            'on_pickup',
            'picked',
            'on_delivery',
        ];

        $selectedKidIds = collect($request->kid_ids)
            ->unique()
            ->values()
            ->toArray();

        $driver = Driver::where('status', 'online')
            ->findOrFail($request->driver_id);

        $capacity = $driver->capacity ?? 1;

        $selectedKids = Kid::with(['parent', 'subscription'])
            ->whereIn('id', $selectedKidIds)
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'active')
                    ->whereDate('start_date', '<=', Carbon::today())
                    ->whereDate('end_date', '>=', Carbon::today());
            })
            ->get();

        if ($selectedKids->count() !== count($selectedKidIds)) {
            return back()
                ->withInput()
                ->with('error', 'Ada anak yang belum memiliki langganan aktif.');
        }

        $activePassengers = RiwayatAntarJemput::where('driver_id', $driver->id)
            ->whereIn('status', $activeStatuses)
            ->count();

        $totalPassengers = $activePassengers + $selectedKids->count();

        if ($totalPassengers > $capacity) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jumlah anak melebihi kapasitas kendaraan. Kapasitas sopir ini hanya '
                    .$capacity.
                    ' anak, sedangkan total penumpang aktif menjadi '
                    .$totalPassengers.
                    ' anak.'
                );
        }

        foreach ($selectedKids as $kid) {
            $activeKidTrip = RiwayatAntarJemput::where('kid_id', $kid->id)
                ->whereIn('status', $activeStatuses)
                ->exists();

            if ($activeKidTrip) {
                return back()
                    ->withInput()
                    ->with('error', 'Anak '.$kid->name.' masih memiliki perjalanan aktif.');
            }
        }

        $pickupDateTime = Carbon::today()->format('Y-m-d').' '.$request->pickup_time.':00';

        $tripCode = 'TRIP-'.now()->format('YmdHis').'-'.strtoupper(Str::random(4));

        DB::transaction(function () use ($selectedKids, $driver, $pickupDateTime, $tripCode) {
            foreach ($selectedKids as $kid) {
                RiwayatAntarJemput::create([
                    'kid_id' => $kid->id,
                    'driver_id' => $driver->id,
                    'trip_code' => $tripCode,
                    'pickup_time' => $pickupDateTime,
                    'status' => 'assigned',
                ]);
            }
        });

        return redirect('/admin/trips')
            ->with(
                'success',
                'Sopir berhasil ditugaskan untuk '.$selectedKids->count().' anak.'
            );
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

        $tripGroups = $trips->groupBy(function ($trip) {
            return $trip->trip_code ?: 'OLD-'.$trip->id;
        });

        return view('driver.jobs', compact('tripGroups'));
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER - UPDATE STATUS PERJALANAN
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:assigned,on_pickup,picked,on_delivery,completed',
        ]);

        $driver = Driver::where('user_id', auth()->id())
            ->firstOrFail();

        $trip = RiwayatAntarJemput::where('driver_id', $driver->id)
            ->findOrFail($id);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->status == 'completed') {
            $updateData['dropoff_time'] = now();
        }

        $query = RiwayatAntarJemput::where('driver_id', $driver->id);

        if ($trip->trip_code) {
            $query->where('trip_code', $trip->trip_code);
        } else {
            $query->where('id', $trip->id);
        }

        $query->update($updateData);

        return redirect('/driver/jobs')
            ->with('success', 'Status seluruh anak dalam perjalanan ini berhasil diperbarui.');
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
