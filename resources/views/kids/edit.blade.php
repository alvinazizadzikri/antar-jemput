@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Edit Data Anak</div>
            <div class="page-subtitle">
                Perbarui data anak, lokasi rumah, dan titik antar jemput
            </div>
        </div>
    </div>

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

    <div class="form-card">

        <div class="form-section-title">
            Form Edit Anak
        </div>

        <form method="POST" action="{{ route('kids.update', $kid->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Anak</label>

                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $kid->name) }}" placeholder="Masukkan nama anak" required>

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Sekolah</label>

                <input type="text" name="school_name" class="form-control @error('school_name') is-invalid @enderror"
                    value="{{ old('school_name', $kid->school_name) }}" placeholder="Masukkan nama sekolah" required>

                @error('school_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Rumah</label>

                <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                    placeholder="Masukkan alamat lengkap" required>{{ old('address', $kid->address) }}</textarea>

                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Lokasi Anak</label>

                    <button type="button" class="btn btn-primary-custom btn-sm" onclick="getCurrentLocation()">
                        <i class="bi bi-geo-alt-fill"></i>
                        Gunakan Lokasi Saya
                    </button>
                </div>

                <div id="map" class="map-form-box"></div>

                <small class="form-helper-text">
                    Klik map untuk mengganti lokasi anak.
                </small>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>

                    <input type="text" name="latitude" id="latitude" readonly class="form-control"
                        value="{{ old('latitude', $kid->latitude) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Longitude</label>

                    <input type="text" name="longitude" id="longitude" readonly class="form-control"
                        value="{{ old('longitude', $kid->longitude) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Titik Jemput</label>

                <input type="text" name="pickup_point" class="form-control"
                    value="{{ old('pickup_point', $kid->pickup_point) }}" placeholder="Contoh: Depan rumah">
            </div>

            <div class="mb-3">
                <label class="form-label">Titik Antar</label>

                <input type="text" name="dropoff_point" class="form-control"
                    value="{{ old('dropoff_point', $kid->dropoff_point) }}" placeholder="Contoh: Gerbang sekolah">
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Anak</label>

                <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">

                <small class="form-helper-text">
                    Kosongkan jika tidak ingin mengganti foto.
                </small>
            </div>

            @if($kid->photo)
                <div class="mb-3">
                    <label class="form-label">Foto Saat Ini</label>

                    <div>
                        <img src="{{ asset('storage/' . $kid->photo) }}" class="image-preview-box">
                    </div>
                </div>
            @endif

            <div class="mb-4 text-center">
                <img id="preview" src="" class="image-preview-box d-none">
            </div>

            <div class="form-action-row">
                <button class="btn btn-primary-custom">
                    <i class="bi bi-save"></i>
                    Update Data
                </button>

                <a href="/kids" class="btn btn-secondary-custom">
                    Kembali
                </a>
            </div>
        </form>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let defaultLat = {{ $kid->latitude ?? -7.250445 }};
            let defaultLng = {{ $kid->longitude ?? 112.768845 }};

            var map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            setTimeout(() => {
                map.invalidateSize();
            }, 300);

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

            if (defaultLat && defaultLng) {
                setMarker(defaultLat, defaultLng);
            }

            map.on('click', function (e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });

            window.getCurrentLocation = function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            setMarker(position.coords.latitude, position.coords.longitude);
                        },
                        function () {
                            alert('Lokasi tidak dapat diakses');
                        }
                    );
                } else {
                    alert('Browser tidak mendukung geolocation');
                }
            };

            window.previewImage = function (event) {
                const image = document.getElementById('preview');
                image.src = URL.createObjectURL(event.target.files[0]);
                image.classList.remove('d-none');
            };
        });
    </script>

@endsection