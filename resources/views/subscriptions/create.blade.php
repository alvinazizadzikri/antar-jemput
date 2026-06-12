@extends('layouts.app')

@section('content')

    @include('components.subscription-info')

    <div class="section-header">
        <div>
            <div class="page-title">Pilih Paket Langganan</div>
            <div class="page-subtitle">
                Pilih satu atau beberapa anak dalam satu pembayaran
            </div>
        </div>

        <div class="header-actions">
            <a href="/subscriptions" class="btn btn-secondary-custom">
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

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-card">
        <div class="card-body">

            <div class="info-alert-custom">
                Pilih anak yang akan didaftarkan langganan antar jemput. Anak yang sudah memiliki langganan aktif atau
                sedang menunggu pembayaran tidak akan muncul di pilihan. Jika memilih lebih dari satu anak, sistem akan
                membuat satu tagihan pembayaran gabungan.
            </div>

            @if($kids->count() > 0)

                <form method="POST" action="{{ route('subscriptions.store') }}" id="subscriptionForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Pilih Anak</label>

                        <div class="page-card border">
                            <div class="card-body">

                                @foreach($kids as $kid)
                                    @php
                                        $oldKidIds = old('kid_ids', []);
                                        $isChecked = in_array($kid->id, $oldKidIds) || request('kid_id') == $kid->id;
                                    @endphp

                                    <div class="form-check mb-2">
                                        <input type="checkbox"
                                            name="kid_ids[]"
                                            value="{{ $kid->id }}"
                                            class="form-check-input child-checkbox"
                                            id="kid_{{ $kid->id }}"
                                            {{ $isChecked ? 'checked' : '' }}>

                                        <label class="form-check-label" for="kid_{{ $kid->id }}">
                                            <strong>{{ $kid->name }}</strong>
                                            -
                                            {{ $kid->school_name }}
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <small class="text-muted">
                            Bisa memilih lebih dari satu anak untuk satu pembayaran.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Metode Pembayaran
                        </label>

                        <select name="payment_method" class="form-select" required>
                            <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>
                                QRIS
                            </option>

                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>
                                Cash
                            </option>
                        </select>

                        <small class="text-muted">
                            QRIS akan langsung diproses setelah pembayaran berhasil. Pembayaran Cash harus diverifikasi
                            oleh admin atau sopir.
                        </small>
                    </div>

                    <div class="mb-4">
    <label class="form-label">
        Paket Langganan
    </label>

    <div class="row g-4">

        @forelse($packages as $package)
            <div class="col-md-4">
                <label class="package-card w-100"
                    for="package_{{ $package->id }}"
                    style="cursor: pointer;">

                    <div class="d-flex align-items-center gap-2 mb-2">
                        <input type="radio"
                            name="package_id"
                            id="package_{{ $package->id }}"
                            value="{{ $package->id }}"
                            data-name="{{ $package->name }}"
                            data-price="{{ $package->price }}"
                            class="form-check-input package-radio"
                            {{ old('package_id') == $package->id ? 'checked' : '' }}
                            required>

                        <div class="package-title mb-0">
                            {{ $package->name }}
                        </div>
                    </div>

                    <div class="package-price">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                    </div>

                    <div class="package-description mb-0">
                        {{ $package->description ?? 'Berlaku untuk '.$package->duration_days.' hari sekolah.' }}
                    </div>

                    <small class="text-muted">
                        Durasi: {{ $package->duration_days }} hari sekolah
                    </small>
                </label>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-danger mb-0">
                    Belum ada paket langganan aktif. Silakan hubungi admin.
                </div>
            </div>
        @endforelse

    </div>
</div>

                    <div class="info-alert-custom">
                        <div class="fw-bold mb-2">
                            Ringkasan Pembayaran
                        </div>

                        <div>
                            Jumlah anak dipilih:
                            <strong id="selectedChildrenCount">0</strong>
                        </div>

                        <div>
                            Paket dipilih:
                            <strong id="selectedPackageText">-</strong>
                        </div>

                        <div>
                            Total pembayaran:
                            <strong id="totalPayment">Rp 0</strong>
                        </div>
                    </div>

                    <div class="form-action-row mt-4">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-credit-card"></i>
                            Buat Tagihan Pembayaran
                        </button>

                        <a href="/subscriptions" class="btn btn-secondary-custom">
                            Kembali
                        </a>
                    </div>

                </form>

            @else

                <div class="text-center text-muted py-5">
                    <i class="bi bi-info-circle fs-1 d-block mb-3"></i>

                    <h5 class="fw-bold">
                        Tidak ada anak yang bisa dibuatkan langganan baru
                    </h5>

                    <p class="mb-4">
                        Semua anak sudah memiliki langganan aktif atau sedang menunggu pembayaran. Selesaikan pembayaran
                        terlebih dahulu, atau tambahkan data anak baru.
                    </p>

                    <a href="/subscriptions" class="btn btn-secondary-custom">
                        Kembali
                    </a>
                </div>

            @endif

        </div>
    </div>

    <script>
    const childCheckboxes = document.querySelectorAll('.child-checkbox');
    const packageRadios = document.querySelectorAll('.package-radio');
    const selectedChildrenCount = document.getElementById('selectedChildrenCount');
    const selectedPackageText = document.getElementById('selectedPackageText');
    const totalPayment = document.getElementById('totalPayment');
    const subscriptionForm = document.getElementById('subscriptionForm');

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    function getSelectedPackage() {
        return document.querySelector('.package-radio:checked');
    }

    function updateTotal() {
        const totalChildren = document.querySelectorAll('.child-checkbox:checked').length;
        const selectedPackage = getSelectedPackage();

        const packageName = selectedPackage ? selectedPackage.dataset.name : '-';
        const packagePrice = selectedPackage ? Number(selectedPackage.dataset.price) : 0;
        const total = totalChildren * packagePrice;

        selectedChildrenCount.textContent = totalChildren;
        selectedPackageText.textContent = packageName;
        totalPayment.textContent = formatRupiah(total);
    }

    childCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateTotal);
    });

    packageRadios.forEach(function (radio) {
        radio.addEventListener('change', updateTotal);
    });

    subscriptionForm?.addEventListener('submit', function (event) {
        const totalChildren = document.querySelectorAll('.child-checkbox:checked').length;
        const selectedPackage = getSelectedPackage();

        if (totalChildren < 1) {
            event.preventDefault();
            alert('Pilih minimal satu anak terlebih dahulu.');
            return;
        }

        if (!selectedPackage) {
            event.preventDefault();
            alert('Pilih paket langganan terlebih dahulu.');
        }
    });

    updateTotal();
</script>

@endsection