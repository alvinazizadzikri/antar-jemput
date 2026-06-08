@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Data Sopir</div>
            <div class="page-subtitle">
                Kelola data sopir, kendaraan, plat nomor, dan status ketersediaan
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/drivers/create" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle"></i>
                Tambah Sopir
            </a>
        </div>
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
                            <th>No</th>
                            <th>Nama</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Kendaraan</th>
                            <th>Plat</th>
                            <th>Status</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $driver->user->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $driver->phone_number ?? '-' }}
                                </td>

                                <td>{{ $driver->user->email ?? '-' }}</td>

                                <td>{{ $driver->vehicle_type }}</td>

                                <td>
                                    <span class="package-badge">
                                        {{ $driver->plate_number }}
                                    </span>
                                </td>

                                <td>
                                    @if($driver->status == 'online')
                                        <span class="badge-status badge-active">
                                            Online
                                        </span>
                                    @else
                                        <span class="badge-status badge-danger">
                                            Offline
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="icon-action-group">

                                        <a href="/admin/drivers/{{ $driver->id }}/edit" class="icon-btn icon-btn-warning"
                                            title="Edit Sopir">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <a href="/admin/drivers/{{ $driver->id }}/history" class="icon-btn icon-btn-info"
                                            title="Riwayat Sopir">
                                            <i class="bi bi-clock-history"></i>
                                        </a>

                                        <form action="/admin/drivers/{{ $driver->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="icon-btn icon-btn-danger"
                                                onclick="return confirm('Yakin hapus sopir?')" title="Hapus Sopir">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data sopir
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection