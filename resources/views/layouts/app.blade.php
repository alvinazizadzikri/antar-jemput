<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antar Jemput</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Leaflet CSS untuk Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    {{-- Theme Global --}}
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>

<body>

    <div class="app-shell">

        {{-- SIDEBAR --}}
        <aside class="sidebar">

            <div class="sidebar-brand">
                Antar Jemput
            </div>

            <ul class="nav flex-column">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i>
                        Dashboard
                    </a>
                </li>

                {{-- MENU PARENT / ORANG TUA --}}
                @if(auth()->user()->role == 'parent')

                    <li class="nav-item">
                        <a href="/kids" class="nav-link {{ request()->is('kids*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            Data Anak
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/subscriptions" class="nav-link {{ request()->is('subscriptions*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card-fill"></i>
                            Langganan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/riwayat" class="nav-link {{ request()->is('riwayat*') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>
                            Riwayat Antar Jemput
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/izin-anak" class="nav-link {{ request()->is('izin-anak*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-x"></i>
                            Izin Anak
                        </a>
                    </li>

                @endif

                {{-- MENU ADMIN --}}
                @if(auth()->user()->role == 'admin')

                    <li class="nav-item">
                        <a href="/admin/drivers" class="nav-link {{ request()->is('admin/drivers*') ? 'active' : '' }}">
                            <i class="bi bi-truck"></i>
                            Data Sopir
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/admin/trips/create"
                            class="nav-link {{ request()->is('admin/trips/create') ? 'active' : '' }}">
                            <i class="bi bi-person-check"></i>
                            Penugasan Sopir
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/admin/trips" class="nav-link {{ request()->is('admin/trips') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>
                            Riwayat Perjalanan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/admin/transaksi" class="nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                            <i class="bi bi-receipt"></i>
                            Transaksi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/admin/izin-anak" class="nav-link {{ request()->is('admin/izin-anak*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-x"></i>
                            Izin Anak
                        </a>
                    </li>

                @endif

                {{-- MENU SOPIR / DRIVER --}}
                @if(auth()->user()->role == 'driver')

                    <li class="nav-item">
                        <a href="/driver/jobs" class="nav-link {{ request()->is('driver/jobs*') ? 'active' : '' }}">
                            <i class="bi bi-bag-check"></i>
                            Tugas Sopir
                        </a>
                    </li>

                @endif

                {{-- PROFIL --}}
                <li class="nav-item">
                    <a href="/profile" class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        Profil
                    </a>
                </li>

            </ul>

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="main-content flex-grow-1">

            {{-- TOPBAR --}}
            <div class="topbar d-flex justify-content-between align-items-center">

                <div class="topbar-title">

                    @if(request()->is('dashboard'))
                        Dashboard

                    @elseif(request()->is('kids*'))
                        Data Anak

                    @elseif(request()->is('subscriptions/*/payment'))
                        Pembayaran

                    @elseif(request()->is('subscriptions*'))
                        Langganan

                    @elseif(request()->is('riwayat*'))
                        Riwayat Antar Jemput

                    @elseif(request()->is('admin/drivers*'))
                        Data Sopir

                    @elseif(request()->is('admin/trips/create'))
                        Penugasan Sopir

                    @elseif(request()->is('admin/trips'))
                        Riwayat Perjalanan

                    @elseif(request()->is('admin/transaksi*'))
                        Transaksi

                    @elseif(request()->is('driver/jobs*'))
                        Tugas Sopir

                    @elseif(request()->is('profile'))
                        Profil

                    @else
                        Antar Jemput
                    @endif

                </div>

                <div class="d-flex align-items-center gap-3">

                    <span class="fw-semibold">
                        {{ auth()->user()->name ?? 'User' }}
                    </span>

                    <a href="/logout" class="btn btn-danger-custom btn-sm">
                        Logout
                    </a>

                </div>

            </div>

            {{-- ISI HALAMAN --}}
            <div class="page-wrapper">
                @yield('content')
            </div>

        </main>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Leaflet JS untuk Map --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</body>

</html>