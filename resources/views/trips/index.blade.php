@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Riwayat Perjalanan</div>
            <div class="page-subtitle">
                Data seluruh perjalanan antar jemput anak yang telah ditugaskan oleh admin
            </div>
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
                            <th>Orang Tua</th>
                            <th>Sekolah</th>
                            <th>Alamat</th>
                            <th>Sopir</th>
                            <th>Jam Jemput</th>
                            <th>Jam Antar</th>
                            <th>Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($trips as $trip)

                            @php
                                $statusText = [
                                    'assigned' => 'Ditugaskan',
                                    'on_pickup' => 'Menuju Jemput',
                                    'picked' => 'Dijemput',
                                    'on_delivery' => 'Diantar',
                                    'completed' => 'Selesai',
                                ];

                                $statusClass = [
                                    'assigned' => 'badge-assigned',
                                    'on_pickup' => 'badge-pending',
                                    'picked' => 'badge-active',
                                    'on_delivery' => 'badge-active',
                                    'completed' => 'badge-active',
                                ];
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $trip->kid->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $trip->kid->parent->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $trip->kid->school_name ?? '-' }}
                                </td>

                                <td>
                                    {{ $trip->kid->address ?? '-' }}
                                </td>

                                <td>
                                    {{ $trip->driver->user->name ?? '-' }}
                                </td>

                                <td>
                                    @if($trip->pickup_time)
                                        {{ \Carbon\Carbon::parse($trip->pickup_time)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($trip->dropoff_time)
                                        {{ \Carbon\Carbon::parse($trip->dropoff_time)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-status {{ $statusClass[$trip->status] ?? 'badge-assigned' }}">
                                        {{ $statusText[$trip->status] ?? $trip->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/trips/{{ $trip->id }}" class="icon-btn icon-btn-info"
                                        title="Detail Perjalanan">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Belum ada riwayat perjalanan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection