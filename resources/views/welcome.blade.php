<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antar Jemput Sekolah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>

<body class="welcome-body">

    <nav class="welcome-nav">
        <div class="welcome-brand">
            Antar Jemput
        </div>

        <div class="welcome-nav-actions">
            @auth
                <a href="/dashboard" class="welcome-btn welcome-btn-primary">
                    Dashboard
                </a>
            @else
                <a href="/login" class="welcome-btn welcome-btn-light">
                    Login
                </a>

                <a href="/register" class="welcome-btn welcome-btn-primary">
                    Register
                </a>
            @endauth
        </div>
    </nav>

    <section class="welcome-hero">
        <div class="welcome-hero-card">

            <div class="row align-items-center g-5">

                <div class="col-lg-7">

                    <div class="welcome-badge">
                        Sistem Antar Jemput Anak Sekolah
                    </div>

                    <h1 class="welcome-title">
                        Pantau layanan antar jemput anak dengan lebih mudah dan aman.
                    </h1>

                    <p class="welcome-subtitle">
                        Aplikasi ini membantu orang tua, admin, dan sopir dalam mengelola
                        data anak, langganan, pembayaran, penugasan sopir, serta riwayat
                        perjalanan antar jemput sekolah.
                    </p>

                    <div class="welcome-feature">
                        <div class="welcome-feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Data anak lebih terkelola</div>
                            <div class="text-muted">
                                Orang tua dapat menyimpan data anak, sekolah, alamat, dan titik jemput.
                            </div>
                        </div>
                    </div>

                    <div class="welcome-feature">
                        <div class="welcome-feature-icon">
                            <i class="bi bi-credit-card-fill"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Langganan dan pembayaran</div>
                            <div class="text-muted">
                                Sistem mendukung paket harian, mingguan, dan bulanan berbasis hari sekolah.
                            </div>
                        </div>
                    </div>

                    <div class="welcome-feature">
                        <div class="welcome-feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Penugasan sopir</div>
                            <div class="text-muted">
                                Admin dapat menugaskan sopir, dan sopir dapat memperbarui status perjalanan.
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        @auth
                            <a href="/dashboard" class="welcome-btn welcome-btn-primary">
                                Masuk Dashboard
                            </a>
                        @else
                            <a href="/login" class="welcome-btn welcome-btn-primary">
                                Mulai Sekarang
                            </a>

                            <a href="/register" class="welcome-btn welcome-btn-light">
                                Buat Akun
                            </a>
                        @endauth
                    </div>

                </div>

                <div class="col-lg-5">
                    <div class="welcome-illustration">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>
                </div>

            </div>

        </div>
    </section>

</body>

</html>