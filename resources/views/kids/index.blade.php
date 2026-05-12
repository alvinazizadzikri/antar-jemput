@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Anak</h4>
        <a href="/kids/create" class="btn btn-primary">+ Tambah Anak</a>
    </div>

    <!-- SEARCH -->
    <form method="GET" action="/kids" class="row mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari nama / sekolah..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Cari</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Sekolah</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($kids as $k)
                    <tr>
                        <td>
                            @if($k->photo)
                                <img src="{{ asset('storage/' . $k->photo) }}" width="50" class="rounded">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td><strong>{{ $k->name }}</strong></td>
                        <td>{{ $k->school_name }}</td>

                        <td>
                            <a href="/kids/{{ $k->id }}" class="btn btn-info btn-sm">
                                📍 Lokasi
                            </a>

                            <a href="/kids/{{ $k->id }}/edit" class="btn btn-warning btn-sm">Edit</a>

                            <form action="/kids/{{ $k->id }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Belum ada data anak
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        {{ $kids->appends(request()->query())->links() }}
    </div>

@endsection