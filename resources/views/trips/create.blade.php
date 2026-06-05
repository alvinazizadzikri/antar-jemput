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
            Form Penugasan Sopir
        </div>

        <form action="/admin/trips" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Sopir</label>

                <select name="driver_id" class="form-select" required>
                    <option value="">-- Pilih Sopir --</option>

                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">
                            {{ $driver->user->name }}
                            -
                            {{ $driver->vehicle_type ?? '-' }}
                            -
                            {{ $driver->plate_number ?? '-' }}
                            | Kapasitas: {{ $driver->capacity }}
                            | Terisi: {{ $driver->active_passengers_count }}
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
                            <div class="form-check mb-2">
                                <input type="checkbox" name="kid_ids[]" value="{{ $kid->id }}" class="form-check-input"
                                    id="kid_{{ $kid->id }}" {{ in_array($kid->id, old('kid_ids', [])) ? 'checked' : '' }}>

                                <label class="form-check-label" for="kid_{{ $kid->id }}">
                                    <strong>{{ $kid->name }}</strong>
                                    -
                                    {{ $kid->parent->name ?? '-' }}
                                    -
                                    {{ $kid->school_name }}
                                </label>
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

            <div class="mb-4">
                <label class="form-label">Jam Jemput</label>

                <input type="time" name="pickup_time" class="form-control" value="{{ old('pickup_time') }}" required>
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