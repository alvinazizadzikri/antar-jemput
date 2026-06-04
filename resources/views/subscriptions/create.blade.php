@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Pilih Paket Langganan</div>
            <div class="page-subtitle">
                Pilih anak dan paket layanan antar jemput sekolah
            </div>
        </div>

        <div class="header-actions">
            <a href="/subscriptions" class="btn btn-secondary-custom">
                Kembali
            </a>
        </div>
    </div>

    <div class="page-card">
        <div class="card-body">

            <div class="info-alert-custom">
                Pilih anak yang akan didaftarkan langganan antar jemput.
                Anak yang sudah memiliki langganan aktif atau pending tidak akan muncul di pilihan.
            </div>

            @if($kids->count() > 0)

                <form method="POST" action="{{ route('subscriptions.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Anak</label>

                        <select name="kid_id" class="form-select" required>
                            @foreach($kids as $kid)
                                <option value="{{ $kid->id }}" {{ request('kid_id') == $kid->id ? 'selected' : '' }}>
                                    {{ $kid->name }} - {{ $kid->school_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-4">

                        <div class="col-md-4">
                            <div class="package-card">
                                <div class="package-title">Harian</div>

                                <div class="package-price">
                                    Rp 50.000
                                </div>

                                <div class="package-description">
                                    Berlaku untuk 1 hari sekolah.
                                </div>

                                <button name="package_name" value="Harian" class="btn btn-primary-custom w-100">
                                    Pilih & Bayar
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="package-card">
                                <div class="package-title">Mingguan</div>

                                <div class="package-price">
                                    Rp 250.000
                                </div>

                                <div class="package-description">
                                    Berlaku untuk 5 hari sekolah.
                                </div>

                                <button name="package_name" value="Mingguan" class="btn btn-success-custom w-100">
                                    Pilih & Bayar
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="package-card">
                                <div class="package-title">Bulanan</div>

                                <div class="package-price">
                                    Rp 800.000
                                </div>

                                <div class="package-description">
                                    Berlaku untuk 20 hari sekolah.
                                </div>

                                <button name="package_name" value="Bulanan" class="btn btn-warning-custom w-100">
                                    Pilih & Bayar
                                </button>
                            </div>
                        </div>

                    </div>

                </form>

            @else

                <div class="text-center text-muted py-5">
                    <i class="bi bi-info-circle fs-1 d-block mb-3"></i>

                    <h5 class="fw-bold text-dark">
                        Tidak ada anak yang bisa dibuatkan langganan baru
                    </h5>

                    <p class="mb-4">
                        Semua anak sudah memiliki langganan aktif atau sedang menunggu pembayaran.
                        Selesaikan pembayaran terlebih dahulu, atau tambahkan data anak baru.
                    </p>

                    <a href="/subscriptions" class="btn btn-secondary-custom">
                        Kembali
                    </a>
                </div>

            @endif

        </div>
    </div>

@endsection