@extends('layouts.app')

@section('content')

    <h3 class="mb-3">
        Data Driver
    </h3>

    <a href="/admin/drivers/create" class="btn btn-primary mb-3">

        + Tambah Driver

    </a>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Kendaraan</th>
                            <th>Plat</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($drivers as $driver)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $driver->user->name }}
                                </td>

                                <td>
                                    {{ $driver->user->email }}
                                </td>

                                <td>
                                    {{ $driver->vehicle_type }}
                                </td>

                                <td>
                                    {{ $driver->plate_number }}
                                </td>

                                <td>

                                    @if($driver->status == 'online')

                                        <span class="badge rounded-pill bg-success px-3 py-2">
                                            🟢 Online
                                        </span>

                                    @else

                                        <span class="badge rounded-pill bg-danger px-3 py-2">
                                            🔴 Offline
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection