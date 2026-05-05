@extends('layouts.app')

@section('content')

<h3>Edit Data Anak</h3>

<div class="card">
    <div class="card-body">

        <form method="POST" action="/kids/{{ $kid->id }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $kid->name }}">
        </div>

        <div class="mb-3">
            <label>Sekolah</label>
            <input type="text" name="school_name" class="form-control" value="{{ $kid->school_name }}">
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="address" class="form-control">{{ $kid->address }}</textarea>
        </div>

        <div class="mb-3">
            <label>Titik Jemput</label>
            <input type="text" name="pickup_point" class="form-control" value="{{ $kid->pickup_point }}">
        </div>

        <div class="mb-3">
            <label>Titik Antar</label>
            <input type="text" name="dropoff_point" class="form-control" value="{{ $kid->dropoff_point }}">
        </div>

        <div class="mb-3">
            <label>Foto</label>
            <input type="file" name="photo" class="form-control">
        </div>

        @if($kid->photo)
        <div class="mb-3">
            <p>Foto Saat Ini:</p>
            <img src="{{ asset('storage/'.$kid->photo) }}" width="100">
        </div>
        @endif

        <button class="btn btn-success">Update</button>
        <a href="/kids" class="btn btn-secondary">Kembali</a>

        </form>

    </div>
</div>

@endsection