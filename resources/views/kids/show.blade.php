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
                    {{ $kid->school_name }} | {{ $kid->address }}
                </p>
            </div>

            <div id="map" class="map-show-box"></div>

        </div>
    </div>

    <script>
        let lat = {{ $kid->latitude ?? -7.250445 }};
        let lng = {{ $kid->longitude ?? 112.768845 }};

        var map = L.map('map').setView([lat, lng], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        L.marker([lat, lng])
            .addTo(map)
            .bindPopup('Lokasi {{ $kid->name }}')
            .openPopup();

        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    </script>

@endsection