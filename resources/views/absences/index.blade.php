@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Izin Anak</div>
            <div class="page-subtitle">
                Ajukan izin jika anak tidak perlu dijemput pada hari tertentu
            </div>
        </div>

        <div class="header-actions">
            <a href="/izin-anak/create" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle"></i>
                Ajukan Izin
            </a>
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

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Jenis Izin</th>
                            <th>Tanggal Izin</th>
                            <th>Alasan</th>
                            <th>Catatan</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($absences as $absence)
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $absence->kid->name ?? '-' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $absence->kid->school_name ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    @if(($absence->absence_type ?? 'full_day') === 'return_only')
                                        <span class="badge-status badge-pending">
                                            Tidak ikut jemput pulang
                                        </span>
                                    @else
                                        <span class="badge-status badge-assigned">
                                            Tidak masuk / tidak dijemput
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($absence->absence_date)->locale('id')->translatedFormat('l, d F Y') }}
                                </td>

                                <td>
                                    @if($absence->reason_type == 'sakit')
                                        <span class="badge-status badge-danger">
                                            Sakit
                                        </span>
                                    @elseif($absence->reason_type == 'keluarga')
                                        <span class="badge-status badge-pending">
                                            Kepentingan Keluarga
                                        </span>
                                    @else
                                        <span class="badge-status badge-assigned">
                                            Lainnya
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $absence->note ?? '-' }}
                                </td>

                                <td>
                                    @if(\Carbon\Carbon::parse($absence->absence_date)->gte(\Carbon\Carbon::today()))
                                        <form action="/izin-anak/{{ $absence->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger-custom btn-sm"
                                                onclick="return confirm('Batalkan izin ini?')">
                                                Batal
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">
                                            Lewat
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada data izin anak
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection