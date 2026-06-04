@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h3 class="fw-bold">Transaksi Pembayaran</h3>
        <p class="text-muted">Data pembayaran langganan antar jemput anak</p>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Anak</th>
                                <th>Orang Tua</th>
                                <th>Paket</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->kid->name ?? '-' }}</td>
                                    <td>{{ $subscription->user->name ?? '-' }}</td>
                                    <td>{{ $subscription->package_name }}</td>

                                    <td>
                                        Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        {{ $subscription->payment_method ?? 'QRIS' }}
                                    </td>

                                    <td>
                                        @if($subscription->status == 'pending')
                                            <span class="badge bg-warning text-dark">
                                                Menunggu Pembayaran
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

                                    <td>
                                        {{ $subscription->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        @if($subscription->status == 'pending' || $subscription->status == 'paid')
                                            <form action="{{ route('admin.transaksi.verifikasi', $subscription->id) }}"
                                                method="POST">
                                                @csrf

                                                <button class="btn btn-success btn-sm">
                                                    Verifikasi
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">
                                                Sudah aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Belum ada transaksi
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