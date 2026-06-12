@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Lokasi Anak</div>
            <div class="page-subtitle">
                Detail lokasi rumah dan titik antar jemput anak
            </div>
        </div>

        <div class="header-actions">
            <a href="/kids" class="btn btn-secondary-custom">
                Kembali
            </a>
        </div>
    </div>

    <div class="page-card">
        <div class="card-body">

            <div class="mb-4">
                <h5 class="fw-bold mb-1">
                    {{ $kid->name }}
                </h5>

                <p class="text-muted mb-0">
                    {{ $kid->school_name ?? '-' }} | {{ $kid->address ?? '-' }}
                </p>
            </div>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <div class="info-list-item h-100">
                        <div class="text-muted mb-1">
                            Titik Jemput
                        </div>

                        <div class="fw-bold">
                            {{ $kid->pickup_point ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-list-item h-100">
                        <div class="text-muted mb-1">
                            Titik Antar
                        </div>

                        <div class="fw-bold">
                            {{ $kid->dropoff_point ?? '-' }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="info-list-item">

                <h5 class="fw-bold mb-3">
                    Lokasi Google Maps
                </h5>

                @if(!is_null($kid->latitude) && !is_null($kid->longitude))

                    <div class="mb-3">
                        <div class="text-muted">
                            Koordinat Lokasi
                        </div>

                        <div class="fw-bold">
                            {{ $kid->latitude }}, {{ $kid->longitude }}
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="https://www.google.com/maps?q={{ $kid->latitude }},{{ $kid->longitude }}" target="_blank"
                            rel="noopener" class="btn btn-primary-custom">
                            <i class="bi bi-geo-alt-fill"></i>
                            Buka Google Maps
                        </a>

                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $kid->latitude }},{{ $kid->longitude }}"
                            target="_blank" rel="noopener" class="btn btn-success-custom">
                            <i class="bi bi-signpost-2-fill"></i>
                            Arahkan ke Lokasi
                        </a>
                    </div>

                @else

                    <div class="alert alert-danger mb-0">
                        Lokasi anak belum tersedia. Silakan edit data anak dan tentukan titik lokasi terlebih dahulu.
                    </div>

                @endif

            </div>

        </div>
    </div>

@endsection