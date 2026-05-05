<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Antar Jemput</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">Antar Jemput</span>

    <div class="text-white">
        {{ auth()->user()->name }}
        <a href="/logout" class="btn btn-danger btn-sm ms-2">Logout</a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 bg-light vh-100 p-3">
            <h5>Menu</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="/kids" class="nav-link">Data Anak</a>
                </li>
            </ul>
        </div>

        <!-- CONTENT -->
        <div class="col-md-10 p-4">
            @yield('content')
        </div>

    </div>
</div>

</body>
</html>