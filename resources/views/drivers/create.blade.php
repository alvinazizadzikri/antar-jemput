@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Tambah Sopir</div>
            <div class="page-subtitle">
                Buat akun login sopir dan tambahkan informasi kendaraan
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
                <label class="form-label">Nama Sopir</label>

                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap sopir" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Login</label>

                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                    placeholder="Contoh: sopir@gmail.com" required>

                <small class="text-muted">
                    Email ini akan digunakan sopir untuk login ke sistem.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Login</label>

                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>

                <small class="text-muted">
                    Password ini akan digunakan sopir untuk login pertama kali.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon Sopir</label>

                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}"
                    placeholder="Contoh: 081234567890" required>

                <small class="text-muted">
                    Nomor ini dapat dilihat orang tua untuk kebutuhan komunikasi terkait antar jemput.
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

                <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number') }}"
                    placeholder="Contoh: AE 1234 YY" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kapasitas Anak</label>

                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 4) }}" min="1" max="20"
                    required>

                <small class="text-muted">
                    Kapasitas digunakan untuk membatasi jumlah anak dalam satu penugasan sopir.
                </small>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>

                <select name="status" class="form-select" required>
                    <option value="online" {{ old('status') == 'online' ? 'selected' : '' }}>
                        Online
                    </option>

                    <option value="offline" {{ old('status') == 'offline' ? 'selected' : '' }}>
                        Offline
                    </option>
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