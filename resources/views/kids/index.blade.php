@extends('layouts.app')

@section('content')

    <h3>Data Anak</h3>

    <a href="/kids/create" class="btn btn-primary mb-3">+ Tambah Anak</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        {{ $kids->links() }}
        <input type="text" name="name" class="form-control" placeholder="Masukkan nama anak">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Sekolah</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($kids as $k)
                <tr>
                    <td>{{ $k->name }}</td>
                    <td>{{ $k->school_name }}</td>
                    <td>
                        @if($k->photo)
                            <img src="{{ asset('storage/' . $k->photo) }}" width="60">
                        @endif
                    </td>
                    <td>
                        <a href="/kids/{{ $k->id }}/edit" class="btn btn-warning btn-sm">Edit</a>

                        <form action="/kids/{{ $k->id }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

@endsection
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif