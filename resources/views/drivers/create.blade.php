@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Tambah Sopir</div>
            <div class="page-subtitle">
                Tambahkan data sopir dan informasi kendaraan
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
            Form Tambah Sopir
        </div>

        <form action="/admin/drivers" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">User Sopir</label>

                <select name="user_id" class="form-select" required>
                    <option value="">-- Pilih Sopir --</option>

                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kendaraan</label>

                <input type="text" name="vehicle_type" class="form-control" placeholder="Contoh: Mobil" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Plat Nomor</label>

                <input type="text" name="plate_number" class="form-control" placeholder="Contoh: B 1234 ABC" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>

                <select name="status" class="form-select" required>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                </select>
            </div>

            <div class="form-action-row">
                <button class="btn btn-primary-custom">
                    <i class="bi bi-save"></i>
                    Simpan
                </button>

                <a href="/admin/drivers" class="btn btn-secondary-custom">
                    Kembali
                </a>
            </div>
        </form>

    </div>

@endsection