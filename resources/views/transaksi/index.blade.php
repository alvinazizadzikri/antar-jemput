@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Transaksi Pembayaran</div>
            <div class="page-subtitle">
                Data pembayaran langganan antar jemput anak
            </div>
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

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Paket</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $subscription->kid->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $subscription->user->name ?? '-' }}
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
                                    {{ $subscription->payment_method ?? 'QRIS' }}
                                </td>

                                <td>
                                    @if($subscription->status == 'pending')

                                        <span class="badge-status badge-pending">
                                            Menunggu Pembayaran QRIS
                                        </span>

                                    @elseif($subscription->status == 'pending_cash')

                                        <span class="badge-status badge-assigned">
                                            Menunggu Pembayaran Cash
                                        </span>

                                    @elseif($subscription->status == 'paid')

                                        <span class="badge-status badge-assigned">
                                            Dibayar
                                        </span>

                                    @elseif($subscription->status == 'active')

                                        <span class="badge-status badge-active">
                                            Aktif
                                        </span>

                                    @elseif($subscription->status == 'cancelled')

                                        <span class="badge-status badge-danger">
                                            Dibatalkan
                                        </span>

                                    @else

                                        <span class="badge-status badge-danger">
                                            Expired
                                        </span>

                                    @endif
                                </td>

                                <td>

                                    {{ $subscription->created_at->format('d/m/Y H:i') }}

                                    @if($subscription->status == 'pending_cash')

                                        <br>

                                        <small class="text-danger">
                                            Tenggat:
                                            {{ \Carbon\Carbon::parse($subscription->cash_deadline)->format('d/m/Y H:i') }}
                                        </small>

                                    @endif

                                </td>

                                <td>

    @if($subscription->status == 'pending')

        <form
            action="{{ route('admin.transaksi.verifikasi', $subscription->id) }}"
            method="POST">

            @csrf

            <button class="btn btn-success-custom btn-sm">
                Verifikasi QRIS
            </button>

        </form>

    @elseif($subscription->status == 'pending_cash')

        <form
            action="{{ route('admin.transaksi.verifyCash', $subscription->id) }}"
            method="POST">

            @csrf

            <button class="btn btn-warning btn-sm">
                Verifikasi Cash
            </button>

        </form>

    @elseif($subscription->status == 'active')

        <span class="table-action-text">
            Sudah Aktif
        </span>

    @elseif($subscription->status == 'cancelled')

        <span class="text-danger">
            Dibatalkan
        </span>

    @else

        <span class="table-action-text">
            -
        </span>

    @endif

</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

@endsection