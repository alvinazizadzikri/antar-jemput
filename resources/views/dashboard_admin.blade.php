@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Dashboard Admin</div>
            <div class="page-subtitle">
                Ringkasan data sistem antar jemput anak sekolah
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Total Anak</div>
                        <h2 class="stat-card-value">{{ $totalKids ?? 0 }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Total Sopir</div>
                        <h2 class="stat-card-value text-success">{{ $totalDrivers ?? 0 }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Langganan Aktif</div>
                        <h2 class="stat-card-value text-warning">{{ $totalSubscriptions ?? 0 }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-credit-card-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Total Perjalanan</div>
                        <h2 class="stat-card-value text-danger">{{ $totalTrips ?? 0 }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection