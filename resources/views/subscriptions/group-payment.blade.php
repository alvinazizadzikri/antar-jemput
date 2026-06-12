@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Pembayaran Gabungan</div>
            <div class="page-subtitle">
                Satu tagihan untuk beberapa langganan anak
            </div>
        </div>

        <div class="header-actions">
            <a href="/subscriptions" class="btn btn-secondary-custom">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="page-card">
        <div class="card-body">

            <div class="row g-4 mb-4">

                <div class="col-md-4">
                    <div class="info-list-item h-100">
                        <div class="text-muted mb-1">Kode Invoice</div>
                        <div class="fw-bold">
                            {{ $paymentGroup->invoice_code }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-list-item h-100">
                        <div class="text-muted mb-1">Metode Pembayaran</div>
                        <div class="fw-bold">
                            {{ $paymentGroup->payment_method }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-list-item h-100">
                        <div class="text-muted mb-1">Status Tagihan</div>

                        @if($paymentGroup->status == 'paid')
                            <span class="badge-status badge-active">
                                Sudah Dibayar
                            </span>
                        @elseif($paymentGroup->status == 'cancelled')
                            <span class="badge-status badge-danger">
                                Dibatalkan
                            </span>
                        @else
                            <span class="badge-status badge-pending">
                                Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="mb-4">
                <div class="detail-label">Total Pembayaran</div>
                <div class="amount-text">
                    Rp {{ number_format($paymentGroup->total_price, 0, ',', '.') }}
                </div>
            </div>

            @if($paymentGroup->payment_method == 'Cash' && $paymentGroup->cash_deadline)
                <div class="transaction-box">
                    Pembayaran cash harus dibayarkan sebelum:
                    <strong>
                        {{ $paymentGroup->cash_deadline->format('d/m/Y H:i') }}
                    </strong>
                </div>
            @endif

            <div class="table-responsive mb-4">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Sekolah</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Status Langganan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($paymentGroup->subscriptions as $subscription)
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $subscription->kid->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $subscription->kid->school_name ?? '-' }}
                                </td>

                                <td>
                                    <span class="package-badge">
                                        {{ $subscription->package_name }}
                                    </span>
                                </td>

                                <td>
                                    Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge-status {{ $subscription->status_badge_class }}">
                                        {{ $subscription->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($paymentGroup->status == 'paid')

                <div class="alert alert-success mb-0">
                    Tagihan ini sudah dibayar. Semua langganan anak dalam invoice ini telah diproses.
                </div>

            @elseif($paymentGroup->payment_method == 'QRIS')

                <div class="qris-box">
                    <div class="qris-icon">
                        <i class="bi bi-qr-code"></i>
                    </div>

                    <div class="fw-bold mb-1">
                        Simulasi QRIS
                    </div>

                    <div class="text-muted">
                        Untuk demo, klik tombol di bawah untuk mensimulasikan pembayaran berhasil.
                    </div>
                </div>

                <form action="{{ route('subscriptions.groupPayment.simulateSuccess', $paymentGroup->id) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle"></i>
                        Simulasi Pembayaran Berhasil
                    </button>
                </form>

            @elseif($paymentGroup->payment_method == 'Cash')

                <div class="alert alert-warning">
                    Tagihan cash sudah dibuat. Silakan lakukan pembayaran kepada admin atau sopir.
                    Setelah dibayar, admin dapat melakukan verifikasi di halaman transaksi.
                </div>

                <a href="/subscriptions" class="btn btn-secondary-custom">
                    Kembali ke Langganan
                </a>

            @endif

        </div>
    </div>

@endsection