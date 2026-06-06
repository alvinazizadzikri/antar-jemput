@extends('layouts.app')

@section('content')

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

        $firstTrip = $tripGroup->first();
    @endphp

    <div class="section-header">
        <div>
            <div class="page-title">Detail Perjalanan</div>
            <div class="page-subtitle">
                Detail perjalanan berdasarkan kode trip dan daftar anak dalam rombongan
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/trips" class="btn btn-secondary-custom">
                Kembali
            </a>
        </div>
    </div>

    <div class="page-card mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="detail-table">

                    <tr>
                        <th>Kode Trip</th>
                        <td>
                            <span class="package-badge">
                                {{ $firstTrip->trip_code ?? 'TRIP LAMA' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Sopir</th>
                        <td>{{ $firstTrip->driver->user->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Kendaraan</th>
                        <td>{{ $firstTrip->driver->vehicle_type ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Plat Nomor</th>
                        <td>
                            <span class="package-badge">
                                {{ $firstTrip->driver->plate_number ?? '-' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Jam Rencana Jemput</th>
                        <td>
                            @if($firstTrip->pickup_time)
                                {{ \Carbon\Carbon::parse($firstTrip->pickup_time)->format('H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Jam Aktual Jemput</th>
                        <td>
                            @if($firstTrip->actual_pickup_time)
                                {{ \Carbon\Carbon::parse($firstTrip->actual_pickup_time)->format('H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Jam Antar</th>
                        <td>
                            @if($firstTrip->dropoff_time)
                                {{ \Carbon\Carbon::parse($firstTrip->dropoff_time)->format('H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge-status {{ $statusClass[$firstTrip->status] ?? 'badge-assigned' }}">
                                {{ $statusText[$firstTrip->status] ?? $firstTrip->status }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Jumlah Anak</th>
                        <td>{{ $tripGroup->count() }} anak</td>
                    </tr>

                </table>
            </div>

        </div>
    </div>

    <div class="page-card">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Daftar Anak dalam Perjalanan</h5>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Sekolah</th>
                            <th>Alamat</th>
                            <th>Titik Jemput</th>
                            <th>Titik Antar</th>
                            <th>Langganan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tripGroup as $item)

                            @php
                                $subscription = $item->kid->subscription;
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $item->kid->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $item->kid->parent->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->kid->school_name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->kid->address ?? '-' }}
                                </td>

                                <td>
                                    <span class="point-badge-blue">
                                        {{ $item->kid->pickup_point ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="point-badge-green">
                                        {{ $item->kid->dropoff_point ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    @if($subscription)
                                        <span class="package-badge">
                                            {{ $subscription->package_name }}
                                        </span>
                                    @else
                                        <span class="badge-status badge-danger">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada anak dalam perjalanan ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection