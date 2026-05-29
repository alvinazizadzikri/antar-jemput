<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Antar Jemput</title>

    <!-- Bootstrap -->
    <!-- LEAFLET CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
            color: #2c3e50;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: white;
            border-right: 1px solid #e9ecef;
            padding: 25px 18px;
            overflow-y: auto;
        }

        .brand {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #4e73df;
        }

        .sidebar .nav-link {
            color: #34495e;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: 0.3s;
        }

        .sidebar .nav-link:hover {
            background: #edf2ff;
            color: #4e73df;
        }

        .sidebar .nav-link.active {
            background: #4e73df;
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 18px 30px;
            border-bottom: 1px solid #e9ecef;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-title {
            font-size: 22px;
            font-weight: 700;
        }

        .content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .table {
            vertical-align: middle;
        }

        @media(max-width:992px) {

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }

        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="brand">
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

            {{-- PARENT --}}
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

            @endif

            {{-- ADMIN --}}
            @if(auth()->user()->role == 'admin')

                <li class="nav-item">
                    <a href="/admin/drivers" class="nav-link {{ request()->is('admin/drivers*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i>
                        Driver
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/admin/trips" class="nav-link {{ request()->is('admin/trips*') ? 'active' : '' }}">
                        <i class="bi bi-person-check"></i>
                        Assign Driver
                    </a>
                </li>

            @endif

            {{-- DRIVER --}}
            @if(auth()->user()->role == 'driver')

                <li class="nav-item">
                    <a href="/driver/jobs" class="nav-link {{ request()->is('driver/jobs*') ? 'active' : '' }}">
                        <i class="bi bi-bag-check"></i>
                        Job Driver
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

    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">

            <div class="topbar-title">

                @if(request()->is('admin/drivers*'))
                    Driver

                @elseif(request()->is('admin/trips/create'))
                    Assign Driver

                @elseif(request()->is('admin/trips*'))
                    Riwayat Trip

                @elseif(request()->is('driver/jobs*'))
                    Job Driver

                @elseif(request()->is('kids*'))
                    Data Anak

                @elseif(request()->is('subscriptions*'))
                    Langganan

                @elseif(request()->is('profile'))
                    Profil

                @else
                    Dashboard
                @endif

            </div>

            <div class="d-flex align-items-center gap-3">

                <span>
                    {{ auth()->user()->name }}
                </span>

                <a href="/logout" class="btn btn-danger btn-sm">

                    Logout

                </a>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="content">

            @yield('content')

        </div>

    </div>
    <!-- LEAFLET JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</body>

</html>