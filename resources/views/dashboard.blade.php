@extends('layouts.app')

@section('content')

    @php
        $kids = auth()->user()->kids;
        $subscriptions = auth()->user()->subscriptions;
        $activeSubscriptions = $subscriptions->where('status', 'active')->count();
    @endphp

    <div class="section-header">
        <div>
            <div class="page-title">Dashboard</div>
            <div class="page-subtitle">
                Selamat datang di sistem antar jemput anak sekolah
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

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
                        <h2 class="stat-card-value text-success">{{ $activeSubscriptions }}</h2>
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
                        <div class="stat-card-label">Status Layanan</div>
                        <h5 class="fw-bold text-success mb-0">Online</h5>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-wifi"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-5">
            <div class="page-card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Data Anak</h5>

                    @forelse($kids as $kid)
                        <div class="info-list-item d-flex align-items-center">

                            @if($kid->photo)
                                <img src="{{ asset('storage/' . $kid->photo) }}" width="54" height="54" class="rounded-circle me-3"
                                    style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                    style="width:54px; height:54px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif

                            <div>
                                <div class="fw-bold">{{ $kid->name }}</div>
                                <small class="text-muted">{{ $kid->school_name }}</small>
                            </div>

                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            Belum ada data anak
                        </div>
                    @endforelse

                </div>
            </div>
        </div>


    </div>

    <script>
        var map = L.map('map').setView([-7.250445, 112.768845], 11);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        @foreach($kids as $kid)
            @if($kid->latitude && $kid->longitude)
                L.marker([
                            {{ $kid->latitude }},
                    {{ $kid->longitude }}
                ])
                    .addTo(map)
                    .bindPopup(`
                            <b>{{ $kid->name }}</b><br>
                            {{ $kid->school_name }}
                        `);
            @endif
        @endforeach
    </script>

@endsection