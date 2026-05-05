@extends('layouts.app')

@section('content')

    <h3>Dashboard</h3>

    <div class="row">

        <div class="col-md-4">
            <div class="card text-bg-primary">
                <div class="card-body text-center">
                    <h2>{{ auth()->user()->kids->count() }}</h2>
                    <p>Total Anak</p>
                </div>
            </div>
        </div>

    </div>

    <hr>
@endsection
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif