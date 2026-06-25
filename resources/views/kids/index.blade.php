@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Data Anak</div>
            <div class="page-subtitle">
                Kelola data anak, lokasi rumah, dan titik antar jemput
            </div>
        </div>

        <div class="header-actions">
            <a href="/kids/create" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle"></i>
                Tambah Anak
            </a>
        </div>
    </div>

    <div class="search-card">
        <form method="GET" action="/kids">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama anak / sekolah..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary-custom w-100">
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Data Anak</th>
                            <th>Alamat Rumah</th>
                            <th>Titik Jemput</th>
                            <th>Titik Antar</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($kids as $k)
                            <tr>
                                <td style="width: 80px;">
                                    @if($k->photo)
                                        <img src="{{ asset('storage/' . $k->photo) }}" width="54" height="54"
                                            class="rounded-circle border" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:54px; height:54px;">
                                            👦
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $k->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $k->school_name }}
                                    </small>
                                </td>

                                <td>
                                    {{ $k->address }}
                                </td>

                                <td>
                                    <span class="point-badge-blue">
                                        {{ $k->pickup_point }}
                                    </span>
                                </td>

                                <td>
                                    <span class="point-badge-green">
                                        {{ $k->dropoff_point }}
                                    </span>
                                </td>

                                <td>
                                    <div class="icon-action-group">

                                        @if(!is_null($k->latitude) && !is_null($k->longitude))
                                            <a href="https://www.google.com/maps?q={{ $k->latitude }},{{ $k->longitude }}"
                                                target="_blank" rel="noopener" class="icon-btn icon-btn-info"
                                                title="Buka Google Maps">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </a>
                                        @else
                                            <span class="icon-btn icon-btn-danger" title="Lokasi belum tersedia">
                                                <i class="bi bi-geo-alt-slash-fill"></i>
                                            </span>
                                        @endif

                                        <a href="/kids/{{ $k->id }}/edit" class="icon-btn icon-btn-warning" title="Edit Data">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <form action="/kids/{{ $k->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                class="icon-btn icon-btn-danger" title="Hapus Data">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Belum ada data anak.
                                    Silakan tambahkan data anak terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $kids->appends(request()->query())->links() }}
            </div>

        </div>
    </div>

@endsection