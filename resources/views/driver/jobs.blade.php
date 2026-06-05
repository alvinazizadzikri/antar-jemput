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

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="custom-table table-compact">
                    <thead>
                        <tr>
                            <th>Kode Trip</th>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Jam Jemput</th>
                            <th>Status</th>
                            <th style="width: 320px;">Aksi</th>
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
                                    <span class="badge-status {{ $statusClass[$firstTrip->status] ?? 'badge-assigned' }}">
                                        {{ $statusText[$firstTrip->status] ?? $firstTrip->status }}
                                    </span>
                                </td>

                                <td>
                                    <form action="/driver/jobs/{{ $firstTrip->id }}/status" method="POST" class="action-form">
                                        @csrf
                                        @method('PUT')

                                        <select name="status" class="form-select">
                                            <option value="assigned" {{ $firstTrip->status == 'assigned' ? 'selected' : '' }}>
                                                Ditugaskan
                                            </option>

                                            <option value="on_pickup" {{ $firstTrip->status == 'on_pickup' ? 'selected' : '' }}>
                                                Menuju Jemput
                                            </option>

                                            <option value="picked" {{ $firstTrip->status == 'picked' ? 'selected' : '' }}>
                                                Dijemput
                                            </option>

                                            <option value="on_delivery" {{ $firstTrip->status == 'on_delivery' ? 'selected' : '' }}>
                                                Diantar
                                            </option>

                                            <option value="completed" {{ $firstTrip->status == 'completed' ? 'selected' : '' }}>
                                                Selesai
                                            </option>
                                        </select>

                                        <button type="submit" class="btn btn-primary-custom">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
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