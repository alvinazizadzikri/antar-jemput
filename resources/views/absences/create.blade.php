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

                <input type="date"
                       name="absence_date"
                       class="form-control"
                       value="{{ old('absence_date', now()->format('Y-m-d')) }}"
                       required>
            </div>

            <div class="mb-3">
    <label class="form-label d-block">Jenis Izin</label>

    <div class="d-flex flex-column gap-2">

        <div class="form-check">
            <input class="form-check-input"
                   type="radio"
                   name="absence_type"
                   id="absence_full_day"
                   value="full_day"
                   {{ old('absence_type', 'full_day') == 'full_day' ? 'checked' : '' }}
                   required>

            <label class="form-check-label" for="absence_full_day">
                Tidak masuk / tidak dijemput hari ini
            </label>

            <small class="text-muted d-block">
                Dipakai jika anak tidak perlu dijemput sejak awal dan belum masuk penugasan sopir.
            </small>
        </div>

        <div class="form-check">
            <input class="form-check-input"
                   type="radio"
                   name="absence_type"
                   id="absence_return_only"
                   value="return_only"
                   {{ old('absence_type') == 'return_only' ? 'checked' : '' }}>

            <label class="form-check-label" for="absence_return_only">
                Tidak ikut jemput pulang
            </label>

            <small class="text-muted d-block">
                Dipakai jika anak sudah berada di sekolah, tetapi tidak perlu dijemput pulang oleh sopir.
            </small>
        </div>

    </div>
</div>

            <div class="mb-3">
                <label class="form-label d-block">Alasan Izin</label>

                <div class="d-flex flex-column gap-2">

                    <div class="form-check">
                        <input class="form-check-input"
                               type="radio"
                               name="reason_type"
                               id="reason_sakit"
                               value="sakit"
                               {{ old('reason_type') == 'sakit' ? 'checked' : '' }}
                               required>

                        <label class="form-check-label" for="reason_sakit">
                            Sakit
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                               type="radio"
                               name="reason_type"
                               id="reason_keluarga"
                               value="keluarga"
                               {{ old('reason_type') == 'keluarga' ? 'checked' : '' }}>

                        <label class="form-check-label" for="reason_keluarga">
                            Kepentingan Keluarga
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                               type="radio"
                               name="reason_type"
                               id="reason_lainnya"
                               value="lainnya"
                               {{ old('reason_type') == 'lainnya' ? 'checked' : '' }}>

                        <label class="form-check-label" for="reason_lainnya">
                            Lainnya
                        </label>
                    </div>

                </div>

                <small class="text-muted">
                    Pilih salah satu alasan izin. Detail tambahan dapat ditulis pada catatan.
                </small>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan</label>

                <textarea name="note"
                          rows="4"
                          class="form-control"
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