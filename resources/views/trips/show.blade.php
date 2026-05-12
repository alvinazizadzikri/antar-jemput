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