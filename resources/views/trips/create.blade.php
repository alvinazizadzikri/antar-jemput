@extends('layouts.app')

@section('content')

    <h3>Assign Driver</h3>

    <form action="/admin/trips" method="POST">

        @csrf

        <div class="mb-3">
            <label>Anak</label>

            <select name="kid_id" class="form-control">

                @foreach($kids as $kid)

                    <option value="{{ $kid->id }}">
                        {{ $kid->name }}
                        -
                        {{ $kid->parent->name ?? '-' }}
                        -
                        {{ $kid->school_name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label>Driver</label>

            <select name="driver_id" class="form-control">

                @foreach($drivers as $driver)

                    <option value="{{ $driver->id }}">
                        {{ $driver->user->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label>Jam Jemput</label>

            <input type="time" name="pickup_time" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>

            <select name="status" class="form-control">

                <option value="assigned">
                    Ditugaskan
                </option>

                <option value="on_pickup">
                    Menuju Jemput
                </option>

                <option value="picked">
                    Dijemput
                </option>

                <option value="on_delivery">
                    Diantar
                </option>

            </select>
        </div>

        <button class="btn btn-primary">
            Simpan
        </button>

    </form>

@endsection