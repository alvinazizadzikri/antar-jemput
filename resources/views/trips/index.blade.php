@extends('layouts.app')

@section('content')

    <h3 class="mb-3">
        Data Trip
    </h3>

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    <a href="/admin/trips/create" class="btn btn-primary mb-3">

        + Assign Driver

    </a>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>Anak</th>
                            <th>Driver</th>
                            <th>Jam Jemput</th>
                            <th>Status</th>
                            <th width="180">
                                Aksi
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($trips as $trip)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $trip->kid->name }}
                                </td>

                                <td>
                                    {{ $trip->driver->user->name }}
                                </td>

                                <td>
                                    {{ $trip->pickup_time }}
                                </td>

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

                                <td>

                                    <a href="/admin/trips/{{ $trip->id }}" class="btn btn-info btn-sm">

                                        Detail

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection