@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">
                Pembayaran Cash
            </div>

            <div class="page-subtitle">
                Pembayaran dilakukan kepada admin atau sopir
            </div>
        </div>
    </div>

    <div class="page-card">
        <div class="card-body">

            <div class="alert alert-warning">

                <h5 class="mb-3">
                    Pembayaran Tunai
                </h5>

                <p>
                    Silakan lakukan pembayaran secara tunai kepada admin
                    atau sopir.
                </p>

                <p>
                    Nominal yang harus dibayar:
                </p>

                <h4>
                    Rp {{ number_format($subscription->price, 0, ',', '.') }}
                </h4>

                <hr>

                <p>
                    Batas pembayaran:
                    <strong>
                        {{ \Carbon\Carbon::parse($subscription->cash_deadline)->format('d M Y H:i') }}
                    </strong>
                </p>

                <p class="mb-0 text-danger">
                    Jika pembayaran tidak dilakukan sebelum batas waktu,
                    langganan akan dibatalkan otomatis.
                </p>

            </div>

            <form action="/subscriptions/{{ $subscription->id }}/cash-confirm" method="POST">

                @csrf

                <button class="btn btn-success-custom">
                    Saya Akan Membayar Cash
                </button>

            </form>

        </div>
    </div>

@endsection