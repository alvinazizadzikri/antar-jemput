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

                            @php
                                $effectiveStatus = $subscription->effective_status;
                            @endphp

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
                                    <span class="badge-status {{ $subscription->status_badge_class }}">
                                        {{ $subscription->status_label }}
                                    </span>
                                </td>

                                <td>
                                    {{ $subscription->created_at->format('d/m/Y H:i') }}

                                    @if($effectiveStatus === 'pending_cash' && $subscription->cash_deadline)
                                        <br>
                                        <small class="text-danger">
                                            Tenggat:
                                            {{ \Carbon\Carbon::parse($subscription->cash_deadline)->format('d/m/Y H:i') }}
                                        </small>
                                    @endif

                                    @if($subscription->start_date && $subscription->end_date)
                                        <br>
                                        <small class="text-muted">
                                            Masa berlaku:
                                            {{ \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    @if($effectiveStatus === 'pending')
                                        <form action="{{ route('admin.transaksi.verifikasi', $subscription->id) }}" method="POST">
                                            @csrf

                                            <button class="btn btn-success-custom btn-sm">
                                                Verifikasi QRIS
                                            </button>
                                        </form>

                                    @elseif($effectiveStatus === 'pending_cash')
                                        <form action="{{ route('admin.transaksi.verifyCash', $subscription->id) }}" method="POST">
                                            @csrf

                                            <button class="btn btn-warning-custom btn-sm">
                                                Verifikasi Cash
                                            </button>
                                        </form>

                                    @elseif($effectiveStatus === 'active')
                                        <span class="table-action-text">
                                            Sudah Aktif
                                        </span>

                                    @elseif($effectiveStatus === 'expired')
                                        <span class="text-danger fw-bold">
                                            Berakhir
                                        </span>

                                    @elseif($effectiveStatus === 'cancelled')
                                        <span class="text-danger fw-bold">
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