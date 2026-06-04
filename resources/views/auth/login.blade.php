<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Antar Jemput Sekolah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-body">

    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-bus-front-fill"></i>
            </div>

            <h2>Selamat Datang</h2>

            <p>
                Login ke sistem antar jemput anak sekolah
            </p>
        </div>

        <div class="auth-body-content">

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="mb-3">
                    <label class="auth-label">Email</label>

                    <div class="input-group auth-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>

                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="auth-label">Password</label>

                    <div class="input-group auth-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>

                        <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                            required>
                    </div>
                </div>

                <button class="btn auth-btn w-100">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login
                </button>
            </form>

            <div class="text-center mt-4">
                Belum punya akun?
                <a href="/register" class="auth-link">
                    Register
                </a>
            </div>

        </div>

    </div>

</body>

</html>