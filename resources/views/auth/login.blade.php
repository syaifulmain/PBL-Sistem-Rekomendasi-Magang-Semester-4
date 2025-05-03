<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMagang - Sistem Informasi Magang Mahasiswa</title>
    <meta name="description" content="Portal login untuk Sistem Informasi Magang Mahasiswa">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .container-fluid {
            min-height: 100vh;
        }

        .left-panel {
            background-color: #19376d;
            color: white;
            display: flex;
            flex-direction: column;
            width: 50%;
        }
        
        .right-panel {
            background-color: white;
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Header */
        .header {
            padding: 24px;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .logo {
            display: flex;
            align-items: flex-end;
        }
        
        .logo-red {
            background-color: #d9291c;
            width: 20px;
            height: 32px;
        }
        
        .logo-yellow {
            background-color: #f7b731;
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
        
        .logo-text {
            color: white;
            font-size: 32px;
            font-weight: bold;
            margin-left: 12px;
        }
        
        /* Content */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
        }
        
        .illustration {
            width: 80%;
            text-align: center;
        }
        
        .illustration img {
            max-width: 100%;
            height: auto;
        }
        
        /* Footer */
        .footer {
            padding: 24px;
            text-align: center;
            font-size: 14px;
        }
        
        /* Login Form */
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        
        .login-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 24px;
        }
        
        .login-title {
            color: #19376d;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 24px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #f5f5f5;
            font-size: 16px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #19376d;
            box-shadow: 0 0 0 2px rgba(25, 55, 109, 0.2);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .checkbox {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }
        
        .checkbox:checked {
            accent-color: #19376d;
        }
        
        .checkbox-label {
            font-size: 14px;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #19376d;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .btn:hover {
            background-color: #152c5b;
        }
        
        .error-message {
            color: #e53e3e;
            font-size: 14px;
            margin-top: 4px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .left-panel {
                visibility: hidden;
            }
            
            .right-panel {
                width: 100%;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid d-flex m-0 p-0">
        <!-- Left side with illustration -->
        <div class="left-panel">
            <div class="header">
                <!-- SIMagang Logo -->
                <a href="{{ url('/') }}" class="logo-container">
                    <img src="{{ asset('images/jti.png') }}" width="5%" alt="Login illustration">
                    <span class="logo-text">SIMagang</span>
                </a>
            </div>

            <div class="content">
                <div class="illustration">
                    <img src="{{ asset('images/login.png') }}" alt="Login illustration">
                </div>
            </div>

            <div class="footer">
                2025 © Sistem Informasi Magang Mahasiswa
            </div>
        </div>

        <!-- Right side with login form -->
        <div class="right-panel">
            <div class="login-container" style="">
                <div class="login-card">
                    <h1 class="login-title">Log Masuk</h1>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="username" 
                                class="form-control" 
                                placeholder="Username"
                                value="{{ old('username') }}" 
                                required 
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-control" 
                                placeholder="Password" 
                                required
                            >
                        </div>

                        <div class="checkbox-group">
                            <input 
                                type="checkbox" 
                                class="checkbox" 
                                id="showPassword" 
                                onclick="togglePasswordVisibility()"
                            >
                            <label class="checkbox-label" for="showPassword">
                                Tampilkan Password
                            </label>
                        </div>

                        <button type="submit" class="btn">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>
</html>