<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar PPDB - SMK PGRI CIKAMPEK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style-new.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <style>        .auth-page {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 0rem 0 0rem 0;
        }
        .auth-card {
            background: #fff;
            color: #333;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 1;
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
            color: white;
        }
        .btn-auth:hover {
            background: #00c6ff;
            color: white;
        }        .input-group-text {
            background: #4facfe;
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            padding: 0;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-check-label {
            font-size: 0.9rem;
        }
        .logo-animate {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .wave {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.15' d='M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") repeat-x;
            background-size: 1440px 100px;
            z-index: 0;
            animation: wave 15s linear infinite;
            opacity: 0.8;
        }
        @keyframes wave {
            0% { background-position-x: 0; }
            100% { background-position-x: 1440px; }
        }
    </style>
</head>
<body class="auth-page">
    <div class="wave"></div>
    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-card shadow-lg">                  
                <div class="auth-header text-center">
                    @php
                    $logo_sekolah = \App\Models\Settings::getValue('logo_sekolah', null);
                    @endphp                    
                    <img src="{{ $logo_sekolah ? asset('storage/'.$logo_sekolah) : asset('images/default-logo.png') }}" 
                         alt="Logo SMK" 
                         class="mx-auto h-16 w-16 object-contain logo-animate mb-3"
                         style="max-height: 60px; max-width: 60px;"
                         onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}'; console.log('Logo image failed to load');">
                    <h2 class="auth-title">Daftar PPDB</h2>
                </div>

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif                

                <div class="auth-body">                    
                    <form action="{{ route('pendaftar.register.submit') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Username untuk login" required>
                                @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>                            
                            <small class="form-text text-muted">Username ini akan digunakan untuk login</small>
                        </div>

                        <div class="mb-4">
                            <label for="nisn" class="form-label">NISN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" id="nisn" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn') }}" minlength="10" maxlength="10" placeholder="Masukkan NISN" required>
                                @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">NISN akan digunakan sebagai password untuk login. Pastikan NISN terdiri dari 10 angka.</small>
                        </div>

                        <div class="mb-4">
                            <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                <input type="text" id="whatsapp" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp') }}" placeholder="Contoh: 081234567890" required>
                                @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Pastikan nomor WhatsApp aktif. contoh 081234567890</small>
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn btn-auth w-100">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>                    <div class="text-center mt-4">
                        <p class="mb-3">Sudah punya akun?</p>
                        <a href="{{ route('pendaftar.login') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <p class="mb-3">Ingin cek status pendaftaran?</p>
                        <a href="{{ route('pendaftaran.check') }}" class="btn btn-info w-100" style="background-color: #17a2b8; border: none; font-weight: 600; padding: 10px 20px; border-radius: 5px; transition: background 0.3s; color: white;">
                            <i class="fas fa-search me-2"></i>Cek Status Pendaftaran
                        </a>
                    </div>
                </div>

                <div class="auth-footer text-center mt-4">
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke halaman utama
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>
