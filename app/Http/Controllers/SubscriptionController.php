<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
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
        Subscription::where(
            'status',
            'pending_cash'
        )
            ->where(
                'cash_deadline',
                '<',
                now()
            )
            ->update([
                'status' => 'cancelled',
            ]);

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
                $query->whereIn('status', [
                    'pending',
                    'pending_cash',
                    'active',
                ]);
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
            'payment_method' => 'required|in:QRIS,Cash',
        ]);

        $kid = auth()->user()
            ->kids()
            ->findOrFail($request->kid_id);

        $alreadySubscribed = Subscription::where('kid_id', $kid->id)
            ->whereIn('status', [
                'pending',
                'active',
                'pending_cash',
            ])
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

        $cashDeadline = null;

        if ($request->payment_method == 'Cash') {
            $cashDeadline = now()->addDays(3);
        }

        $subscription = Subscription::create([
            'user_id' => auth()->id(),
            'kid_id' => $kid->id,
            'package_name' => $request->package_name,
            'price' => $prices[$request->package_name],

            'status' => $request->payment_method == 'Cash'
                ? 'pending_cash'
                : 'pending',

            'payment_method' => $request->payment_method,

            'qris_image' => 'qris/qris-default.png',

            'start_date' => null,
            'end_date' => null,

            'is_paused' => false,
            'remaining_days' => 0,
            'cash_deadline' => $cashDeadline,
        ]);

        if ($request->payment_method == 'Cash') {

            return redirect(
                route(
                    'subscriptions.cash',
                    $subscription->id
                )
            );
        }

        return redirect(
            route(
                'subscriptions.payment',
                $subscription->id
            )
        );
    }

    public function payment($id)
    {
        $subscription = Subscription::with([
            'kid',
            'latestTransaction',
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (
            $subscription->status != 'pending'
            && $subscription->payment_method == 'QRIS'
        ) {
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

            $startDate = $this->getSubscriptionStartDate();
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

    private function addSchoolDays(
        Carbon $startDate,
        int $totalSchoolDays
    ) {
        $date = $startDate->copy();

        $count = 0;

        while ($count < $totalSchoolDays) {
            $isWeekend = $date->isWeekend();

            $isHoliday = Holiday::where(
                'holiday_date',
                $date->format('Y-m-d')
            )->exists();

            if (! $isWeekend && ! $isHoliday) {
                $count++;
            }

            if ($count < $totalSchoolDays) {
                $date->addDay();
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

        $startDate = $this->getSubscriptionStartDate();
        $endDate = $this->addSchoolDays($startDate, $duration);

        $subscription->update([
            'status' => 'active',
            'payment_method' => 'QRIS',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }

    private function getSubscriptionStartDate()
    {
        $date = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Batas operasional armada
        |--------------------------------------------------------------------------
        | Jika pembayaran dilakukan setelah pukul 05:30,
        | maka langganan mulai berlaku pada hari sekolah berikutnya.
        |--------------------------------------------------------------------------
        */

        $cutoffHour = 5;
        $cutoffMinute = 30;

        $afterCutoff =
            $date->hour > $cutoffHour
            ||
            (
                $date->hour == $cutoffHour
                &&
                $date->minute >= $cutoffMinute
            );

        if ($afterCutoff) {
            $date->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Lewati Sabtu, Minggu, dan Hari Libur Nasional
        |--------------------------------------------------------------------------
        */

        while (
            $date->isWeekend()
            ||
            Holiday::where(
                'holiday_date',
                $date->format('Y-m-d')
            )->exists()
        ) {
            $date->addDay();
        }

        return $date->startOfDay();
    }

    public function cashPayment($id)
    {
        $subscription = Subscription::findOrFail($id);

        return view(
            'subscriptions.cash',
            compact('subscription')
        );
    }

    public function cashConfirm($id)
    {
        $subscription = Subscription::where(
            'user_id',
            auth()->id()
        )->findOrFail($id);

        $subscription->update([
            'status' => 'pending_cash',
        ]);

        return redirect('/subscriptions')
            ->with(
                'success',
                'Permintaan pembayaran cash berhasil dikirim. Silakan lakukan pembayaran kepada admin atau sopir.'
            );
    }

    public function verifyCash($id)
    {
        $subscription = Subscription::findOrFail($id);

        if ($subscription->status != 'pending_cash') {
            return back()->with(
                'error',
                'Status langganan bukan pending cash.'
            );
        }

        $durations = [
            'Harian' => 1,
            'Mingguan' => 5,
            'Bulanan' => 20,
        ];

        $duration =
            $durations[$subscription->package_name] ?? 1;

        $startDate = $this->getSubscriptionStartDate();

        $endDate = $this->addSchoolDays(
            $startDate,
            $duration
        );

        $subscription->update([
            'status' => 'active',
            'cash_paid_at' => now(),
            'verified_by' => auth()->id(),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return back()->with(
            'success',
            'Pembayaran cash berhasil diverifikasi.'
        );
    }
}
