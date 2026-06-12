@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Edit Paket Langganan</div>
            <div class="page-subtitle">
                Perbarui harga dan durasi paket langganan
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/packages" class="btn btn-secondary-custom">
                Kembali
            </a>
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
            Form Edit Paket
        </div>

        <form action="/admin/packages/{{ $package->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Paket</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $package->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $package->price) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Durasi Hari Sekolah</label>
                <input type="number" name="duration_days" class="form-control"
                    value="{{ old('duration_days', $package->duration_days) }}" required>

                <small class="text-muted">
                    Durasi dihitung berdasarkan hari sekolah. Sabtu, Minggu, dan libur nasional tetap dilewati.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="form-control">{{ old('description', $package->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $package->is_active) == '1' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="0" {{ old('is_active', $package->is_active) == '0' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="form-action-row">
                <button class="btn btn-primary-custom">
                    <i class="bi bi-save"></i>
                    Update
                </button>

                <a href="/admin/packages" class="btn btn-secondary-custom">
                    Kembali
                </a>
            </div>
        </form>
    </div>

@endsection