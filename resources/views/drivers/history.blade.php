@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Riwayat Sopir</div>
            <div class="page-subtitle">
                Riwayat perjalanan yang ditugaskan kepada {{ $driver->user->name ?? '-' }}
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/drivers" class="btn btn-secondary-custom">
                Kembali
            </a>
        </div>
    </div>

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Anak</th>
                            <th>Jam Jemput</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($trips as $trip)

                            @php
                                $statusText = [
                                    'assigned' => 'Ditugaskan',
                                    'picked_up' => 'Anak Dijemput',
                                    'arrived_school' => 'Sampai Sekolah',
                                    'picked_up_school' => 'Dijemput Pulang',
                                    'completed' => 'Selesai',
                                    'return_cancelled' => 'Tidak Ikut Jemput Pulang',
                                ];

                                $statusClass = [
                                    'assigned' => 'badge-assigned',
                                    'picked_up' => 'badge-pending',
                                    'arrived_school' => 'badge-active',
                                    'picked_up_school' => 'badge-pending',
                                    'completed' => 'badge-active',
                                    'return_cancelled' => 'badge-danger',
                                ];
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $trip->kid->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    @if($trip->pickup_time)
                                        {{ \Carbon\Carbon::parse($trip->pickup_time)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-status {{ $statusClass[$trip->status] ?? 'badge-assigned' }}">
                                        {{ $statusText[$trip->status] ?? $trip->status }}
                                    </span>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada riwayat sopir
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection