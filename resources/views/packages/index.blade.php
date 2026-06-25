@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Paket Langganan</div>
            <div class="page-subtitle">
                Kelola harga dan durasi paket langganan
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/packages/create" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle"></i>
                Tambah Paket
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
                            <th>Nama Paket</th>
                            <th>Harga</th>
                            <th>Durasi Hari Sekolah</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        {{ $package->name }}
                                    </div>
                                </td>

                                <td>
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $package->duration_days }} hari sekolah
                                </td>

                                <td>
                                    {{ $package->description ?? '-' }}
                                </td>

                                <td>
                                    @if($package->is_active)
                                        <span class="badge-status badge-active">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge-status badge-danger">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="icon-action-group">
                                        <a href="/admin/packages/{{ $package->id }}/edit" class="icon-btn icon-btn-warning"
                                            title="Edit Paket">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        @if($package->is_active)
                                            <form action="/admin/packages/{{ $package->id }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button class="icon-btn icon-btn-danger"
                                                    onclick="return confirm('Nonaktifkan paket ini?')" title="Nonaktifkan Paket">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada paket langganan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection