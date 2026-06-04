@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold">Dashboard Admin</h3>
    <p class="text-muted">Ringkasan data sistem antar jemput anak sekolah</p>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p>Total Anak</p>
                    <h2 class="fw-bold text-primary">{{ $totalKids ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p>Total Driver</p>
                    <h2 class="fw-bold text-success">{{ $totalDrivers ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p>Langganan Aktif</p>
                    <h2 class="fw-bold text-warning">{{ $totalSubscriptions ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p>Total Perjalanan</p>
                    <h2 class="fw-bold text-danger">{{ $totalTrips ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection