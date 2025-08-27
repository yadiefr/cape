<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB - SMK PGRI CIKAMPEK')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style-new.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">    <style>        .ppdb-page {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;            
            padding-top: 3.5rem;
            padding-bottom: 3rem;
        }
        
        /* Fix untuk input group alignment */
        .input-group-text {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 50px;
            padding: 0;
        }        .ppdb-card {
            background: #fff;
            color: #333;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
            position: relative;
            z-index: 1;
            margin: 0 auto;
        }
        .ppdb-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .ppdb-subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 30px;
            text-align: center;
        }
        .ppdb-section-header {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 15px 20px;
            font-weight: 600;
            margin: -40px -40px 30px -40px;
        }
        .btn-ppdb {
            background: #4facfe;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s;
            color: white;
        }
        .btn-ppdb:hover {
            background: #00c6ff;
            color: white;
        }
        .btn-secondary-ppdb {
            background: #f8f9fa;
            border: 1px solid #ddd;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s;
            color: #444;
        }
        .btn-secondary-ppdb:hover {
            background: #e9ecef;
            color: #333;
        }
        .form-control {
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }
        .form-hint {
            font-size: 0.8rem;
            color: #777;
            margin-top: 5px;
        }
        .help-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
        }
        .help-section h3 {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .help-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .help-icon {
            background: #e3f2fd;
            color: #4facfe;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .help-text strong {
            display: block;
            color: #4facfe;
            margin-bottom: 5px;
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
        .logo-animate {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .navbar-ppdb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .navbar-brand {
            color: white;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .navbar-nav .nav-link.active {
            background: white;
            color: #4facfe;
        }
    </style>
    @stack('styles')
</head>
<body>    <div class="ppdb-page">
        <div class="wave"></div>
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-ppdb">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">                    @php
                    // Gunakan metode static getValue
                    $logo_sekolah = \App\Models\Settings::getValue('logo_sekolah', null);
                    @endphp
                    <img src="{{ $logo_sekolah ? asset('storage/'.$logo_sekolah) : asset('images/default-logo.png') }}" 
                         alt="Logo SMK" 
                         class="logo-animate"
                         style="max-height: 40px; max-width: 40px;"
                         onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}';">
                    SMK PGRI CIKAMPEK
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPPDB" aria-controls="navbarPPDB" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                  <div class="collapse navbar-collapse" id="navbarPPDB">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                        </li>
                        
                        @auth('pendaftar')
                            <!-- Jika calon siswa sudah login, tampilkan hanya tombol logout -->
                            <li class="nav-item">
                                <form method="POST" action="{{ route('pendaftar.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn nav-link border-0 bg-transparent">Logout</button>
                                </form>
                            </li>
                        @else
                            <!-- Jika belum login, tampilkan menu lengkap -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pendaftar.register') }}">Daftar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pendaftar.login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pendaftaran.check') ? 'active' : '' }}" href="{{ route('pendaftaran.check') }}">Cek Status</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
          <div class="container mt-4">
            <div class="d-flex justify-content-center">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
