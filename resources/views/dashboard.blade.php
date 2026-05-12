@extends('layouts.app')

@section('content')

<h4 class="mb-4">Dashboard</h4>

<div class="row">

    <div class="col-md-4">
        <div class="card text-center p-3">
            <h2 class="text-primary">{{ auth()->user()->kids()->count() }}</h2>
            <p>Total Anak</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-3">
            <h2 class="text-success">Aktif</h2>
            <p>Status Layanan</p>
        </div>
    </div>

</div>

@endsection