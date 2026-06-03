@extends('layouts.app')

@section('content')

    <div class="card">

        <div class="card-header bg-dark text-white">

            Detail Trip

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">
                        Nama Anak
                    </th>

                    <td>
                        {{ $trip->kid->name }}
                    </td>
                </tr>

                <tr>
                    <th>Nama Orang Tua</th>
                    <td>
                        {{ $trip->kid->parent->name ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Alamat Rumah</th>
                    <td>
                        {{ $trip->kid->address }}
                    </td>
                </tr>

                <tr>
                    <th>Sekolah</th>
                    <td>
                        {{ $trip->kid->school_name }}
                    </td>
                </tr>

                <tr>
                    <th>Titik Jemput</th>
                    <td>
                        {{ $trip->kid->pickup_point }}
                    </td>
                </tr>

                <tr>
                    <th>Titik Antar</th>
                    <td>
                        {{ $trip->kid->dropoff_point }}
                    </td>
                </tr>

                <tr>
                    <th>Paket Langganan</th>
                    <td>

                        @php
                            $subscription = $trip->kid->subscriptions->first();
                        @endphp

                        @if($subscription)

                            {{ $subscription->package_name }}

                            (Rp {{ number_format($subscription->price, 0, ',', '.') }})

                        @else

                            Belum Berlangganan

                        @endif

                    </td>
                </tr>

                <tr>
                    <th>Foto Anak</th>

                    <td>

                        @if($trip->kid->photo)

                            <img src="{{ asset('storage/' . $trip->kid->photo) }}" width="200" class="img-thumbnail">

                        @endif

                    </td>
                </tr>

                <tr>
                    <th>Driver</th>

                    <td>
                        {{ $trip->driver->user->name }}
                    </td>
                </tr>

                <tr>
                    <th>Kendaraan</th>

                    <td>
                        {{ $trip->driver->vehicle_type }}
                    </td>
                </tr>

                <tr>
                    <th>Plat Nomor</th>

                    <td>
                        {{ $trip->driver->plate_number }}
                    </td>
                </tr>

                <tr>
                    <th>Jam Jemput</th>

                    <td>
                        {{ $trip->pickup_time }}
                    </td>
                </tr>

                <tr>
                    <th>Status</th>

                    <td>

                        @if($trip->status == 'assigned')

                            <span class="badge bg-secondary">
                                Ditugaskan
                            </span>

                        @elseif($trip->status == 'on_pickup')

                            <span class="badge bg-warning text-dark">
                                Menuju Jemput
                            </span>

                        @elseif($trip->status == 'picked')

                            <span class="badge bg-primary">
                                Dijemput
                            </span>

                        @elseif($trip->status == 'on_delivery')

                            <span class="badge bg-success">
                                Diantar
                            </span>

                        @endif

                    </td>

                </tr>

            </table>

            <a href="/admin/trips" class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </div>

@endsection