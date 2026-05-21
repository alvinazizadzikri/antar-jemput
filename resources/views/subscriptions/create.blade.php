@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4>Pilih Paket Langganan</h4>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('subscriptions.store') }}">

                    @csrf

                    <div class="mb-3">
                        <label>Anak</label>

                        <select name="kid_id" class="form-control">

                            @foreach($kids as $kid)
                                <option value="{{ $kid->id }}">
                                    {{ $kid->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="card border p-3">

                                <h5>Harian</h5>

                                <h3>Rp 50.000</h3>

                                <button name="package_name" value="Harian" class="btn btn-primary">

                                    Pilih

                                </button>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border p-3">

                                <h5>Mingguan</h5>

                                <h3>Rp 250.000</h3>

                                <button name="package_name" value="Mingguan" class="btn btn-success">

                                    Pilih

                                </button>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border p-3">

                                <h5>Bulanan</h5>

                                <h3>Rp 800.000</h3>

                                <button name="package_name" value="Bulanan" class="btn btn-warning">

                                    Pilih

                                </button>

                            </div>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection