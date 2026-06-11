@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Riwayat Antar Jemput</div>
            <div class="page-subtitle">
                Pantau riwayat perjalanan antar jemput anak
            </div>
        </div>
    </div>

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Sopir</th>
                            <th>No. Telepon Sopir</th>
                            <th>Tanggal</th>
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
                                    'picked_up' => 'Anak Dijemput',
                                    'arrived_school' => 'Sampai Sekolah',
                                    'picked_up_school' => 'Dijemput Pulang',
                                    'completed' => 'Selesai',
                                    'return_cancelled' => 'Tidak Ikut Jemput Pulang',
                                ];

                                $statusClass = [
                                    'assigned' => 'badge-assigned',
                                    'picked_up' => 'badge-pending',
                                    'arrived_school' => 'badge-active',
                                    'picked_up_school' => 'badge-pending',
                                    'completed' => 'badge-active',
                                    'return_cancelled' => 'badge-danger',
                                ];

                                $phoneNumber = $trip->driver->phone_number ?? null;
                                $waPhone = null;

                                if ($phoneNumber) {
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

                                    if (substr($cleanPhone, 0, 1) === '0') {
                                        $waPhone = '62' . substr($cleanPhone, 1);
                                    } else {
                                        $waPhone = $cleanPhone;
                                    }
                                }

                                $tanggalPerjalanan = '-';

                                if ($trip->pickup_time) {
                                    $tanggalPerjalanan = \Carbon\Carbon::parse($trip->pickup_time)
                                        ->locale('id')
                                        ->translatedFormat('l, d F Y');
                                }
                            @endphp

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
                                    {{ $trip->driver->user->name ?? '-' }}
                                </td>

                                <td>
                                    @if($phoneNumber && $waPhone)
                                        <a href="https://wa.me/{{ $waPhone }}" target="_blank"
                                            class="fw-bold text-success text-decoration-none">
                                            <i class="bi bi-whatsapp"></i>
                                            {{ $phoneNumber }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $tanggalPerjalanan }}
                                    </div>
                                </td>

                                <td>
                                    @if($trip->pickup_time)
                                        {{ \Carbon\Carbon::parse($trip->pickup_time)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($trip->dropoff_time)
                                        {{ \Carbon\Carbon::parse($trip->dropoff_time)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-status {{ $statusClass[$trip->status] ?? 'badge-assigned' }}">
                                        {{ $statusText[$trip->status] ?? $trip->status }}
                                    </span>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
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