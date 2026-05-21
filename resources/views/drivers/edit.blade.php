@extends('layouts.app')

@section('content')

    <h3 class="mb-3">
        Edit Driver
    </h3>

    <div class="card">

        <div class="card-body">

            <form action="/admin/drivers/{{ $driver->id }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label>
                        Jenis Kendaraan
                    </label>

                    <input type="text" name="vehicle_type" class="form-control" value="{{ $driver->vehicle_type }}">

                </div>

                <div class="mb-3">

                    <label>
                        Plat Nomor
                    </label>

                    <input type="text" name="plate_number" class="form-control" value="{{ $driver->plate_number }}">

                </div>

                <div class="mb-3">

                    <label>
                        Status
                    </label>

                    <select name="status" class="form-control">

                        <option value="online" {{ $driver->status == 'online' ? 'selected' : '' }}>

                            Online

                        </option>

                        <option value="offline" {{ $driver->status == 'offline' ? 'selected' : '' }}>

                            Offline

                        </option>

                    </select>

                </div>

                <button class="btn btn-primary">

                    Update

                </button>

            </form>

        </div>

    </div>

@endsection