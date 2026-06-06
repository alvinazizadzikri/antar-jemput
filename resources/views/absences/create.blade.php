@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Ajukan Izin Anak</div>
            <div class="page-subtitle">
                Izin hanya dapat diajukan sebelum anak masuk penugasan trip
            </div>
        </div>

        <div class="header-actions">
            <a href="/izin-anak" class="btn btn-secondary-custom">
                Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
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

    <div class="form-card">

        <div class="form-section-title">
            Form Izin Anak
        </div>

        <div class="info-alert-custom">
            Izin hanya berlaku jika anak belum masuk trip pada tanggal tersebut.
            Jika izin disetujui, langganan pada hari itu tetap dianggap terpakai.
        </div>

        <form action="/izin-anak" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Anak</label>

                <select name="kid_id" class="form-select" required>
                    <option value="">-- Pilih Anak --</option>

                    @foreach($kids as $kid)
                        <option value="{{ $kid->id }}" {{ old('kid_id') == $kid->id ? 'selected' : '' }}>
                            {{ $kid->name }} - {{ $kid->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Izin</label>

                <input type="date" name="absence_date" class="form-control"
                    value="{{ old('absence_date', now()->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alasan Izin</label>

                <select name="reason_type" class="form-select" required>
                    <option value="">-- Pilih Alasan --</option>
                    <option value="sakit" {{ old('reason_type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="keluarga" {{ old('reason_type') == 'keluarga' ? 'selected' : '' }}>Kepentingan Keluarga
                    </option>
                    <option value="lainnya" {{ old('reason_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan</label>

                <textarea name="note" rows="4" class="form-control"
                    placeholder="Contoh: Anak demam, izin tidak dijemput hari ini">{{ old('note') }}</textarea>
            </div>

            <div class="form-action-row">
                <button class="btn btn-primary-custom">
                    <i class="bi bi-save"></i>
                    Simpan Izin
                </button>

                <a href="/izin-anak" class="btn btn-secondary-custom">
                    Kembali
                </a>
            </div>
        </form>

    </div>

@endsection