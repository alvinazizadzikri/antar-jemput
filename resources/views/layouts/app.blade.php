<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Antar Jemput</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
            color: #2c3e50;
        }

        /* SIDEBAR */

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: white;
            border-right: 1px solid #e9ecef;
            padding: 25px 18px;
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

        /* MAIN */

        .main {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* TOPBAR */

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

        /* CONTENT */

        .content {
            padding: 30px;
        }

        /* CARD */

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }

        /* TABLE */

        .table {
            vertical-align: middle;
        }

        /* MOBILE */

        @media(max-width:992px){

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

        <!-- DASHBOARD -->

        <li class="nav-item">

            <a href="/dashboard"
               class="nav-link">

                <i class="bi bi-grid"></i>
                Dashboard

            </a>

        </li>

        <!-- PARENT -->

        @if(auth()->user()->role == 'parent')

            <li class="nav-item">

                <a href="/kids"
                   class="nav-link">

                    <i class="bi bi-people"></i>
                    Data Anak

                </a>

            </li>

        @endif

        <!-- ADMIN -->

        @if(auth()->user()->role == 'admin')

            <li class="nav-item">

                <a href="/admin/drivers"
                   class="nav-link">

                    <i class="bi bi-truck"></i>
                    Driver

                </a>

            </li>

            <li class="nav-item">

                <a href="/admin/trips"
                   class="nav-link">

                    <i class="bi bi-pin-map"></i>
                    Assign Driver

                </a>

            </li>

        @endif

        <!-- DRIVER -->

        @if(auth()->user()->role == 'driver')

            <li class="nav-item">

                <a href="/driver/jobs"
                   class="nav-link">

                    <i class="bi bi-briefcase"></i>
                    Job Driver

                </a>

            </li>

        @endif

    </ul>

</div>

<!-- MAIN -->

<div class="main">

    <!-- TOPBAR -->

    <div class="topbar">

        <div class="topbar-title">
            Dashboard
        </div>

        <div>

            <span class="me-3">

                {{ auth()->user()->name }}

            </span>

            <a href="/logout"
               class="btn btn-danger btn-sm">

                Logout

            </a>

        </div>

    </div>

    <!-- CONTENT -->

    <div class="content">

        @yield('content')

    </div>

</div>

</body>

</html>