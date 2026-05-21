<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Antar Jemput Sekolah</title>

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ICON --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;

            background:
                linear-gradient(135deg,
                    #dbeafe 0%,
                    #e0e7ff 45%,
                    #f5f3ff 100%);

            overflow: hidden;
            position: relative;
        }

        /* BACKGROUND CIRCLE */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.35);
            border-radius: 50%;
            top: -120px;
            left: -120px;
            filter: blur(10px);
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            bottom: -120px;
            right: -120px;
            filter: blur(10px);
        }

        .login-card {
            position: relative;
            z-index: 10;

            width: 100%;
            max-width: 430px;

            border: none;
            border-radius: 28px;

            background: rgba(255, 255, 255, 0.75);

            backdrop-filter: blur(12px);

            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.08);

            overflow: hidden;
        }

        .login-header {
            text-align: center;
            padding: 40px 35px 15px;
        }

        .logo {
            width: 90px;
            height: 90px;
            margin: auto;
            margin-bottom: 20px;

            border-radius: 50%;

            background:
                linear-gradient(135deg,
                    #4e73df,
                    #7c9cff);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 40px;

            color: white;

            box-shadow:
                0 8px 20px rgba(78, 115, 223, 0.3);
        }

        .login-header h2 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #7f8c8d;
            font-size: 15px;
        }

        .login-body {
            padding: 10px 35px 40px;
        }

        .form-label {
            font-weight: 600;
            color: #34495e;
        }

        .input-group {
            border-radius: 14px;
            overflow: hidden;
            margin-top: 8px;
        }

        .input-group-text {
            background: #f1f5ff;
            border: none;
            color: #4e73df;
        }

        .form-control {
            height: 52px;
            border: none;
            background: #f8faff;
        }

        .form-control:focus {
            box-shadow: none;
            background: white;
        }

        .btn-login {
            height: 52px;
            border: none;
            border-radius: 14px;

            background:
                linear-gradient(135deg,
                    #4e73df,
                    #6c8cff);

            font-weight: 600;
            font-size: 16px;

            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 20px rgba(78, 115, 223, 0.25);
        }

        .register-link {
            color: #4e73df;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link:hover {
            color: #375ad3;
        }

        .alert {
            border: none;
            border-radius: 14px;
        }
    </style>

</head>

<body>

    <div class="login-card">

        {{-- HEADER --}}
        <div class="login-header">

            <div class="logo">
                🚐
            </div>

            <h2>
                Selamat Datang
            </h2>

            <p>
                Login ke Sistem Antar Jemput Anak
            </p>

        </div>

        {{-- BODY --}}
        <div class="login-body">

            @if(session('error'))

                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>

            @endif

            <form method="POST" action="/login">

                @csrf

                {{-- EMAIL --}}
                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>

                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>

                    </div>

                </div>

                {{-- PASSWORD --}}
                <div class="mb-4">

                    <label class="form-label">
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>

                        <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                            required>

                    </div>

                </div>

                {{-- BUTTON --}}
                <button class="btn btn-primary btn-login w-100">

                    <i class="bi bi-box-arrow-in-right"></i>
                    Login

                </button>

            </form>

            {{-- FOOTER --}}
            <div class="text-center mt-4">

                Belum punya akun?

                <a href="/register" class="register-link">

                    Register

                </a>

            </div>

        </div>

    </div>

</body>

</html>