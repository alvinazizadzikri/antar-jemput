@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Dashboard</div>
            <div class="page-subtitle">
                Ringkasan data anak, langganan, dan akses cepat layanan antar jemput
            </div>
        </div>
    </div>

    {{-- RINGKASAN UTAMA --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Total Anak</div>
                        <h2 class="stat-card-value">{{ $kids->count() }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Langganan Aktif</div>
                        <h2 class="stat-card-value">{{ $activeSubscriptions }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-credit-card-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Belum Langganan</div>
                        <h2 class="stat-card-value">{{ $needSubscriptionCount }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- AKSI CEPAT --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-3">
            <a href="/kids/create" class="text-decoration-none">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Tambah Anak</div>
                            <small class="text-muted">Daftarkan anak</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3">
            <a href="/subscriptions/create" class="text-decoration-none">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="bi bi-bag-plus-fill"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Langganan</div>
                            <small class="text-muted">Pilih paket</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3">
            <a href="/riwayat" class="text-decoration-none">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Riwayat</div>
                            <small class="text-muted">Lihat perjalanan</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3">
            <a href="/izin-anak/create" class="text-decoration-none">
                <div class="stat-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="bi bi-calendar-x-fill"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Izin Anak</div>
                            <small class="text-muted">Ajukan izin</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <div class="row g-4">

        {{-- DATA ANAK RINGKAS --}}
        <div class="col-lg-7">
            <div class="page-card h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Data Anak</h5>
                            <small class="text-muted">
                                Ringkasan anak yang terdaftar di akun Anda
                            </small>
                        </div>

                        <a href="/kids" class="btn btn-secondary-custom btn-sm">
                            Lihat Semua
                        </a>
                    </div>

                    @forelse($latestKids as $kid)

                        @php
                            $hasActiveSubscription = $activeKidIds->contains($kid->id);
                        @endphp

                        <div class="info-list-item d-flex align-items-center justify-content-between gap-3">

                            <div class="d-flex align-items-center min-width-0">

                                @if($kid->photo)
                                    <img src="{{ asset('storage/' . $kid->photo) }}"
                                        width="54"
                                        height="54"
                                        class="rounded-circle me-3"
                                        style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                        style="width:54px; height:54px; min-width:54px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif

                                <div>
                                    <div class="fw-bold">{{ $kid->name }}</div>
                                    <small class="text-muted">{{ $kid->school_name }}</small>
                                </div>

                            </div>

                            <div class="text-end">
                                @if($hasActiveSubscription)
                                    <span class="badge-status badge-active">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge-status badge-danger">
                                        Belum Langganan
                                    </span>
                                @endif
                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted py-4">
                            <div class="mb-3">
                                <i class="bi bi-people" style="font-size: 38px;"></i>
                            </div>

                            <div class="fw-bold mb-1">Belum ada data anak</div>

                            <div class="mb-3">
                                Tambahkan data anak terlebih dahulu sebelum memilih langganan.
                            </div>

                            <a href="/kids/create" class="btn btn-primary-custom">
                                <i class="bi bi-person-plus-fill"></i>
                                Tambah Anak
                            </a>
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

        {{-- RINGKASAN LANGGANAN --}}
        <div class="col-lg-5">
            <div class="page-card h-100">
                <div class="card-body">

                    <div class="mb-3">
                        <h5 class="fw-bold mb-1">Ringkasan Langganan</h5>
                        <small class="text-muted">
                            Informasi status layanan anak hari ini
                        </small>
                    </div>

                    <div class="info-list-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">Status Layanan</div>
                                <small class="text-muted">
                                    Berdasarkan langganan aktif hari ini
                                </small>
                            </div>

                            @if($activeSubscriptions > 0)
                                <span class="badge-status badge-active">
                                    Berjalan
                                </span>
                            @else
                                <span class="badge-status badge-danger">
                                    Belum Aktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="info-list-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">Anak Terdaftar</div>
                                <small class="text-muted">
                                    Jumlah anak pada akun ini
                                </small>
                            </div>

                            <span class="package-badge">
                                {{ $kids->count() }} Anak
                            </span>
                        </div>
                    </div>

                    <div class="info-list-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">Perlu Tindakan</div>
                                <small class="text-muted">
                                    Anak yang belum memiliki langganan aktif
                                </small>
                            </div>

                            @if($needSubscriptionCount > 0)
                                <span class="badge-status badge-pending">
                                    {{ $needSubscriptionCount }} Anak
                                </span>
                            @else
                                <span class="badge-status badge-active">
                                    Aman
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($needSubscriptionCount > 0)
                        <div class="alert alert-info mt-3 mb-0">
                            <strong>Catatan:</strong>
                            Masih ada anak yang belum memiliki langganan aktif.
                            Silakan pilih paket langganan agar layanan antar jemput dapat digunakan.
                        </div>
                    @else
                        <div class="alert alert-success mt-3 mb-0">
                            Semua anak yang terdata sudah memiliki langganan aktif atau tidak memerlukan tindakan saat ini.
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

@endsection