@extends('layouts.app')

@section('content')

    <div class="section-header">
        <div>
            <div class="page-title">Dashboard Sopir</div>
            <div class="page-subtitle">
                Ringkasan pekerjaan antar jemput anak
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Total Tugas</div>
                        <h2 class="stat-card-value">{{ $totalJobs ?? 0 }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-list-task"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-card-label">Tugas Aktif</div>
                        <h2 class="stat-card-value text-success">{{ $activeJobs ?? 0 }}</h2>
                    </div>

                    <div class="stat-card-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="page-card">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Daftar Tugas</h5>

            @forelse($jobs as $job)
                <div class="info-list-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="fw-bold mb-1">
                                {{ $job->kid->name ?? '-' }}
                            </h5>

                            <p class="mb-1 text-muted">
                                {{ $job->kid->school_name ?? '-' }}
                            </p>

                            <p class="mb-0">
                                {{ $job->kid->address ?? '-' }}
                            </p>
                        </div>

                        <span class="badge-status badge-assigned">
                            {{ $job->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center mb-0 py-4">
                    Belum ada tugas sopir
                </p>
            @endforelse

        </div>
    </div>

@endsection