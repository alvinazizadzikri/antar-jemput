@extends('layouts.app')

@section('content')

    <h3 class="mb-3">

        Riwayat Driver:
        {{ $driver->user->name }}

    </h3>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>Anak</th>
                            <th>Jam Jemput</th>
                            <th>Status</th>
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
                                    {{ $trip->pickup_time }}
                                </td>

                                <td>
                                    {{ $trip->status }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection