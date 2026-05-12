<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Antar Jemput Sekolah</title>

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ICON --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- LEAFLET --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #map {
            width: 100%;
            height: 400px;
            border-radius: 15px;
            z-index: 1;
        }

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
            background: #ffffff;
            border-right: 1px solid #e9ecef;
            padding: 25px 18px;
            overflow-y: auto;
        }

        .brand {
            font-size: 24px;
            font-weight: 700;
            color: #34495e;
            margin-bottom: 35px;
        }

        .menu-title {
            font-size: 13px;
            color: #95a5a6;
            text-transform: uppercase;
            margin-bottom: 10px;
            margin-top: 25px;
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
            transform: translateX(3px);
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
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #4e73df;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* CONTENT */
        .content {
            padding: 30px;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            border: none;
            border-radius: 18px 18px 0 0 !important;
            padding: 18px 22px;
            font-weight: 600;
        }

        /* TABLE */
        .table {
            vertical-align: middle;
        }

        .table thead {
            background: #f8f9fc;
        }

        .table th {
            border: none;
            color: #6c757d;
            font-size: 14px;
        }

        .table td {
            border-color: #f1f3f5;
        }

        /* BUTTON */
        .btn {
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 500;
        }

        .btn-primary {
            background: #4e73df;
            border: none;
        }

        .btn-primary:hover {
            background: #375ad3;
        }

        /* FORM */
        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #dfe4ea;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: none;
        }

        textarea.form-control {
            resize: none;
        }

        /* ALERT */
        .alert {
            border: none;
            border-radius: 12px;
        }

        /* IMAGE */
        .img-thumbnail {
            border-radius: 15px;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d6dbe4;
            border-radius: 10px;
        }

        /* MOBILE */
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

    {{-- SIDEBAR --}}
    <div class="sidebar">

        <div class="brand">
            🚐 Antar Jemput
        </div>

        <div class="menu-title">
            Main Menu
        </div>

        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="/kids" class="nav-link {{ request()->is('kids*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    Data Anak
                </a>
            </li>

            <li class="nav-item">
                <a href="/profile" class="nav-link">
                    <i class="bi bi-person-circle"></i>
                    Profil
                </a>
            </li>

        </ul>

    </div>

    {{-- MAIN --}}
    <div class="main">

        {{-- TOPBAR --}}
        <div class="topbar">

            <div class="topbar-title">
                Sistem Antar Jemput Anak Sekolah
            </div>

            <div class="user-box">

                <div class="text-end">

                    <div style="font-weight:600;">
                        {{ auth()->user()->name }}
                    </div>

                    <small class="text-muted">
                        Parent
                    </small>

                </div>

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <a href="/logout" class="btn btn-danger btn-sm">
                    Logout
                </a>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="content">

            @yield('content')

        </div>

    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- LEAFLET --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</body>

</html>