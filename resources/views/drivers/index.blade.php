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
                            <th width="180">Aksi</th>
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
                                <td>

                                    <div class="d-flex gap-2 flex-wrap">

                                        <a href="/admin/drivers/{{ $driver->id }}/edit" class="btn btn-warning btn-sm">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a href="/admin/drivers/{{ $driver->id }}/history" class="btn btn-info btn-sm">

                                            <i class="bi bi-clock-history"></i>

                                        </a>

                                        <form action="/admin/drivers/{{ $driver->id }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus driver?')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection