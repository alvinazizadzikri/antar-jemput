@extends('layouts.app')

@section('content')

@include('components.subscription-info')

    <div class="section-header">
        <div>
            <div class="page-title">Langganan Saya</div>
            <div class="page-subtitle">
                Status langganan antar jemput berdasarkan data anak
            </div>
        </div>

        <div class="header-actions">
            <a href="{{ route('subscriptions.create') }}" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle"></i>
                Tambah Langganan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Sekolah</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Masa Berlaku</th>
                            <th style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($kids as $kid)

                            @php
                                $subscription = $kid->activeSubscription ?? $kid->latestSubscription;
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $kid->name }}
                                    </div>
                                </td>

                                <td>
                                    {{ $kid->school_name }}
                                </td>

                                <td>
                                    @if($subscription)
                                        <span class="package-badge">
                                            {{ $subscription->package_name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if($subscription)
                                        Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($subscription)
                                        {{ $subscription->payment_method ?? 'QRIS' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if(!$subscription)

    <span class="badge-status badge-neutral">
        Belum Langganan
    </span>

@elseif($subscription->status == 'pending')

    <span class="badge-status badge-pending">
        Menunggu Pembayaran QRIS
    </span>

@elseif($subscription->status == 'pending_cash')

    <span class="badge-status badge-assigned">
        Menunggu Pembayaran Cash
    </span>

    <br>

    <small class="text-danger">
        Tenggat:
        {{ \Carbon\Carbon::parse($subscription->cash_deadline)->format('d M Y H:i') }}
    </small>

@elseif($subscription->status == 'active')

    <span class="badge-status badge-active">
        Aktif
    </span>

@elseif($subscription->status == 'cancelled')

    <span class="badge-status badge-danger">
        Dibatalkan
    </span>

@elseif($subscription->status == 'paid')

    <span class="badge-status badge-assigned">
        Dibayar
    </span>

@else

    <span class="badge-status badge-danger">
        Expired
    </span>

@endif
                                </td>

                                <td>
                                    @if($subscription && $subscription->status == 'active')
                                        <small>
                                            {{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}
                                        </small>
                                    @elseif($subscription && $subscription->status == 'pending')
                                        <small class="text-muted">
                                            Belum aktif
                                        </small>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if(!$subscription || $subscription->status == 'expired')

    <a href="{{ route('subscriptions.create') }}?kid_id={{ $kid->id }}"
        class="btn btn-primary-custom btn-sm">
        Pilih Paket
    </a>

@elseif($subscription->status == 'pending')

    <a href="{{ route('subscriptions.payment', $subscription->id) }}"
        class="btn btn-warning-custom btn-sm">
        Bayar QRIS
    </a>

@elseif($subscription->status == 'pending_cash')

    <a href="{{ route('subscriptions.cash', $subscription->id) }}"
        class="btn btn-warning-custom btn-sm">
        Detail Cash
    </a>

@elseif($subscription->status == 'active')

    <span class="fw-bold text-success">
        Aktif
    </span>

@elseif($subscription->status == 'cancelled')

    <span class="text-danger fw-bold">
        Dibatalkan
    </span>

@else

    <span class="text-muted">
        -
    </span>

@endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Belum ada data anak.
                                    Silakan tambahkan data anak terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection