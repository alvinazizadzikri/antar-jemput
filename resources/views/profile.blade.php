@extends('layouts.app')

@section('content')

<h3>Profil Saya</h3>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form method="POST">
@csrf

<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control">
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control">
</div>

<button class="btn btn-success">Update</button>

</form>

@endsection