@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Penugasan Sopir</div>
            <div class="page-subtitle">
                Pilih satu sopir dan beberapa anak sesuai kapasitas kendaraan
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="form-card">

        <div class="form-section-title">
            Kapasitas Sopir
        </div>

        <div class="capacity-summary-grid mb-4">
            @forelse($drivers as $driver)
                <div class="capacity-summary-card">
                    <div class="fw-bold">
                        {{ $driver->user->name ?? '-' }}
                    </div>

                    <small class="text-muted d-block mb-2">
                        {{ $driver->vehicle_type ?? '-' }} | {{ $driver->plate_number ?? '-' }}
                    </small>

                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge-status badge-assigned">
                            Kapasitas: {{ $driver->capacity }}
                        </span>

                        <span class="badge-status badge-pending">
                            Terisi: {{ $driver->active_passengers_count }}
                        </span>

                        <span class="badge-status badge-active">
                            Sisa: {{ $driver->remaining_capacity }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-muted">
                    Belum ada sopir online.
                </div>
            @endforelse
        </div>

        <div class="form-section-title">
            Form Penugasan Sopir
        </div>

        <form action="/admin/trips" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Sopir</label>

                <select name="driver_id" class="form-select" required>
                    <option value="">-- Pilih Sopir --</option>

                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->user->name }}
                            -
                            {{ $driver->vehicle_type ?? '-' }}
                            -
                            {{ $driver->plate_number ?? '-' }}
                            | Sisa Kapasitas: {{ $driver->remaining_capacity }}
                        </option>
                    @endforeach
                </select>

                <small class="text-muted">
                    Hanya sopir online yang muncul pada pilihan ini.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Anak</label>

                <div class="page-card border">
                    <div class="card-body">

                        @forelse($kids as $kid)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <div class="form-check">
                                    <input type="checkbox" name="kid_ids[]" value="{{ $kid->id }}" class="form-check-input"
                                        id="kid_{{ $kid->id }}" {{ in_array($kid->id, old('kid_ids', [])) ? 'checked' : '' }}>

                                    <label class="form-check-label" for="kid_{{ $kid->id }}">
                                        <strong>{{ $kid->name }}</strong>
                                        -
                                        {{ $kid->parent->name ?? '-' }}
                                        -
                                        {{ $kid->school_name }}

                                        <br>

                                        <small class="text-muted">
                                            Alamat: {{ $kid->address ?? '-' }}
                                        </small>
                                    </label>
                                </div>

                                <div class="text-end">
                                    @if(!is_null($kid->latitude) && !is_null($kid->longitude))
                                        <a href="https://www.google.com/maps?q={{ $kid->latitude }},{{ $kid->longitude }}"
                                            target="_blank" class="btn btn-primary-custom btn-sm">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            Lihat Lokasi
                                        </a>
                                    @else
                                        <span class="badge-status badge-danger">
                                            Lokasi belum ada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-muted text-center py-3">
                                Tidak ada anak yang bisa ditugaskan.
                                Pastikan anak sudah memiliki langganan aktif dan belum dalam perjalanan aktif.
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Tanggal Jemput
                </label>

                <input type="date" name="trip_date" class="form-control"
                    value="{{ old('trip_date', date('Y-m-d', strtotime('+1 day'))) }}" required>

                <small class="text-muted">
                    Pilih tanggal perjalanan yang akan dilakukan.
                    Admin dapat membuat jadwal untuk hari berikutnya sebelum layanan dimulai.
                </small>
            </div>


            <div class="mb-4">
                <label class="form-label">
                    Jam Rencana Jemput
                </label>

                <input type="time" name="pickup_time" class="form-control" value="{{ old('pickup_time') }}" required>

                <small class="text-muted">
                    Jam ini merupakan jadwal rencana jemput dari admin.
                    Waktu aktual akan tercatat ketika sopir memperbarui status perjalanan.
                </small>
            </div>

            <div class="form-action-row">
                <button class="btn btn-primary-custom">
                    <i class="bi bi-save"></i>
                    Simpan Penugasan
                </button>

                <a href="/admin/trips" class="btn btn-secondary-custom">
                    Kembali
                </a>
            </div>
        </form>

    </div>

@endsection