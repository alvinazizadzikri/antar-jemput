@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Data Anak
            </h3>

            <p class="text-muted mb-0">
                Kelola data anak, lokasi rumah, dan titik antar jemput
            </p>
        </div>

        <a href="/kids/create" class="btn btn-primary">
            + Tambah Anak
        </a>

    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET" action="/kids">

                <div class="row g-2">

                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama anak / sekolah..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Cari
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>

    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle table-hover">

                    <thead class="table-light">

                        <tr>
                            <th>Foto</th>
                            <th>Data Anak</th>
                            <th>Alamat Rumah</th>
                            <th>Titik Jemput</th>
                            <th>Titik Antar</th>
                            <th width="220">Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($kids as $k)

                            <tr>

                                {{-- FOTO --}}
                                <td width="80">

                                    @if($k->photo)

                                        <img src="{{ asset('storage/' . $k->photo) }}" width="60" height="60"
                                            class="rounded-circle object-fit-cover border">

                                    @else

                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:60px; height:60px;">

                                            👦

                                        </div>

                                    @endif

                                </td>

                                {{-- DATA --}}
                                <td>

                                    <div class="fw-bold">
                                        {{ $k->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $k->school_name }}
                                    </small>

                                </td>

                                {{-- ALAMAT --}}
                                <td>

                                    <small class="text-dark">
                                        {{ $k->address }}
                                    </small>

                                </td>

                                {{-- TITIK JEMPUT --}}
                                <td>

                                    <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                        {{ $k->pickup_point }}
                                    </span>

                                </td>

                                {{-- TITIK ANTAR --}}
                                <td>

                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        {{ $k->dropoff_point }}
                                    </span>

                                </td>

                                {{-- AKSI --}}
                                {{-- AKSI --}}
                                <td>

                                    <div class="d-flex gap-2">

                                        {{-- LOKASI --}}
                                        <a href="/kids/{{ $k->id }}" class="btn btn-info btn-sm text-white"
                                            title="Lihat Lokasi">

                                            <i class="bi bi-geo-alt-fill"></i>

                                        </a>

                                        {{-- EDIT --}}
                                        <a href="/kids/{{ $k->id }}/edit" class="btn btn-warning btn-sm" title="Edit Data">

                                            <i class="bi bi-pencil-fill"></i>

                                        </a>

                                        {{-- HAPUS --}}
                                        <form action="/kids/{{ $k->id }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                class="btn btn-danger btn-sm" title="Hapus Data">

                                                <i class="bi bi-trash-fill"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486740.png" width="90" class="mb-3">

                                    <div class="fw-bold text-muted">
                                        Belum ada data anak
                                    </div>

                                    <small class="text-muted">
                                        Silakan tambahkan data anak terlebih dahulu
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $kids->appends(request()->query())->links() }}
            </div>

        </div>

    </div>

@endsection