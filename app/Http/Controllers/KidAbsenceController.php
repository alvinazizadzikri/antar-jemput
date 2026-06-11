<?php

namespace App\Http\Controllers;

use App\Models\KidAbsence;
use App\Models\RiwayatAntarJemput;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KidAbsenceController extends Controller
{
    public function index()
    {
        $absences = KidAbsence::with('kid')
            ->where('user_id', auth()->id())
            ->latest('absence_date')
            ->get();

        return view('absences.index', compact('absences'));
    }

    public function create()
    {
        $kids = auth()->user()
            ->kids()
            ->with('activeSubscription')
            ->get();

        return view('absences.create', compact('kids'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kid_id' => 'required|exists:kids,id',
            'absence_type' => 'required|in:full_day,return_only',
            'absence_date' => 'required|date',
            'reason_type' => 'required|in:sakit,keluarga,lainnya',
            'note' => 'nullable|string|max:1000',
        ]);

        $kid = auth()->user()
            ->kids()
            ->findOrFail($request->kid_id);

        $absenceDate = Carbon::parse($request->absence_date)->startOfDay();

        if ($absenceDate->lt(Carbon::today())) {
            return back()
                ->withInput()
                ->with('error', 'Tanggal izin tidak boleh sebelum hari ini.');
        }

        $hasActiveSubscription = $kid->subscriptions()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $absenceDate)
            ->whereDate('end_date', '>=', $absenceDate)
            ->exists();

        if (! $hasActiveSubscription) {
            return back()
                ->withInput()
                ->with('error', 'Anak ini belum memiliki langganan aktif pada tanggal izin tersebut.');
        }

        $trip = RiwayatAntarJemput::where('kid_id', $kid->id)
            ->whereDate('pickup_time', $absenceDate)
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | IZIN FULL DAY
        |--------------------------------------------------------------------------
        | Anak tidak masuk / tidak dijemput sejak awal.
        | Syarat: anak belum masuk trip pada tanggal itu.
        |--------------------------------------------------------------------------
        */
        if ($request->absence_type === 'full_day') {
            if ($trip) {
                return back()
                    ->withInput()
                    ->with('error', 'Anak ini sudah masuk penugasan trip. Jika anak sudah di sekolah dan tidak ikut jemput pulang, pilih jenis izin "Tidak ikut jemput pulang".');
            }

            DB::transaction(function () use ($request, $kid, $absenceDate) {
                KidAbsence::updateOrCreate(
                    [
                        'kid_id' => $kid->id,
                        'absence_date' => $absenceDate->format('Y-m-d'),
                    ],
                    [
                        'user_id' => auth()->id(),
                        'absence_type' => 'full_day',
                        'reason_type' => $request->reason_type,
                        'note' => $request->note,
                    ]
                );
            });

            return redirect('/izin-anak')
                ->with('success', 'Izin berhasil diajukan. Anak tidak akan muncul pada penugasan sopir tanggal tersebut.');
        }

        /*
        |--------------------------------------------------------------------------
        | IZIN TIDAK IKUT JEMPUT PULANG
        |--------------------------------------------------------------------------
        | Anak sudah diantar ke sekolah, tetapi tidak ikut dijemput pulang.
        | Syarat: status perjalanan anak sudah sampai sekolah.
        |--------------------------------------------------------------------------
        */
        if ($request->absence_type === 'return_only') {
            if (! $trip) {
                return back()
                    ->withInput()
                    ->with('error', 'Anak belum memiliki penugasan trip pada tanggal tersebut. Gunakan jenis izin "Tidak masuk / tidak dijemput hari ini".');
            }

            if ($trip->status !== 'arrived_school') {
                return back()
                    ->withInput()
                    ->with('error', 'Izin tidak ikut jemput pulang hanya dapat diajukan jika status anak sudah "Sampai Sekolah".');
            }

            DB::transaction(function () use ($request, $kid, $absenceDate, $trip) {
                KidAbsence::updateOrCreate(
                    [
                        'kid_id' => $kid->id,
                        'absence_date' => $absenceDate->format('Y-m-d'),
                    ],
                    [
                        'user_id' => auth()->id(),
                        'absence_type' => 'return_only',
                        'reason_type' => $request->reason_type,
                        'note' => $request->note,
                    ]
                );

                RiwayatAntarJemput::where('id', $trip->id)
                    ->update([
                        'status' => 'return_cancelled',
                    ]);
            });

            return redirect('/izin-anak')
                ->with('success', 'Izin tidak ikut jemput pulang berhasil diajukan. Sopir akan melihat bahwa anak tidak perlu dijemput pulang.');
        }
    }

    public function destroy($id)
    {
        $absence = KidAbsence::where('user_id', auth()->id())
            ->findOrFail($id);

        $absenceDate = Carbon::parse($absence->absence_date)->startOfDay();

        if ($absenceDate->lt(Carbon::today())) {
            return back()
                ->with('error', 'Izin yang sudah lewat tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($absence, $absenceDate) {
            if ($absence->absence_type === 'return_only') {
                $trip = RiwayatAntarJemput::where('kid_id', $absence->kid_id)
                    ->whereDate('pickup_time', $absenceDate)
                    ->where('status', 'return_cancelled')
                    ->first();

                if ($trip) {
                    $hasReturnProcessStarted = RiwayatAntarJemput::where('driver_id', $trip->driver_id)
                        ->where('trip_code', $trip->trip_code)
                        ->whereIn('status', ['picked_up_school', 'completed'])
                        ->exists();

                    if (! $hasReturnProcessStarted) {
                        $trip->update([
                            'status' => 'arrived_school',
                        ]);
                    }
                }
            }

            $absence->delete();
        });

        return back()
            ->with('success', 'Izin anak berhasil dibatalkan.');
    }

    public function adminIndex(Request $request)
    {
        $query = KidAbsence::with([
            'kid.parent',
            'user',
        ]);

        if ($request->filled('date')) {
            $query->whereDate('absence_date', $request->date);
        }

        if ($request->filled('absence_type')) {
            $query->where('absence_type', $request->absence_type);
        }

        if ($request->filled('reason_type')) {
            $query->where('reason_type', $request->reason_type);
        }

        $absences = $query
            ->latest('absence_date')
            ->get();

        return view('admin.absences.index', compact('absences'));
    }
}
