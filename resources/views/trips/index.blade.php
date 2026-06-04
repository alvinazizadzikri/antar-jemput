@extends('layouts.app')

@section('title', 'Riwayat Perjalanan')

@section('content')
    <div class="container-fluid">

        <h3 class="fw-bold">Riwayat Perjalanan</h3>
        <p class="text-muted">Data seluruh perjalanan antar jemput anak</p>

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Anak</th>
                                <th>Orang Tua</th>
                                <th>Sekolah</th>
                                <th>Alamat</th>
                                <th>Driver</th>
                                <th>Jam Jemput</th>
                                <th>Jam Antar</th>
                                <th>Status</th>
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

                                    $statusColor = [
                                        'assigned' => 'secondary',
                                        'on_pickup' => 'warning',
                                        'picked' => 'info',
                                        'on_delivery' => 'primary',
                                        'completed' => 'success',
                                    ];
                                @endphp

                                <tr>
                                    <td>{{ $trip->kid->name ?? '-' }}</td>
                                    <td>{{ $trip->kid->parent->name ?? '-' }}</td>
                                    <td>{{ $trip->kid->school_name ?? '-' }}</td>
                                    <td>{{ $trip->kid->address ?? '-' }}</td>
                                    <td>{{ $trip->driver->user->name ?? '-' }}</td>

                                    <td>
                                        {{ $trip->pickup_time ? \Carbon\Carbon::parse($trip->pickup_time)->format('d/m/Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        {{ $trip->dropoff_time ? \Carbon\Carbon::parse($trip->dropoff_time)->format('d/m/Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $statusColor[$trip->status] ?? 'secondary' }}">
                                            {{ $statusText[$trip->status] ?? $trip->status }}
                                        </span>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Belum ada riwayat perjalanan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection