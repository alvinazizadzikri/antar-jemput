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

    @forelse($tripGroups as $tripCode => $group)

        @php
            $firstTrip = $group
                ->whereNotIn('status', ['return_cancelled', 'completed'])
                ->first() ?? $group->first();

            $statusText = [
                'assigned' => 'Ditugaskan',
                'picked_up' => 'Anak Dijemput',
                'arrived_school' => 'Sampai Sekolah',
                'picked_up_school' => 'Dijemput Pulang',
                'return_cancelled' => 'Tidak Ikut Jemput Pulang',
                'completed' => 'Selesai',
            ];

            $statusClass = [
                'assigned' => 'badge-assigned',
                'picked_up' => 'badge-pending',
                'arrived_school' => 'badge-active',
                'picked_up_school' => 'badge-pending',
                'return_cancelled' => 'badge-danger',
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

        <div class="page-card mb-4">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

                    <div>
                        <div class="fw-bold fs-5">
                            {{ $firstTrip->trip_code ?? 'TRIP LAMA' }}
                        </div>

                        <div class="text-muted">
                            {{ $group->count() }} anak dalam rombongan
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge-status {{ $statusClass[$firstTrip->status] ?? 'badge-assigned' }}">
                            {{ $statusText[$firstTrip->status] ?? $firstTrip->status }}
                        </span>

                        @if($nextStatus)
                            <form action="/driver/jobs/{{ $firstTrip->id }}/status" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="status" value="{{ $nextStatus['value'] }}">

                                <button type="submit" class="btn btn-primary-custom btn-sm">
                                    {{ $nextStatus['label'] }}
                                </button>
                            </form>
                        @else
                            <span class="fw-bold text-success">
                                Perjalanan selesai
                            </span>
                        @endif
                    </div>

                </div>

                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-muted mb-1">
                                Sopir
                            </div>

                            <div class="fw-bold">
                                {{ $firstTrip->driver->user->name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-muted mb-1">
                                Rencana Jemput
                            </div>

                            <div class="fw-bold">
                                @if($firstTrip->pickup_time)
                                    {{ \Carbon\Carbon::parse($firstTrip->pickup_time)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-muted mb-1">
                                Aktual Jemput
                            </div>

                            <div class="fw-bold">
                                @if($firstTrip->actual_pickup_time)
                                    {{ \Carbon\Carbon::parse($firstTrip->actual_pickup_time)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="custom-table table-compact">
                        <thead>
                            <tr>
                                <th>Anak</th>
                                <th>Orang Tua</th>
                                <th>Alamat</th>
                                <th>Lokasi</th>
                                <th>Status Anak</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($group as $trip)

                                <tr>
                                    <td>
                                        <div class="fw-bold">
                                            {{ $trip->kid->name ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $trip->kid->school_name ?? '-' }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $trip->kid->parent->name ?? '-' }}
                                    </td>

                                    <td>
                                        <div>
                                            {{ $trip->kid->address ?? '-' }}
                                        </div>

                                        @if($trip->kid->pickup_point)
                                            <small class="text-muted d-block">
                                                Titik jemput: {{ $trip->kid->pickup_point }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        @if(!is_null($trip->kid->latitude) && !is_null($trip->kid->longitude))
                                            <a href="https://www.google.com/maps?q={{ $trip->kid->latitude }},{{ $trip->kid->longitude }}"
                                                target="_blank" class="btn btn-primary-custom btn-sm">
                                                <i class="bi bi-geo-alt-fill"></i>
                                                Buka Maps
                                            </a>
                                        @else
                                            <span class="badge-status badge-danger">
                                                Lokasi belum ada
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge-status {{ $statusClass[$trip->status] ?? 'badge-assigned' }}">
                                            {{ $statusText[$trip->status] ?? $trip->status }}
                                        </span>

                                        @if($trip->status === 'return_cancelled')
                                            <div class="text-danger fw-semibold mt-1">
                                                Anak tidak perlu dijemput pulang
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    @empty

        <div class="page-card">
            <div class="card-body text-center text-muted py-5">
                Belum ada tugas sopir
            </div>
        </div>

    @endforelse

@endsection