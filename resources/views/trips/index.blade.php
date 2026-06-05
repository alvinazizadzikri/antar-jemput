@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Riwayat Perjalanan</div>
            <div class="page-subtitle">
                Data perjalanan antar jemput berdasarkan kode trip
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
                            <th>Kode Trip</th>
                            <th>Sopir</th>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Jam Jemput</th>
                            <th>Jam Antar</th>
                            <th>Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tripGroups as $tripCode => $group)

                            @php
                                $firstTrip = $group->first();

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
                                        {{ $firstTrip->trip_code ?? '-' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $group->count() }} anak
                                    </small>
                                </td>

                                <td>
                                    {{ $firstTrip->driver->user->name ?? '-' }}
                                </td>

                                <td>
                                    @foreach($group as $trip)
                                        <div class="fw-bold">
                                            {{ $trip->kid->name ?? '-' }}
                                        </div>
                                    @endforeach
                                </td>

                                <td>
                                    @foreach($group as $trip)
                                        <div>
                                            {{ $trip->kid->parent->name ?? '-' }}
                                        </div>
                                    @endforeach
                                </td>

                                <td>
                                    @if($firstTrip->pickup_time)
                                        {{ \Carbon\Carbon::parse($firstTrip->pickup_time)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($firstTrip->dropoff_time)
                                        {{ \Carbon\Carbon::parse($firstTrip->dropoff_time)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-status {{ $statusClass[$firstTrip->status] ?? 'badge-assigned' }}">
                                        {{ $statusText[$firstTrip->status] ?? $firstTrip->status }}
                                    </span>
                                </td>

                                <td>
                                    <a href="/admin/trips/{{ $firstTrip->id }}" class="icon-btn icon-btn-info"
                                        title="Detail Perjalanan">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
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