@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Tugas Sopir</div>
            <div class="page-subtitle">
                Daftar tugas antar jemput anak yang diberikan kepada sopir
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
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Alamat</th>
                            <th>Langganan</th>
                            <th>Status</th>
                            <th style="width: 320px;">Aksi</th>
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
                                    {{ $trip->kid->address ?? '-' }}
                                </td>

                                <td>
                                    @if($trip->kid->subscription)
                                        <div class="fw-semibold">
                                            {{ $trip->kid->subscription->package_name }}
                                        </div>

                                        <span class="badge-status badge-active">
                                            {{ $trip->kid->subscription->status }}
                                        </span>
                                    @else
                                        <span class="badge-status badge-danger">
                                            Belum Langganan
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-status {{ $statusClass[$trip->status] ?? 'badge-assigned' }}">
                                        {{ $statusText[$trip->status] ?? $trip->status }}
                                    </span>
                                </td>

                                <td>
                                    <form action="/driver/jobs/{{ $trip->id }}/status" method="POST" class="action-form">
                                        @csrf
                                        @method('PUT')

                                        <select name="status" class="form-select">
                                            <option value="assigned" {{ $trip->status == 'assigned' ? 'selected' : '' }}>
                                                Ditugaskan
                                            </option>

                                            <option value="on_pickup" {{ $trip->status == 'on_pickup' ? 'selected' : '' }}>
                                                Menuju Jemput
                                            </option>

                                            <option value="picked" {{ $trip->status == 'picked' ? 'selected' : '' }}>
                                                Dijemput
                                            </option>

                                            <option value="on_delivery" {{ $trip->status == 'on_delivery' ? 'selected' : '' }}>
                                                Diantar
                                            </option>

                                            <option value="completed" {{ $trip->status == 'completed' ? 'selected' : '' }}>
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