<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\RiwayatAntarJemput;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    /**
     * Menampilkan daftar sopir.
     */
    public function index()
    {
        $drivers = Driver::with('user')
            ->latest()
            ->get();

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Menampilkan form tambah sopir.
     */
    public function create()
    {
        return view('drivers.create');
    }

    /**
     * Menyimpan akun user driver sekaligus data sopir.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|max:20',
            'plate_number' => 'required|string|max:50|unique:drivers,plate_number',
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:online,offline',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'driver',
            ]);

            Driver::create([
                'user_id' => $user->id,
                'vehicle_type' => 'Mobil',
                'phone_number' => $request->phone_number,
                'plate_number' => strtoupper($request->plate_number),
                'capacity' => $request->capacity,
                'status' => $request->status,
            ]);

        });

        return redirect('/admin/drivers')
            ->with('success', 'Akun sopir dan data kendaraan berhasil dibuat.');
    }

    /**
     * Detail sopir, sementara belum digunakan.
     */
    public function show(Driver $driver)
    {
        return redirect('/admin/drivers');
    }

    /**
     * Menampilkan form edit sopir.
     */
    public function edit($id)
    {
        $driver = Driver::with('user')->findOrFail($id);

        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update akun user driver dan data sopir.
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($driver->user_id),
            ],

            'password' => 'nullable|string|min:6',
            'phone_number' => 'required|string|max:20',

            'plate_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('drivers', 'plate_number')->ignore($driver->id),
            ],

            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:online,offline',
        ]);

        DB::transaction(function () use ($request, $driver) {

            $driver->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'driver',
            ]);

            if ($request->filled('password')) {
                $driver->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $driver->update([
                'vehicle_type' => 'Mobil',
                'plate_number' => strtoupper($request->plate_number),
                'phone_number' => $request->phone_number,
                'capacity' => $request->capacity,
                'status' => $request->status,
            ]);

        });

        return redirect('/admin/drivers')
            ->with('success', 'Data sopir berhasil diperbarui.');
    }

    /**
     * Menghapus data sopir dan akun user-nya.
     */
    public function destroy($id)
    {
        $driver = Driver::with('user')->findOrFail($id);

        $hasActiveTrip = RiwayatAntarJemput::where('driver_id', $driver->id)
            ->whereIn('status', [
                'assigned',
                'picked_up',
                'arrived_school',
                'picked_up_school',
            ])
            ->exists();

        if ($hasActiveTrip) {
            return back()
                ->with('error', 'Sopir tidak dapat dihapus karena masih memiliki perjalanan aktif.');
        }

        DB::transaction(function () use ($driver) {

            $user = $driver->user;

            $driver->delete();

            if ($user) {
                $user->delete();
            }

        });

        return redirect('/admin/drivers')
            ->with('success', 'Data sopir dan akun login berhasil dihapus.');
    }

    /**
     * Riwayat perjalanan sopir.
     */
    public function history($id)
    {
        $driver = Driver::with('user')->findOrFail($id);

        $trips = RiwayatAntarJemput::with([
            'kid',
            'kid.parent',
            'kid.subscription',
        ])
            ->where('driver_id', $driver->id)
            ->latest()
            ->get();

        return view('drivers.history', compact('driver', 'trips'));
    }
}
