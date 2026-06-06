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

        /*
        |--------------------------------------------------------------------------
        | ATURAN UTAMA
        |--------------------------------------------------------------------------
        | Anak yang sudah masuk trip pada tanggal izin tidak boleh mengajukan izin.
        | Jadi sistem tidak mengubah status trip menjadi izin.
        |--------------------------------------------------------------------------
        */

        $alreadyAssignedToTrip = RiwayatAntarJemput::where('kid_id', $kid->id)
            ->whereDate('pickup_time', $absenceDate)
            ->exists();

        if ($alreadyAssignedToTrip) {
            return back()
                ->withInput()
                ->with('error', 'Anak ini sudah masuk penugasan trip pada tanggal tersebut, sehingga izin tidak dapat diajukan.');
        }

        DB::transaction(function () use ($request, $kid, $absenceDate) {
            KidAbsence::updateOrCreate(
                [
                    'kid_id' => $kid->id,
                    'absence_date' => $absenceDate->format('Y-m-d'),
                ],
                [
                    'user_id' => auth()->id(),
                    'reason_type' => $request->reason_type,
                    'note' => $request->note,
                ]
            );
        });

        return redirect('/izin-anak')
            ->with('success', 'Izin anak berhasil diajukan. Anak tidak akan muncul pada penugasan sopir tanggal tersebut.');
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

        $absence->delete();

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

        if ($request->filled('reason_type')) {
            $query->where('reason_type', $request->reason_type);
        }

        $absences = $query
            ->latest('absence_date')
            ->get();

        return view('admin.absences.index', compact('absences'));
    }
}
