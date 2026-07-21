<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Kid;
use App\Models\KidAbsence;
use App\Models\RiwayatAntarJemput;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RiwayatAntarJemputController extends Controller
{
    private function activeTripStatuses(): array
    {
        return [
            'assigned',
            'picked_up',
            'arrived_school',
            'picked_up_school',
        ];
    }

    private function nextStatusMap(): array
    {
        return [
            'assigned' => ['picked_up'],
            'picked_up' => ['arrived_school'],
            'arrived_school' => ['picked_up_school'],
            'picked_up_school' => ['completed'],
            'return_cancelled' => [],
            'completed' => [],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - RIWAYAT PERJALANAN + FILTER
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = RiwayatAntarJemput::with([
            'driver.user',
            'kid.parent',
            'kid.subscription',
        ]);

        if ($request->filled('trip_code')) {
            $query->where('trip_code', 'like', '%'.$request->trip_code.'%');
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('pickup_time', $request->date);
        }

        $trips = $query
            ->latest()
            ->get();

        $tripGroups = $trips->groupBy(function ($trip) {
            return $trip->trip_code ?: 'OLD-'.$trip->id;
        });

        $drivers = Driver::with('user')
            ->latest()
            ->get();

        return view('trips.index', compact('tripGroups', 'drivers'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - FORM ASSIGN DRIVER + BADGE KAPASITAS
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $activeStatuses = $this->activeTripStatuses();

        $drivers = Driver::with('user')
            ->where('status', 'online')
            ->get()
            ->map(function ($driver) use ($activeStatuses) {
                $activePassengersCount = RiwayatAntarJemput::where('driver_id', $driver->id)
                    ->whereIn('status', $activeStatuses)
                    ->count();

                $driver->active_passengers_count = $activePassengersCount;
                $driver->remaining_capacity = max(0, ($driver->capacity ?? 0) - $activePassengersCount);

                return $driver;
            });

        $kids = Kid::with([
            'parent',
            'subscription',
        ])
            ->whereHas('subscriptions', function ($query) {
                $query->whereIn('status', [
                    'active',
                    'scheduled',
                ])
                    ->whereDate('start_date', '<=', Carbon::tomorrow())
                    ->whereDate('end_date', '>=', Carbon::tomorrow());
            })
            ->whereDoesntHave('trips', function ($query) use ($activeStatuses) {
                $query->whereIn('status', $activeStatuses);
            })
            ->whereDoesntHave('absences', function ($query) {
                $query->whereDate('absence_date', Carbon::today());
            })
            ->get();

        return view('trips.create', compact('drivers', 'kids'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - SIMPAN ASSIGN DRIVER MULTI ANAK
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'kid_ids' => 'required|array|min:1',
            'kid_ids.*' => 'exists:kids,id',
            'trip_date' => 'required|date',
            'pickup_time' => 'required|date_format:H:i',
        ]);

        $activeStatuses = $this->activeTripStatuses();

        $selectedKidIds = collect($request->kid_ids)
            ->unique()
            ->values()
            ->toArray();

        $absence = KidAbsence::with('kid')
            ->whereIn('kid_id', $selectedKidIds)
            ->whereDate('absence_date', $request->trip_date)
            ->first();

        if ($absence) {
            return back()
                ->withInput()
                ->with('error', 'Anak '.$absence->kid->name.' sedang izin hari ini dan tidak dapat ditugaskan.');
        }

        $driver = Driver::where('status', 'online')
            ->findOrFail($request->driver_id);

        $capacity = $driver->capacity ?? 1;

        $selectedKids = Kid::with(['parent', 'subscription'])
            ->whereIn('id', $selectedKidIds)
            ->whereHas('subscriptions', function ($query) use ($request) {

                $query->whereIn('status', [
                    'active',
                    'scheduled',
                ])
                    ->whereDate('start_date', '<=', $request->trip_date)
                    ->whereDate('end_date', '>=', $request->trip_date);

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

        $pickupDateTime = Carbon::parse(
            $request->trip_date.' '.$request->pickup_time
        )->format('Y-m-d H:i:s');

        $tripCode = 'TRIP-'.now()->format('YmdHis').'-'.strtoupper(Str::random(4));

        $tripDate = $request->trip_date;

        DB::transaction(function () use (
            $selectedKids,
            $driver,
            $pickupDateTime,
            $tripCode,
            $tripDate
        ) {

            foreach ($selectedKids as $kid) {

                RiwayatAntarJemput::create([
                    'kid_id' => $kid->id,
                    'driver_id' => $driver->id,
                    'trip_code' => $tripCode,
                    'trip_date' => $tripDate,
                    'pickup_time' => $pickupDateTime,
                    'actual_pickup_time' => null,
                    'dropoff_time' => null,
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
    | ADMIN - DETAIL PERJALANAN ROMBONGAN
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $trip = RiwayatAntarJemput::with([
            'kid.parent',
            'kid.subscription',
            'driver.user',
        ])->findOrFail($id);

        if ($trip->trip_code) {
            $tripGroup = RiwayatAntarJemput::with([
                'kid.parent',
                'kid.subscription',
                'driver.user',
            ])
                ->where('driver_id', $trip->driver_id)
                ->where('trip_code', $trip->trip_code)
                ->get();
        } else {
            $tripGroup = collect([$trip]);
        }

        return view('trips.show', compact('trip', 'tripGroup'));
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER - JOB DRIVER BERDASARKAN TRIP CODE
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
            ->orderBy('trip_date', 'asc')
            ->orderBy('pickup_time', 'asc')
            ->get();

        $tripGroups = $trips->groupBy(function ($trip) {
            return $trip->trip_code ?: 'OLD-'.$trip->id;
        });

        return view('driver.jobs', compact('tripGroups'));
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER - UPDATE STATUS BERURUTAN DAN ROMBONGAN
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:assigned,picked_up,arrived_school,picked_up_school,return_cancelled,completed',
        ]);

        $driver = Driver::where('user_id', auth()->id())
            ->firstOrFail();

        $trip = RiwayatAntarJemput::where('driver_id', $driver->id)
            ->findOrFail($id);

        $currentStatus = $trip->status;
        $newStatus = $request->status;

        if ($currentStatus === 'completed') {
            return redirect('/driver/jobs')
                ->with('error', 'Perjalanan yang sudah selesai tidak dapat diubah lagi.');
        }

        $allowedNextStatuses = $this->nextStatusMap()[$currentStatus] ?? [];

        if (! in_array($newStatus, $allowedNextStatuses)) {
            return redirect('/driver/jobs')
                ->with('error', 'Status tidak dapat dilompati. Ikuti urutan perjalanan yang tersedia.');
        }

        $updateData = [
            'status' => $newStatus,
        ];

        if ($newStatus === 'picked_up') {
            $updateData['actual_pickup_time'] = now();
        }

        if ($newStatus === 'completed') {
            $updateData['dropoff_time'] = now();
        }

        DB::transaction(function () use ($trip, $driver, $updateData, $currentStatus) {
            if ($trip->trip_code) {
                RiwayatAntarJemput::where('driver_id', $driver->id)
                    ->where('trip_code', $trip->trip_code)
                    ->where('status', $currentStatus)
                    ->update($updateData);
            } else {
                RiwayatAntarJemput::where('driver_id', $driver->id)
                    ->where('id', $trip->id)
                    ->where('status', $currentStatus)
                    ->update($updateData);
            }
        });

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
