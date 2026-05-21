@extends('layouts.app')

@section('content')

    @php

        $kids = auth()->user()->kids;

        $subscriptions = auth()->user()->subscriptions;

        $activeSubscriptions = $subscriptions->where('status', 'active')->count();

    @endphp

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="mb-4">

            <h3 class="fw-bold">
                Dashboard
            </h3>

            <p class="text-muted">
                Selamat datang di sistem antar jemput anak sekolah
            </p>

        </div>

        {{-- CARD STATISTIK --}}
        <div class="row g-4 mb-4">

            {{-- TOTAL ANAK --}}
            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Total Anak
                                </p>

                                <h2 class="fw-bold text-primary">
                                    {{ $kids->count() }}
                                </h2>

                            </div>

                            <div class="fs-1 text-primary">
                                <i class="bi bi-people-fill"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- LANGGANAN AKTIF --}}
            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Langganan Aktif
                                </p>

                                <h2 class="fw-bold text-success">
                                    {{ $activeSubscriptions }}
                                </h2>

                            </div>

                            <div class="fs-1 text-success">
                                <i class="bi bi-credit-card-fill"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- STATUS --}}
            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Status Layanan
                                </p>

                                <h5 class="fw-bold text-success">

                                    Online

                                </h5>

                            </div>

                            <div class="fs-1 text-success">
                                <i class="bi bi-wifi"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ROW --}}
        <div class="row g-4">

            {{-- DATA ANAK --}}
            <div class="col-lg-5">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white border-0">

                        <h5 class="mb-0 fw-bold">
                            Data Anak
                        </h5>

                    </div>

                    <div class="card-body">

                        @forelse($kids as $kid)

                            <div class="d-flex align-items-center mb-3 p-2 border rounded">

                                {{-- FOTO --}}
                                @if($kid->photo)

                                    <img src="{{ asset('storage/' . $kid->photo) }}" width="60" height="60"
                                        class="rounded-circle me-3" style="object-fit:cover;">

                                @else

                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                        style="width:60px;height:60px;">

                                        <i class="bi bi-person-fill"></i>

                                    </div>

                                @endif

                                {{-- INFO --}}
                                <div>

                                    <div class="fw-bold">
                                        {{ $kid->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $kid->school_name }}
                                    </small>

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

            {{-- MAP --}}
            <div class="col-lg-7">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white border-0">

                        <h5 class="mb-0 fw-bold">
                            Lokasi Anak
                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="map" style="height:400px;border-radius:15px;">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- LEAFLET --}}
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