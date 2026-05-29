@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Tambah Data Anak</h4>
            </div>

            <div class="card-body">

                {{-- ERROR VALIDATION --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('kids.store') }}" enctype="multipart/form-data">

                    @csrf

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Nama Anak
                        </label>

                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Masukkan nama anak"required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- SEKOLAH --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Nama Sekolah
                        </label>

                        <input type="text" name="school_name"
                            class="form-control @error('school_name') is-invalid @enderror" value="{{ old('school_name') }}"
                            placeholder="Masukkan nama sekolah">

                        @error('school_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ALAMAT --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Alamat Rumah
                        </label>

                        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- MAP --}}
                    <div class="mb-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <label class="form-label fw-bold mb-0">
                                Pilih Lokasi di Map
                            </label>

                            <button type="button" class="btn btn-sm btn-primary" onclick="getCurrentLocation()">

                                📍 Gunakan Lokasi Saya

                            </button>

                        </div>

                        <div id="map" class="rounded overflow-hidden border"></div>

                        <style>
                            #map {
                                height: 400px;
                                width: 100%;
                                z-index: 1;
                            }
                        </style>

                        <small class="text-muted">
                            Klik map untuk menentukan lokasi rumah anak.
                        </small>

                    </div>

                    {{-- LAT LNG --}}
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Latitude
                            </label>

                            <input type="text" name="latitude" id="latitude" readonly class="form-control"
                                value="{{ old('latitude') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Longitude
                            </label>

                            <input type="text" name="longitude" id="longitude" readonly class="form-control"
                                value="{{ old('longitude') }}">
                        </div>

                    </div>

                    {{-- TITIK JEMPUT --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Titik Jemput
                        </label>

                        <input type="text" name="pickup_point" class="form-control" value="{{ old('pickup_point') }}"
                            placeholder="Contoh: Depan rumah">
                    </div>

                    {{-- TITIK ANTAR --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Titik Antar
                        </label>

                        <input type="text" name="dropoff_point" class="form-control" value="{{ old('dropoff_point') }}"
                            placeholder="Contoh: Gerbang sekolah">
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Foto Anak
                        </label>

                        <input type="file" name="photo" class="form-control" accept="image/*"
                            onchange="previewImage(event)">

                        <small class="text-muted">
                            Format: JPG, PNG, JPEG
                        </small>

                    </div>

                    {{-- PREVIEW FOTO --}}
                    <div class="mb-3 text-center">

                        <img id="preview" src="" class="img-thumbnail d-none" width="200">

                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex gap-2">

                        <button class="btn btn-success">
                            💾 Simpan Data
                        </button>

                        <a href="/kids" class="btn btn-secondary">
                            ← Kembali
                        </a>

                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- LEAFLET --}}
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            // DEFAULT LOCATION
            let defaultLat = -7.250445;
            let defaultLng = 112.768845;

            // INIT MAP
            var map = L.map('map').setView([defaultLat, defaultLng], 13);

            // TILE
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // FIX BLANK MAP
            setTimeout(() => {
                map.invalidateSize();
            }, 300);

            // MARKER
            var marker;

            function setMarker(lat, lng) {

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup("Lokasi dipilih")
                    .openPopup();

                map.setView([lat, lng], 15);
            }

            // CLICK MAP
            map.on('click', function (e) {

                setMarker(
                    e.latlng.lat,
                    e.latlng.lng
                );

            });

            // AUTO LOCATION
            window.getCurrentLocation = function () {

                if (navigator.geolocation) {

                    navigator.geolocation.getCurrentPosition(

                        function (position) {

                            let lat = position.coords.latitude;
                            let lng = position.coords.longitude;

                            setMarker(lat, lng);

                        },

                        function () {

                            alert('Lokasi tidak dapat diakses');

                        }

                    );

                } else {

                    alert('Browser tidak mendukung geolocation');

                }

            };

            // PREVIEW FOTO
            window.previewImage = function (event) {

                const image = document.getElementById('preview');

                image.src = URL.createObjectURL(event.target.files[0]);

                image.classList.remove('d-none');

            };

        });

    </script>

@endsection