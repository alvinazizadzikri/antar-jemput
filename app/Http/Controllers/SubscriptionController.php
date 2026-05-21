<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $subscriptions = Subscription::with('kid')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $kids = auth()->user()->kids;

        return view('subscriptions.create', compact('kids'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'kid_id' => 'required',
            'package_name' => 'required',
        ]);

        $prices = [
            'Harian' => 50000,
            'Mingguan' => 250000,
            'Bulanan' => 800000,
        ];

        /*
        |--------------------------------------------------------------------------
        | DURASI PAKET
        |--------------------------------------------------------------------------
        */
        $durations = [
            'Harian' => 1,
            'Mingguan' => 7,
            'Bulanan' => 30,
        ];

        $startDate = Carbon::today();

        $endDate = Carbon::today()->addDays(
            $durations[$request->package_name]
        );

        Subscription::create([

            'user_id' => auth()->id(),

            'kid_id' => $request->kid_id,

            'package_name' => $request->package_name,

            'price' => $prices[$request->package_name],

            'status' => 'active',

            'payment_method' => 'QRIS',

            'qris_image' => 'qris/qris-default.png',

            'start_date' => $startDate,

            'end_date' => $endDate,

            'is_paused' => false,

            'remaining_days' => 0,

        ]);

        return redirect('/subscriptions')
            ->with('success', 'Langganan berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | PAUSE SUBSCRIPTION
    |--------------------------------------------------------------------------
    */
    public function pause($id)
    {
        $subscription = Subscription::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | HITUNG SISA HARI
        |--------------------------------------------------------------------------
        */
        $today = Carbon::today();

        $remaining = $today->diffInDays(
            Carbon::parse($subscription->end_date),
            false
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */
        $subscription->update([

            'is_paused' => true,

            'pause_start' => now(),

            'remaining_days' => $remaining,

            'pause_reason' => 'Anak sakit',

        ]);

        return back()->with(
            'success',
            'Langganan berhasil dipause'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESUME SUBSCRIPTION
    |--------------------------------------------------------------------------
    */
    public function resume($id)
    {
        $subscription = Subscription::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | BUAT TANGGAL AKHIR BARU
        |--------------------------------------------------------------------------
        */
        $newEndDate = Carbon::today()
            ->addDays($subscription->remaining_days);

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */
        $subscription->update([

            'is_paused' => false,

            'pause_end' => now(),

            'end_date' => $newEndDate,

        ]);

        return back()->with(
            'success',
            'Langganan kembali aktif'
        );
    }
}
