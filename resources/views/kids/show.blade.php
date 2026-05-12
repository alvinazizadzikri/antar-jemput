@extends('layouts.app')

@section('content')

    <h3>Lokasi Anak</h3>

    <div class="card">
        <div class="card-body">

            <h5>{{ $kid->name }}</h5>

            <div id="map" style="height:500px;"></div>

        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>

        var map = L.map('map').setView([
        {{ $kid->latitude }},
            {{ $kid->longitude }}
        ], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        L.marker([
        {{ $kid->latitude }},
            {{ $kid->longitude }}
        ]).addTo(map)
            .bindPopup('Lokasi {{ $kid->name }}')
            .openPopup();

    </script>

@endsection