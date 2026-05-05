@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@extends('layouts.app')

@section('content')

<h3>Tambah Anak</h3>

<form method="POST" action="/kids" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="name" class="form-control">
</div>

<div class="mb-3">
    <label>Sekolah</label>
    <input type="text" name="school_name" class="form-control">
</div>

<div class="mb-3">
    <label>Alamat</label>
    <textarea name="address" class="form-control"></textarea>
</div>

<div class="mb-3">
    <label>Titik Jemput</label>
    <input type="text" name="pickup_point" class="form-control">
</div>

<div class="mb-3">
    <label>Titik Antar</label>
    <input type="text" name="dropoff_point" class="form-control">
</div>

<div class="mb-3">
    <label>Foto</label>
    <input type="file" name="photo" class="form-control">
</div>

<button class="btn btn-success">Simpan</button>

</form>

@endsection