@extends('layouts.app')

@section('content')

    <h3 class="mb-3">
        Tambah Driver
    </h3>

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="card">

        <div class="card-body">

            <form action="/admin/drivers" method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        User Driver
                    </label>

                    <select name="user_id" class="form-control" required>

                        <option value="">
                            -- Pilih Driver --
                        </option>

                        @foreach($users as $user)

                            <option value="{{ $user->id }}">

                                {{ $user->name }}
                                -
                                {{ $user->email }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Jenis Kendaraan
                    </label>

                    <input type="text" name="vehicle_type" class="form-control" placeholder="Contoh: Mobil" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Plat Nomor
                    </label>

                    <input type="text" name="plate_number" class="form-control" placeholder="Contoh: B 1234 ABC" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status" class="form-control">

                        <option value="online">
                            Online
                        </option>

                        <option value="offline">
                            Offline
                        </option>

                    </select>

                </div>

                <button class="btn btn-primary">

                    Simpan

                </button>

                <a href="/admin/drivers" class="btn btn-secondary">

                    Kembali

                </a>

            </form>

        </div>

    </div>

@endsection