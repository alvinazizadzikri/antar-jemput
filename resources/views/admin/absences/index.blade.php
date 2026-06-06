@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Izin Anak</div>
            <div class="page-subtitle">
                Rekap anak yang tidak perlu dijemput karena izin
            </div>
        </div>
    </div>

    <div class="search-card">
        <form method="GET" action="/admin/izin-anak">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Alasan</label>
                    <select name="reason_type" class="form-select">
                        <option value="">Semua Alasan</option>
                        <option value="sakit" {{ request('reason_type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="keluarga" {{ request('reason_type') == 'keluarga' ? 'selected' : '' }}>Kepentingan
                            Keluarga</option>
                        <option value="lainnya" {{ request('reason_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary-custom w-100">
                        Filter
                    </button>

                    <a href="/admin/izin-anak" class="btn btn-secondary-custom">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Tanggal Izin</th>
                            <th>Alasan</th>
                            <th>Catatan</th>
                            <th>Diajukan Pada</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($absences as $absence)
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $absence->kid->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $absence->kid->parent->name ?? '-' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($absence->absence_date)->format('d/m/Y') }}
                                </td>

                                <td>
                                    @if($absence->reason_type == 'sakit')
                                        <span class="badge-status badge-danger">Sakit</span>
                                    @elseif($absence->reason_type == 'keluarga')
                                        <span class="badge-status badge-pending">Kepentingan Keluarga</span>
                                    @else
                                        <span class="badge-status badge-assigned">Lainnya</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $absence->note ?? '-' }}
                                </td>

                                <td>
                                    {{ $absence->created_at->format('d/m/Y H:i') }}
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