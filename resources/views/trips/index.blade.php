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

    <div class="search-card">
        <form method="GET" action="/admin/trips">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Kode Trip</label>
                    <input type="text" name="trip_code" class="form-control" placeholder="Cari kode trip..."
                        value="{{ request('trip_code') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sopir</label>
                    <select name="driver_id" class="form-select">
                        <option value="">Semua Sopir</option>

                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->user->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>

                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>
                            Ditugaskan
                        </option>

                        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>
                            Anak Dijemput
                        </option>

                        <option value="arrived_school" {{ request('status') == 'arrived_school' ? 'selected' : '' }}>
                            Sampai Sekolah
                        </option>

                        <option value="picked_up_school" {{ request('status') == 'picked_up_school' ? 'selected' : '' }}>
                            Dijemput Pulang
                        </option>

                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary-custom w-100">
                        Filter
                    </button>

                    <a href="/admin/trips" class="btn btn-secondary-custom">
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
                            <th>Kode Trip</th>
                            <th>Sopir</th>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Tanggal</th>
                            <th>Rencana Jemput</th>
                            <th>Aktual Jemput</th>
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

                                $tanggalPerjalanan = '-';

                                if ($firstTrip->pickup_time) {
                                    $tanggalPerjalanan = \Carbon\Carbon::parse($firstTrip->pickup_time)
                                        ->locale('id')
                                        ->translatedFormat('l, d F Y');
                                }
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
                                    <div class="fw-semibold">
                                        {{ $tanggalPerjalanan }}
                                    </div>
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
                                <td colspan="10" class="text-center text-muted py-4">
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