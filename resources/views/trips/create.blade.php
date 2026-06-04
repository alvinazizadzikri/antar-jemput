@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Penugasan Sopir</div>
            <div class="page-subtitle">
                Pilih anak, sopir, jam jemput, dan status awal perjalanan
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
                <label class="form-label">Anak</label>

                <select name="kid_id" class="form-select" required>
                    <option value="">-- Pilih Anak --</option>

                    @foreach($kids as $kid)
                        <option value="{{ $kid->id }}">
                            {{ $kid->name }}
                            -
                            {{ $kid->parent->name ?? '-' }}
                            -
                            {{ $kid->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>

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
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jam Jemput</label>

                <input type="time" name="pickup_time" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Status Awal</label>

                <select name="status" class="form-select" required>
                    <option value="assigned">
                        Ditugaskan
                    </option>

                    <option value="on_pickup">
                        Menuju Jemput
                    </option>

                    <option value="picked">
                        Dijemput
                    </option>

                    <option value="on_delivery">
                        Diantar
                    </option>

                    <option value="completed">
                        Selesai
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">
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