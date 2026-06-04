@extends('layouts.app')

@section('content')

    <h3 class="mb-3">Job Driver</h3>

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>Anak</th>
                            <th>Orang Tua</th>
                            <th>Alamat</th>
                            <th>Langganan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($trips as $trip)

                            <tr>

                                <td>
                                    {{ $trip->kid->name }}
                                </td>

                                <td>
                                    {{ $trip->kid->parent->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $trip->kid->address }}
                                </td>

                                <td>

                                    @if($trip->kid->subscription)

                                        {{ $trip->kid->subscription->package_name }}

                                        <br>

                                        <span class="badge bg-success">
                                            {{ $trip->kid->subscription->status }}
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Belum Langganan
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($trip->status == 'assigned')

                                        <span class="badge bg-secondary">
                                            Ditugaskan
                                        </span>

                                    @elseif($trip->status == 'on_pickup')

                                        <span class="badge bg-warning text-dark">
                                            Menuju Jemput
                                        </span>

                                    @elseif($trip->status == 'picked')

                                        <span class="badge bg-primary">
                                            Dijemput
                                        </span>

                                    @elseif($trip->status == 'on_delivery')

                                        <span class="badge bg-success">
                                            Diantar
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <form action="/driver/jobs/{{ $trip->id }}/status" method="POST">

                                        @csrf
                                        @method('PUT')

                                        <select name="status" class="form-control mb-2">

                                            <option value="assigned" {{ $trip->status == 'assigned' ? 'selected' : '' }}>
                                                Ditugaskan
                                            </option>

                                            <option value="on_pickup" {{ $trip->status == 'on_pickup' ? 'selected' : '' }}>
                                                Menuju Jemput
                                            </option>

                                            <option value="picked" {{ $trip->status == 'picked' ? 'selected' : '' }}>
                                                Dijemput
                                            </option>

                                            <option value="on_delivery" {{ $trip->status == 'on_delivery' ? 'selected' : '' }}>
                                                Diantar
                                            </option>

                                        </select>

                                        <button class="btn btn-primary btn-sm w-100">
                                            Update Status
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection