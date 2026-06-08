@extends('layouts.app')

@section('content')

    @include('components.subscription-info')

    <div class="section-header">
        <div>
            <div class="page-title">Detail Pembayaran</div>
            <div class="page-subtitle">
                Selesaikan pembayaran langganan antar jemput anak
            </div>
        </div>
    </div>

    <div class="payment-grid">

        <div class="page-card">
            <div class="card-body">

                <h5 class="fw-bold mb-4">Informasi Langganan</h5>

                <div class="detail-row">
                    <div class="detail-label">Kode Pembayaran</div>
                    <div class="detail-value">
                        INV-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Nama Anak</div>
                    <div class="detail-value">
                        {{ $subscription->kid->name ?? '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Sekolah</div>
                    <div class="detail-value">
                        {{ $subscription->kid->school_name ?? '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Paket Langganan</div>
                    <span class="package-badge">
                        {{ $subscription->package_name }}
                    </span>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Metode Pembayaran</div>
                    <div class="detail-value">
                        {{ $subscription->payment_method ?? 'QRIS' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Status Pembayaran</div>
                    <span class="badge-status badge-pending">
                        Menunggu Pembayaran
                    </span>
                </div>

                <div class="amount-box">
                    <h5 class="mb-0 fw-bold">Total Pembayaran</h5>

                    <div class="amount-text">
                        Rp {{ number_format($subscription->price, 0, ',', '.') }}
                    </div>
                </div>

            </div>
        </div>

        <div class="page-card">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Pembayaran</h5>

                <div class="qris-box">
                    <div class="qris-icon">
                        <i class="bi bi-qr-code"></i>
                    </div>

                    <h5 class="fw-bold">QRIS / Payment Gateway</h5>

                    <p class="text-muted mb-0">
                        Halaman ini disiapkan untuk pembayaran melalui payment gateway.
                    </p>
                </div>

                <div class="payment-note">
                    Setelah payment gateway dipasang, tombol di bawah ini akan mengarahkan
                    user ke halaman pembayaran resmi. Jika pembayaran berhasil, status
                    langganan otomatis berubah menjadi <b>Aktif</b>.
                </div>

                @if($subscription->latestTransaction)
                    <div class="transaction-box">
                        <div>
                            <strong>Order ID:</strong>
                            {{ $subscription->latestTransaction->order_id }}
                        </div>

                        <div>
                            <strong>Status Transaksi:</strong>
                            {{ ucfirst($subscription->latestTransaction->payment_status) }}
                        </div>
                    </div>

                    @if($subscription->latestTransaction->payment_status == 'pending')
                        <form action="{{ route('transactions.simulateSuccess', $subscription->latestTransaction->id) }}"
                            method="POST">
                            @csrf

                            <button type="submit" class="btn btn-success-custom w-100 mb-2">
                                Simulasi Pembayaran Berhasil
                            </button>
                        </form>
                    @endif

                @else
                    <form action="{{ route('subscriptions.pay', $subscription->id) }}" method="POST">
                        @csrf

                        <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                            Lanjutkan ke Payment Gateway
                        </button>
                    </form>
                @endif

                <a href="/subscriptions" class="btn btn-secondary-custom w-100">
                    Kembali ke Langganan
                </a>

            </div>
        </div>

    </div>

@endsection