@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Edit Sopir</div>
            <div class="page-subtitle">
                Perbarui akun login sopir, nomor telepon, kendaraan, kapasitas, dan status
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/drivers" class="btn btn-secondary-custom">
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
            Form Edit Sopir
        </div>

        <form action="/admin/drivers/{{ $driver->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Sopir</label>

                <input type="text" name="name" class="form-control" value="{{ old('name', $driver->user->name ?? '') }}"
                    placeholder="Masukkan nama lengkap sopir" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Login</label>

                <input type="email" name="email" class="form-control" value="{{ old('email', $driver->user->email ?? '') }}"
                    placeholder="Contoh: sopir@gmail.com" required>

                <small class="text-muted">
                    Email ini digunakan sopir untuk login ke sistem.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>

                <input type="password" name="password" class="form-control"
                    placeholder="Kosongkan jika tidak ingin mengganti password">

                <small class="text-muted">
                    Isi hanya jika ingin mengganti password login sopir.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon Sopir</label>

                <input type="text" name="phone_number" class="form-control"
                    value="{{ old('phone_number', $driver->phone_number) }}" placeholder="Contoh: 081234567890" required>

                <small class="text-muted">
                    Nomor ini dapat dilihat orang tua untuk kebutuhan komunikasi antar jemput.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kendaraan</label>

                <input type="text" class="form-control" value="Mobil" readonly>

                <small class="text-muted">
                    Jenis kendaraan ditetapkan otomatis sebagai mobil.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Plat Nomor</label>

                <input type="text" name="plate_number" class="form-control"
                    value="{{ old('plate_number', $driver->plate_number) }}" placeholder="Contoh: AE 1234 YY" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kapasitas Anak</label>

                <input type="number" name="capacity" class="form-control"
                    value="{{ old('capacity', $driver->capacity ?? 4) }}" min="1" max="20" required>

                <small class="text-muted">
                    Kapasitas digunakan untuk membatasi jumlah anak dalam satu penugasan sopir.
                </small>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>

                <select name="status" class="form-select" required>
                    <option value="online" {{ old('status', $driver->status) == 'online' ? 'selected' : '' }}>
                        Online
                    </option>

                    <option value="offline" {{ old('status', $driver->status) == 'offline' ? 'selected' : '' }}>
                        Offline
                    </option>
                </select>
            </div>

            <div class="form-action-row">
                <button class="btn btn-primary-custom">
                    <i class="bi bi-save"></i>
                    Simpan Perubahan
                </button>

                <a href="/admin/drivers" class="btn btn-secondary-custom">
                    Kembali
                </a>
            </div>
        </form>

    </div>

@endsection