@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Langganan Saya
                </h3>

                <small class="text-muted">
                    Kelola paket antar jemput anak
                </small>
            </div>

            <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Tambah Langganan

            </a>

        </div>

        {{-- ALERT --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>

        @endif

        {{-- CARD --}}
        <div class="card shadow border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle table-hover">

                        <thead class="table-light">

                            <tr>

                                <th>Anak</th>
                                <th>Paket</th>
                                <th>Harga</th>
                                <th>Pembayaran</th>
                                <th>Status</th>
                                <th>Sisa Hari</th>
                                <th>Tanggal</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($subscriptions as $subscription)

                                <tr>

                                    {{-- NAMA ANAK --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $subscription->kid->name ?? '-' }}
                                        </div>

                                    </td>

                                    {{-- PAKET --}}
                                    <td>

                                        <span class="badge bg-primary">

                                            {{ $subscription->package_name }}

                                        </span>

                                    </td>

                                    {{-- HARGA --}}
                                    <td>

                                        Rp {{ number_format($subscription->price, 0, ',', '.') }}

                                    </td>

                                    {{-- PEMBAYARAN --}}
                                    <td>

                                        {{ $subscription->payment_method }}

                                    </td>

                                    {{-- STATUS --}}
                                    <td>

                                        @if($subscription->is_paused)

                                            <span class="badge bg-secondary">

                                                Dipause

                                            </span>

                                        @elseif($subscription->status == 'pending')

                                            <span class="badge bg-warning text-dark">

                                                Pending

                                            </span>

                                        @elseif($subscription->status == 'paid')

                                            <span class="badge bg-info">

                                                Dibayar

                                            </span>

                                        @elseif($subscription->status == 'active')

                                            <span class="badge bg-success">

                                                Aktif

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                Expired

                                            </span>

                                        @endif

                                    </td>

                                    {{-- SISA HARI --}}
                                    <td>

                                        @if($subscription->is_paused)

                                            <span class="text-warning fw-bold">

                                                {{ $subscription->remaining_days }} Hari

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- TANGGAL --}}
                                    <td>

                                        <small>

                                            {{ $subscription->created_at->format('d M Y') }}

                                        </small>

                                    </td>


                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-5">

                                        <i class="bi bi-credit-card fs-1 d-block mb-2"></i>

                                        Belum ada langganan

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection