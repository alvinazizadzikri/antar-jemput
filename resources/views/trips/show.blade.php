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

        $subscription = $trip->kid->subscriptions->first();
    @endphp

    <div class="section-header">
        <div>
            <div class="page-title">Detail Perjalanan</div>
            <div class="page-subtitle">
                Detail data anak, sopir, kendaraan, dan status perjalanan
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/trips" class="btn btn-secondary-custom">
                Kembali
            </a>
        </div>
    </div>

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="detail-table">

                    <tr>
                        <th>Nama Anak</th>
                        <td>{{ $trip->kid->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Nama Orang Tua</th>
                        <td>{{ $trip->kid->parent->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Alamat Rumah</th>
                        <td>{{ $trip->kid->address ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Sekolah</th>
                        <td>{{ $trip->kid->school_name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Titik Jemput</th>
                        <td>
                            <span class="point-badge-blue">
                                {{ $trip->kid->pickup_point ?? '-' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Titik Antar</th>
                        <td>
                            <span class="point-badge-green">
                                {{ $trip->kid->dropoff_point ?? '-' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Paket Langganan</th>
                        <td>
                            @if($subscription)
                                <span class="package-badge">
                                    {{ $subscription->package_name }}
                                </span>

                                <span class="ms-2">
                                    Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="badge-status badge-danger">
                                    Belum Berlangganan
                                </span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Foto Anak</th>
                        <td>
                            @if($trip->kid->photo)
                                <img src="{{ asset('storage/' . $trip->kid->photo) }}" class="detail-photo">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Sopir</th>
                        <td>{{ $trip->driver->user->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Kendaraan</th>
                        <td>{{ $trip->driver->vehicle_type ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Plat Nomor</th>
                        <td>
                            <span class="package-badge">
                                {{ $trip->driver->plate_number ?? '-' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Jam Jemput</th>
                        <td>
                            @if($trip->pickup_time)
                                {{ \Carbon\Carbon::parse($trip->pickup_time)->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge-status {{ $statusClass[$trip->status] ?? 'badge-assigned' }}">
                                {{ $statusText[$trip->status] ?? $trip->status }}
                            </span>
                        </td>
                    </tr>

                </table>
            </div>

        </div>
    </div>

@endsection