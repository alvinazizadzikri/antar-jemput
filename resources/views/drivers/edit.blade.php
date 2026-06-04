@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Edit Sopir</div>
            <div class="page-subtitle">
                Perbarui data kendaraan dan status sopir
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
            Form Edit Sopir
        </div>

        <form action="/admin/drivers/{{ $driver->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Jenis Kendaraan</label>

                <input type="text" name="vehicle_type" class="form-control"
                    value="{{ old('vehicle_type', $driver->vehicle_type) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Plat Nomor</label>

                <input type="text" name="plate_number" class="form-control"
                    value="{{ old('plate_number', $driver->plate_number) }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>

                <select name="status" class="form-select" required>
                    <option value="online" {{ $driver->status == 'online' ? 'selected' : '' }}>
                        Online
                    </option>

                    <option value="offline" {{ $driver->status == 'offline' ? 'selected' : '' }}>
                        Offline
                    </option>
                </select>
            </div>

            <div class="form-action-row">
                <button class="btn btn-primary-custom">
                    <i class="bi bi-save"></i>
                    Update
                </button>

                <a href="/admin/drivers" class="btn btn-secondary-custom">
                    Kembali
                </a>
            </div>
        </form>

    </div>

@endsection