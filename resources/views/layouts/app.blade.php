<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Antar Jemput</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-dark px-3">

        <span class="navbar-brand">
            Antar Jemput
        </span>

        <div class="text-white">

            {{ auth()->user()->name }}

            <a href="/logout" class="btn btn-danger btn-sm ms-2">
                Logout
            </a>

        </div>

    </nav>

    <div class="container-fluid">

        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-2 bg-light vh-100 p-3">

                <h3>Menu</h3>

                <ul class="nav flex-column">

                    {{-- SEMUA ROLE --}}
                    <li class="nav-item mb-2">
                        <a href="/dashboard" class="nav-link">
                            Dashboard
                        </a>
                    </li>

                    {{-- PARENT & ADMIN --}}
                    @if(auth()->user()->role == 'parent' || auth()->user()->role == 'admin')

                        <li class="nav-item mb-2">
                            <a href="/kids" class="nav-link">
                                Data Anak
                            </a>
                        </li>

                    @endif

                    {{-- ADMIN --}}
                    @if(auth()->user()->role == 'admin')

                        <li class="nav-item mb-2">
                            <a href="/admin/drivers" class="nav-link">
                                Driver
                            </a>
                        </li>

                        <li class="nav-item mb-2">
                            <a href="/admin/trips" class="nav-link">
                                Assign Driver
                            </a>
                        </li>

                    @endif

                    {{-- DRIVER --}}
                    @if(auth()->user()->role == 'driver')

                        <li class="nav-item mb-2">
                            <a href="/driver/jobs" class="nav-link">
                                Job Driver
                            </a>
                        </li>

                    @endif

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