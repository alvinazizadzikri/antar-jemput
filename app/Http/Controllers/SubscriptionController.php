<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $kids = auth()->user()
            ->kids()
            ->with([
                'activeSubscription',
                'latestSubscription',
            ])
            ->get();

        return view('subscriptions.index', compact('kids'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $kids = auth()->user()
            ->kids()
            ->whereDoesntHave('subscriptions', function ($query) {
                $query->whereIn('status', ['pending', 'active']);
            })
            ->get();

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
            'kid_id' => 'required|exists:kids,id',
            'package_name' => 'required|in:Harian,Mingguan,Bulanan',
        ]);

        $kid = auth()->user()
            ->kids()
            ->findOrFail($request->kid_id);

        $alreadySubscribed = Subscription::where('kid_id', $kid->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($alreadySubscribed) {
            return redirect('/subscriptions')
                ->with('error', 'Anak ini sudah memiliki langganan aktif atau sedang menunggu pembayaran.');
        }

        $prices = [
            'Harian' => 50000,
            'Mingguan' => 250000,
            'Bulanan' => 800000,
        ];

        Subscription::create([
            'user_id' => auth()->id(),
            'kid_id' => $kid->id,
            'package_name' => $request->package_name,
            'price' => $prices[$request->package_name],
            'status' => 'pending',
            'payment_method' => 'QRIS',
            'qris_image' => 'qris/qris-default.png',
            'start_date' => null,
            'end_date' => null,
            'is_paused' => false,
            'remaining_days' => 0,
        ]);

        return redirect('/subscriptions')
            ->with('success', 'Langganan berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    public function payment($id)
    {
        $subscription = Subscription::with([
            'kid',
            'latestTransaction',
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($subscription->status != 'pending') {
            return redirect('/subscriptions')
                ->with('error', 'Langganan ini tidak dalam status menunggu pembayaran.');
        }

        return view('subscriptions.payment', compact('subscription'));
    }

    public function startPayment($id)
    {
        $subscription = Subscription::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $existingTransaction = Transaction::where('subscription_id', $subscription->id)
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        if (! $existingTransaction) {
            Transaction::create([
                'subscription_id' => $subscription->id,
                'user_id' => auth()->id(),
                'order_id' => 'INV-'.now()->format('YmdHis').'-'.$subscription->id,
                'amount' => $subscription->price,
                'payment_method' => 'QRIS',
                'payment_status' => 'pending',
                'payment_url' => null,
                'transaction_code' => null,
                'paid_at' => null,
            ]);
        }

        return redirect()
            ->route('subscriptions.payment', $subscription->id)
            ->with('success', 'Transaksi pembayaran berhasil dibuat.');
    }

    public function simulatePaymentSuccess($id)
    {
        $transaction = Transaction::with('subscription')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($transaction->payment_status == 'paid') {
            return redirect('/subscriptions')
                ->with('success', 'Pembayaran sudah berhasil sebelumnya.');
        }

        $subscription = $transaction->subscription;

        $durations = [
            'Harian' => 1,
            'Mingguan' => 5,
            'Bulanan' => 20,
        ];

        $duration = $durations[$subscription->package_name] ?? 1;

        DB::transaction(function () use ($transaction, $subscription, $duration) {
            $transaction->update([
                'payment_status' => 'paid',
                'transaction_code' => 'SIM-'.now()->format('YmdHis'),
                'paid_at' => now(),
            ]);

            $startDate = Carbon::today();
            $endDate = $this->addSchoolDays($startDate, $duration);

            $subscription->update([
                'status' => 'active',
                'payment_method' => 'QRIS',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        });

        return redirect('/subscriptions')
            ->with('success', 'Pembayaran berhasil. Langganan sudah aktif.');
    }

    private function addSchoolDays(Carbon $startDate, int $totalSchoolDays)
    {
        $date = $startDate->copy();

        $count = $date->isWeekday() ? 1 : 0;

        while ($count < $totalSchoolDays) {
            $date->addDay();

            if ($date->isWeekday()) {
                $count++;
            }
        }

        return $date;
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN TRANSAKSI
    |--------------------------------------------------------------------------
    */
    public function adminTransaksi()
    {
        $subscriptions = Subscription::with([
            'user',
            'kid',
        ])
            ->latest()
            ->get();

        return view('transaksi.index', compact('subscriptions'));
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN SEMENTARA
    |--------------------------------------------------------------------------
    | Ini bisa dipakai dulu untuk simulasi.
    | Nanti kalau payment gateway sudah dipasang,
    | bagian ini akan diganti oleh callback payment gateway.
    */
    public function verifikasiPembayaran($id)
    {
        $subscription = Subscription::findOrFail($id);

        $durations = [
            'Harian' => 1,
            'Mingguan' => 5,
            'Bulanan' => 20,
        ];

        $duration = $durations[$subscription->package_name] ?? 1;

        $startDate = Carbon::today();
        $endDate = $this->addSchoolDays($startDate, $duration);

        $subscription->update([
            'status' => 'active',
            'payment_method' => 'QRIS',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }
}
