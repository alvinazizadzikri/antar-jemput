@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3 class="fw-bold">Dashboard Driver</h3>
        <p class="text-muted">Ringkasan pekerjaan antar jemput</p>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p>Total Job</p>
                        <h2 class="fw-bold text-primary">{{ $totalJobs ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p>Job Aktif</p>
                        <h2 class="fw-bold text-success">{{ $activeJobs ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="fw-bold mb-0">Daftar Job</h5>
            </div>

            <div class="card-body">
                @forelse($jobs as $job)
                    <div class="border rounded p-3 mb-3">
                        <h5 class="fw-bold">{{ $job->kid->name ?? '-' }}</h5>
                        <p class="mb-1">{{ $job->kid->school_name ?? '-' }}</p>
                        <p class="mb-1">{{ $job->kid->address ?? '-' }}</p>
                        <span class="badge bg-primary">{{ $job->status }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center">Belum ada job driver</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection