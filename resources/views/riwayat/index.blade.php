@extends('layouts.app')

@section('title', 'Riwayat Antar Jemput')

@section('content')
    <div class="container-fluid">
        <h3 class="fw-bold">Riwayat Antar Jemput</h3>
        <p class="text-muted">Pantau riwayat perjalanan antar jemput anak</p>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Anak</th>
                            <th>Driver</th>
                            <th>Jam Jemput</th>
                            <th>Jam Antar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trips as $trip)
                            <tr>
                                <td>{{ $trip->kid->name ?? '-' }}</td>
                                <td>{{ $trip->driver->user->name ?? '-' }}</td>
                                <td>{{ $trip->pickup_time ?? '-' }}</td>
                                <td>{{ $trip->dropoff_time ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusText = [
                                            'assigned' => 'Ditugaskan',
                                            'on_pickup' => 'Menuju Jemput',
                                            'picked' => 'Dijemput',
                                            'on_delivery' => 'Diantar',
                                            'completed' => 'Selesai',
                                        ];
                                    @endphp

                                    <span class="badge bg-primary">
                                        {{ $statusText[$trip->status] ?? $trip->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada riwayat antar jemput
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection