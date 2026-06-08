@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Tugas Sopir</div>
            <div class="page-subtitle">
                Daftar perjalanan berdasarkan rombongan anak dalam satu trip
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
                <table class="custom-table table-compact">
                    <thead>
                        <tr>
                            <th>Kode Trip</th>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Lokasi Jemput</th>
                            <th>Rencana Jemput</th>
                            <th>Aktual Jemput</th>
                            <th>Status</th>
                            <th style="width: 220px;">Aksi Berikutnya</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tripGroups as $tripCode => $group)

                            @php
                                $firstTrip = $group->first();

                                $statusText = [
                                    'assigned' => 'Ditugaskan',
                                    'picked_up' => 'Anak Dijemput',
                                    'arrived_school' => 'Sampai Sekolah',
                                    'picked_up_school' => 'Dijemput Pulang',
                                    'completed' => 'Selesai',
                                ];

                                $statusClass = [
                                    'assigned' => 'badge-assigned',
                                    'picked_up' => 'badge-pending',
                                    'arrived_school' => 'badge-active',
                                    'picked_up_school' => 'badge-pending',
                                    'completed' => 'badge-active',
                                ];

                                $nextStatus = [
                                    'assigned' => [
                                        'value' => 'picked_up',
                                        'label' => 'Anak Dijemput',
                                    ],
                                    'picked_up' => [
                                        'value' => 'arrived_school',
                                        'label' => 'Sampai Sekolah',
                                    ],
                                    'arrived_school' => [
                                        'value' => 'picked_up_school',
                                        'label' => 'Jemput Pulang',
                                    ],
                                    'picked_up_school' => [
                                        'value' => 'completed',
                                        'label' => 'Sampai Rumah',
                                    ],
                                ][$firstTrip->status] ?? null;
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $firstTrip->trip_code ?? 'TRIP LAMA' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $group->count() }} anak
                                    </small>
                                </td>

                                <td>
                                    @foreach($group as $trip)
                                        <div class="fw-bold mb-1">
                                            {{ $trip->kid->name ?? '-' }}
                                        </div>
                                    @endforeach
                                </td>

                                <td>
                                    @foreach($group as $trip)
                                        <div class="mb-1">
                                            {{ $trip->kid->parent->name ?? '-' }}
                                        </div>
                                    @endforeach
                                </td>

                                <td>
                                    @foreach($group as $trip)
                                        <div class="mb-2">
                                            <div class="fw-semibold">
                                                {{ $trip->kid->name ?? '-' }}
                                            </div>

                                            <small class="text-muted d-block">
                                                {{ $trip->kid->address ?? '-' }}
                                            </small>

                                            @if(!is_null($trip->kid->latitude) && !is_null($trip->kid->longitude))
                                                <a href="https://www.google.com/maps?q={{ $trip->kid->latitude }},{{ $trip->kid->longitude }}"
                                                    target="_blank" class="btn btn-primary-custom btn-sm mt-1">
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                    Buka Maps
                                                </a>
                                            @else
                                                <span class="badge-status badge-danger">
                                                    Lokasi belum ada
                                                </span>
                                            @endif
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
                                    @if($firstTrip->actual_pickup_time)
                                        {{ \Carbon\Carbon::parse($firstTrip->actual_pickup_time)->format('H:i') }}
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
                                    @if($nextStatus)
                                        <form action="/driver/jobs/{{ $firstTrip->id }}/status" method="POST" class="action-form">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="status" value="{{ $nextStatus['value'] }}">

                                            <button type="submit" class="btn btn-primary-custom">
                                                {{ $nextStatus['label'] }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="fw-bold text-success">
                                            Perjalanan selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada tugas sopir
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection