<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMK PGRI CIKAMPEK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style-new.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <style>
        .auth-page {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: #fff;
            color: #333;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        .auth-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        .auth-link {
            color: #4facfe;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-link:hover {
            text-decoration: underline;
        }
        .btn-auth {
            background: #4facfe;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn-auth:hover {
            background: #00c6ff;
        }
        .input-group-text {
            background: #4facfe;
            color: #fff;
            border: none;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-check-label {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-card shadow-lg">
                <div class="auth-header text-center">
                    <a href="{{ url('/') }}" class="auth-logo">
                    </a>
                    <h2 class="auth-title mt-0">Masuk ke Akun Anda</h2>
                    <p class="auth-subtitle">Masuk untuk mengakses area siswa dan guru</p>
                </div>
                
                <!-- Alert Messages -->
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <div class="auth-body">                    
                    <form method="POST" action="{{ url('/login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="username" class="form-label">Email atau NIS</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan email atau NIS" required>
                                @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label d-flex justify-content-between">
                                <span>Password</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <a href="{{ route('forgot.password') }}" class="auth-link">Lupa Password?</a>
                        </div>
                        
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary btn-auth w-100">Masuk</button>
                        </div>
                    </form>
                </div>
                
                <div class="auth-footer text-center">
                    <p>Informasi lebih lanjut mengenai pendaftaran akun siswa dan guru, silakan hubungi bagian administrasi sekolah.</p>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary mt-1">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.querySelector('#password');
            
            if(togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
