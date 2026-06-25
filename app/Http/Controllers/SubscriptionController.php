<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\PaymentGroup;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    private function syncSubscriptionStatuses(): void
    {
        Subscription::syncExpired();
    }

    private function packagePrices(): array
    {
        return [
            'Harian' => 50000,
            'Mingguan' => 250000,
            'Bulanan' => 800000,
        ];
    }

    private function packageDurations(): array
    {
        return [
            'Harian' => 1,
            'Mingguan' => 5,
            'Bulanan' => 20,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->syncSubscriptionStatuses();

        $kids = auth()->user()
            ->kids()
            ->with([
                'activeSubscription',
                'latestSubscription.paymentGroup',
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
        $this->syncSubscriptionStatuses();

        $today = Carbon::today()->toDateString();

        $kids = auth()->user()
            ->kids()
            ->whereDoesntHave('subscriptions', function ($query) use ($today) {
                $query->whereIn('status', [
                    'pending',
                    'pending_cash',
                ])
                    ->orWhere(function ($query) use ($today) {
                        $query->where('status', 'active')
                            ->whereDate('end_date', '>=', $today);
                    });
            })
            ->get();

        $packages = SubscriptionPackage::where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('subscriptions.create', compact('kids', 'packages'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE - MEMBUAT TAGIHAN GABUNGAN
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'kid_ids' => 'nullable|array',
            'kid_ids.*' => 'exists:kids,id',
            'kid_id' => 'nullable|exists:kids,id',
            'package_id' => 'required|exists:subscription_packages,id',
            'payment_method' => 'required|in:QRIS,Cash',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Support dua bentuk input:
        | - kid_ids[] untuk sistem baru multi anak
        | - kid_id untuk sistem lama satu anak
        |--------------------------------------------------------------------------
        */
        $selectedKidIds = collect($request->input('kid_ids', []));

        if ($request->filled('kid_id')) {
            $selectedKidIds->push($request->kid_id);
        }

        $selectedKidIds = $selectedKidIds
            ->filter()
            ->unique()
            ->values();

        if ($selectedKidIds->isEmpty()) {
            return back()
                ->withInput()
                ->with('error', 'Pilih minimal satu anak untuk berlangganan.');
        }

        $kids = auth()->user()
            ->kids()
            ->whereIn('id', $selectedKidIds)
            ->get();

        if ($kids->count() !== $selectedKidIds->count()) {
            return back()
                ->withInput()
                ->with('error', 'Ada data anak yang tidak valid atau bukan milik akun ini.');
        }

        $today = Carbon::today()->toDateString();

        $alreadySubscriptions = Subscription::with('kid')
            ->whereIn('kid_id', $selectedKidIds)
            ->where(function ($query) use ($today) {
                $query->whereIn('status', [
                    'pending',
                    'pending_cash',
                ])
                    ->orWhere(function ($query) use ($today) {
                        $query->where('status', 'active')
                            ->whereDate('end_date', '>=', $today);
                    });
            })
            ->get();

        if ($alreadySubscriptions->isNotEmpty()) {
            $names = $alreadySubscriptions
                ->pluck('kid.name')
                ->filter()
                ->implode(', ');

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Anak berikut sudah memiliki langganan aktif atau sedang menunggu pembayaran: '.$names
                );
        }

        $package = SubscriptionPackage::where('is_active', true)
            ->findOrFail($request->package_id);

        $price = $package->price;

        $totalPrice = $price * $kids->count();

        $cashDeadline = null;

        if ($request->payment_method === 'Cash') {
            $cashDeadline = now()->addDays(3);
        }

        $paymentGroup = DB::transaction(function () use (
            $request,
            $kids,
            $package,
            $totalPrice,
            $cashDeadline
        ) {
            $paymentGroup = PaymentGroup::create([
                'user_id' => auth()->id(),
                'invoice_code' => 'INV-'.now()->format('YmdHis').'-'.strtoupper(Str::random(4)),
                'payment_method' => $request->payment_method,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'cash_deadline' => $cashDeadline,
                'paid_at' => null,
                'verified_by' => null,
            ]);

            foreach ($kids as $kid) {
                Subscription::create([
                    'user_id' => auth()->id(),
                    'kid_id' => $kid->id,
                    'payment_group_id' => $paymentGroup->id,
                    'package_name' => $package->name,
                    'price' => $package->price,
                    'duration_days' => $package->duration_days,
                    'status' => $request->payment_method === 'Cash'
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
            }

            return $paymentGroup;
        });

        return redirect()
            ->route('subscriptions.groupPayment', $paymentGroup->id)
            ->with('success', 'Tagihan gabungan berhasil dibuat.');
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT LAMA - JIKA DATA LAMA MASIH ADA
    |--------------------------------------------------------------------------
    */
    public function payment($id)
    {
        $subscription = Subscription::with([
            'kid',
            'latestTransaction',
            'paymentGroup',
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($subscription->payment_group_id) {
            return redirect()
                ->route('subscriptions.groupPayment', $subscription->payment_group_id);
        }

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

        if ($subscription->payment_group_id) {
            return redirect()
                ->route('subscriptions.groupPayment', $subscription->payment_group_id);
        }

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
        $transaction = Transaction::with('subscription.paymentGroup')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($transaction->payment_status == 'paid') {
            return redirect('/subscriptions')
                ->with('success', 'Pembayaran sudah berhasil sebelumnya.');
        }

        $subscription = $transaction->subscription;

        if ($subscription->payment_group_id) {
            return redirect()
                ->route('subscriptions.groupPayment', $subscription->payment_group_id);
        }

        DB::transaction(function () use ($transaction, $subscription) {
            $transaction->update([
                'payment_status' => 'paid',
                'transaction_code' => 'SIM-'.now()->format('YmdHis'),
                'paid_at' => now(),
            ]);

            $this->activateSubscription($subscription, 'QRIS');
        });

        return redirect('/subscriptions')
            ->with('success', 'Pembayaran berhasil. Langganan sudah aktif.');
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT GROUP - TAGIHAN GABUNGAN
    |--------------------------------------------------------------------------
    */
    public function groupPayment($id)
    {
        $paymentGroup = PaymentGroup::with([
            'subscriptions.kid',
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('subscriptions.group-payment', compact('paymentGroup'));
    }

    public function simulateGroupPaymentSuccess($id)
    {
        $paymentGroup = PaymentGroup::with([
            'subscriptions',
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($paymentGroup->payment_method !== 'QRIS') {
            return back()
                ->with('error', 'Simulasi pembayaran hanya berlaku untuk metode QRIS.');
        }

        if ($paymentGroup->status === 'paid') {
            return redirect('/subscriptions')
                ->with('success', 'Tagihan ini sudah dibayar sebelumnya.');
        }

        DB::transaction(function () use ($paymentGroup) {
            $paymentGroup->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            foreach ($paymentGroup->subscriptions as $subscription) {
                $this->activateSubscription($subscription, 'QRIS');

                Transaction::create([
                    'subscription_id' => $subscription->id,
                    'user_id' => $paymentGroup->user_id,
                    'order_id' => $paymentGroup->invoice_code.'-'.$subscription->id,
                    'amount' => $subscription->price,
                    'payment_method' => 'QRIS',
                    'payment_status' => 'paid',
                    'payment_url' => null,
                    'transaction_code' => 'SIM-'.now()->format('YmdHis').'-'.$subscription->id,
                    'paid_at' => now(),
                ]);
            }
        });

        return redirect('/subscriptions')
            ->with('success', 'Pembayaran gabungan berhasil. Semua langganan anak telah aktif.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN TRANSAKSI
    |--------------------------------------------------------------------------
    */
    public function adminTransaksi()
    {
        $this->syncSubscriptionStatuses();

        $subscriptions = Subscription::with([
            'user',
            'kid',
            'paymentGroup',
        ])
            ->latest()
            ->get();

        return view('transaksi.index', compact('subscriptions'));
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN QRIS OLEH ADMIN
    |--------------------------------------------------------------------------
    */
    public function verifikasiPembayaran($id)
    {
        $subscription = Subscription::with('paymentGroup.subscriptions')
            ->findOrFail($id);

        if ($subscription->payment_group_id) {
            $paymentGroup = $subscription->paymentGroup;

            if (! $paymentGroup) {
                return back()->with('error', 'Data tagihan gabungan tidak ditemukan.');
            }

            if ($paymentGroup->status === 'paid') {
                return back()->with('success', 'Tagihan ini sudah dibayar sebelumnya.');
            }

            DB::transaction(function () use ($paymentGroup) {
                $paymentGroup->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'verified_by' => auth()->id(),
                ]);

                foreach ($paymentGroup->subscriptions as $item) {
                    $this->activateSubscription($item, 'QRIS');
                }
            });

            return back()->with('success', 'Pembayaran gabungan berhasil diverifikasi.');
        }

        if ($subscription->status !== 'pending') {
            return back()->with(
                'error',
                'Langganan ini tidak dalam status menunggu pembayaran QRIS.'
            );
        }

        $this->activateSubscription($subscription, 'QRIS');

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /*
    |--------------------------------------------------------------------------
    | CASH
    |--------------------------------------------------------------------------
    */
    public function cashPayment($id)
    {
        $subscription = Subscription::with('paymentGroup.subscriptions.kid')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($subscription->payment_group_id) {
            return redirect()
                ->route('subscriptions.groupPayment', $subscription->payment_group_id);
        }

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

        if ($subscription->payment_group_id) {
            return redirect()
                ->route('subscriptions.groupPayment', $subscription->payment_group_id)
                ->with(
                    'success',
                    'Permintaan pembayaran cash berhasil dibuat. Silakan lakukan pembayaran kepada admin atau sopir.'
                );
        }

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
        $subscription = Subscription::with('paymentGroup.subscriptions')
            ->findOrFail($id);

        if ($subscription->payment_group_id) {
            $paymentGroup = $subscription->paymentGroup;

            if (! $paymentGroup) {
                return back()->with('error', 'Data tagihan gabungan tidak ditemukan.');
            }

            if ($paymentGroup->status === 'paid') {
                return back()->with('success', 'Tagihan ini sudah diverifikasi sebelumnya.');
            }

            DB::transaction(function () use ($paymentGroup) {
                $paymentGroup->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'verified_by' => auth()->id(),
                ]);

                foreach ($paymentGroup->subscriptions as $item) {
                    $this->activateSubscription($item, 'Cash');
                }
            });

            return back()
                ->with('success', 'Pembayaran cash gabungan berhasil diverifikasi.');
        }

        if ($subscription->status != 'pending_cash') {
            return back()->with(
                'error',
                'Status langganan bukan pending cash.'
            );
        }

        $this->activateSubscription($subscription, 'Cash');

        return back()->with(
            'success',
            'Pembayaran cash berhasil diverifikasi.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER AKTIVASI LANGGANAN
    |--------------------------------------------------------------------------
    */
    private function activateSubscription(
        Subscription $subscription,
        string $paymentMethod
    ): void {
        $duration = $subscription->duration_days;

        if (! $duration) {
            $durations = $this->packageDurations();
            $duration = $durations[$subscription->package_name] ?? 1;
        }

        $startDate = $this->getSubscriptionStartDate();

        $endDate = $this->addSchoolDays(
            $startDate,
            $duration
        );

        $data = [
            'status' => 'active',
            'payment_method' => $paymentMethod,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        if ($paymentMethod === 'Cash') {
            $data['cash_paid_at'] = now();
            $data['verified_by'] = auth()->id();
        }

        $subscription->update($data);
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

    private function getSubscriptionStartDate()
    {
        $date = Carbon::now();

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
}
