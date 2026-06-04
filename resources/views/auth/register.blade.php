<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Antar Jemput Sekolah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-body">

    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-people-fill"></i>
            </div>

            <h2>Buat Akun</h2>

            <p>
                Daftar untuk mulai menggunakan layanan
            </p>
        </div>

        <div class="auth-body-content">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/register">
                @csrf

                <div class="mb-3">
                    <label class="auth-label">Nama Lengkap</label>

                    <div class="input-group auth-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-fill"></i>
                        </span>

                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap"
                            required>
                    </div>
                </div>

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
                    <i class="bi bi-person-plus-fill"></i>
                    Register
                </button>
            </form>

            <div class="text-center mt-4">
                Sudah punya akun?
                <a href="/login" class="auth-link">
                    Login
                </a>
            </div>

        </div>

    </div>

</body>

</html>