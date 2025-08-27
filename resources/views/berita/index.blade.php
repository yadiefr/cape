<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita - {{ setting('site_title', 'SMK PGRI CIKAMPEK') }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Berita terbaru dan informasi terkini dari {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}">
    <meta name="keywords" content="berita, news, {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}, pendidikan, sekolah">
    <meta name="author" content="{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Berita - {{ setting('site_title', 'SMK PGRI CIKAMPEK') }}">
    <meta property="og:description" content="Berita terbaru dan informasi terkini dari {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(setting('logo_sekolah'))
    <meta property="og:image" content="{{ asset('storage/' . setting('logo_sekolah')) }}">
    @endif
    
    <!-- Favicon -->
    @if(setting('site_favicon'))
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive-utilities.css') }}" rel="stylesheet">
    
    <style>
        /* Page Header Styles */
        .page-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 50%, #7c3aed 100%);
            padding: 6rem 0 4rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px), 
                              radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
            background-size: 100px 100px;
            background-position: 0 0, 50px 50px;
        }
        
        .page-header .container-custom {
            position: relative;
            z-index: 2;
        }
        
        .page-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .breadcrumb-nav {
            margin-top: 2rem;
        }
        
        .breadcrumb {
            background: rgba(255,255,255,0.1);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            margin: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.7);
        }
        
        .breadcrumb-item a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-item a:hover {
            color: white;
        }
        
        .breadcrumb-item.active {
            color: white;
        }
        
        /* Berita Grid Styles */
        .berita-section {
            padding: 5rem 0;
            background: #f8fafc;
        }
        
        .berita-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .berita-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: fit-content;
        }
        
        .berita-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        
        .berita-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .berita-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .berita-card:hover .berita-image img {
            transform: scale(1.05);
        }
        
        .berita-date-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(59, 130, 246, 0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        .berita-content {
            padding: 2rem;
        }
        
        .berita-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .berita-excerpt {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .berita-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .berita-date {
            display: flex;
            align-items: center;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .berita-date i {
            margin-right: 0.5rem;
            color: #3b82f6;
        }
        
        .berita-link {
            display: inline-flex;
            align-items: center;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .berita-link:hover {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            transform: translateY(-2px);
        }
        
        .berita-link i {
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }
        
        .berita-link:hover i {
            transform: translateX(5px);
        }
        
        /* Search and Filter Bar */
        .search-filter-bar {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .search-form {
            display: flex;
            gap: 1rem;
            align-items: end;
        }
        
        .search-input {
            flex: 1;
        }
        
        .search-input input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .search-input input:focus {
            border-color: #3b82f6;
            outline: none;
        }
        
        .search-btn {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        /* Pagination Styles */
        .berita-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 4rem;
            gap: 0.5rem;
        }
        
        .pagination {
            margin: 0;
        }
        
        .page-link {
            color: #3b82f6;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            margin: 0 0.25rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            transform: translateY(-2px);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-color: #3b82f6;
            color: white;
        }
        
        .page-item.disabled .page-link {
            color: #94a3b8;
            border-color: #e2e8f0;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 0;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 2rem;
            color: #cbd5e1;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #475569;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .berita-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .search-form {
                flex-direction: column;
                gap: 1rem;
            }
            
            .berita-pagination {
                margin-top: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .page-header {
                padding: 4rem 0 3rem;
            }
            
            .berita-section {
                padding: 3rem 0;
            }
            
            .berita-content {
                padding: 1.5rem;
            }
            
            .search-filter-bar {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container-custom header-container">
            <a href="{{ url('/') }}" class="logo">
                @if(setting('logo_sekolah'))
                    <img src="{{ asset('storage/' . setting('logo_sekolah')) }}" alt="{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}" style="height: 40px; width: auto; margin-right: 10px;">
                @else
                    <i class="fas fa-graduation-cap logo-icon"></i>
                @endif
                {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}
            </a>
            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
            <ul class="nav-menu">
                <li><a href="{{ url('/') }}" class="nav-link">Beranda</a></li>
                <li><a href="{{ url('/') }}#features" class="nav-link">Keunggulan</a></li>
                <li><a href="{{ url('/') }}#programs" class="nav-link">Program Keahlian</a></li>
                <li><a href="{{ route('berita.index') }}" class="nav-link active">Berita</a></li>
                <li><a href="{{ route('galeri.index') }}" class="nav-link">Galeri</a></li>
                <li><a href="/login" class="btn btn-primary">Login</a></li>
            </ul>
        </div>
    </header>
    
    <!-- Page Header -->
    <section class="page-header">
        <div class="container-custom">
            <div class="text-center" data-aos="fade-up">
                <h1 class="page-title">Berita & Informasi</h1>
                <p class="page-subtitle">Dapatkan berita terbaru dan informasi terkini dari {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}</p>
                
                <nav class="breadcrumb-nav" data-aos="fade-up" data-aos-delay="100">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Berita</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    
    <!-- Berita Section -->
    <section class="berita-section">
        <div class="container-custom">
            <!-- Search and Filter Bar -->
            <div class="search-filter-bar" data-aos="fade-up">
                <form class="search-form" method="GET" action="{{ route('berita.index') }}">
                    <div class="search-input">
                        <label for="search" class="form-label fw-semibold">Cari Berita</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               placeholder="Masukkan kata kunci..."
                               value="{{ request('search') }}"
                               class="form-control">
                    </div>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </form>
            </div>

            <!-- Berita Grid -->
            @if($berita->count() > 0)
                <div class="berita-grid">
                    @foreach($berita as $item)
                        <div class="berita-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="berita-image">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}">
                                @else
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #e2e8f0, #cbd5e1); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-newspaper" style="font-size: 3rem; color: #94a3b8;"></i>
                                    </div>
                                @endif
                                <div class="berita-date-badge">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </div>
                            </div>
                            
                            <div class="berita-content">
                                <h3 class="berita-title">{{ $item->judul }}</h3>
                                
                                <div class="berita-meta">
                                    <div class="berita-date">
                                        <i class="fas fa-clock"></i>
                                        {{ $item->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                
                                <p class="berita-excerpt">{{ Str::limit(strip_tags($item->isi), 120) }}</p>
                                
                                <a href="{{ route('berita.show', $item->id) }}" class="berita-link">
                                    Baca Selengkapnya
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($berita->hasPages())
                    <div class="berita-pagination" data-aos="fade-up">
                        {{ $berita->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state" data-aos="fade-up">
                    <i class="fas fa-newspaper"></i>
                    <h3>Belum Ada Berita</h3>
                    <p>{{ request('search') ? 'Tidak ada berita yang sesuai dengan pencarian "' . request('search') . '"' : 'Belum ada berita yang dipublikasikan. Silakan kembali lagi nanti.' }}</p>
                    @if(request('search'))
                        <a href="{{ route('berita.index') }}" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Lihat Semua Berita
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-custom">
            <div class="footer-main" data-aos="fade-up">
                <div class="footer-info">
                    <a href="{{ url('/') }}" class="footer-logo">
                        @if(setting('logo_sekolah'))
                            <img src="{{ asset('storage/' . setting('logo_sekolah')) }}" alt="{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}" style="height: 40px; width: auto; margin-right: 10px;">
                        @endif
                        {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}
                    </a>
                    <p class="footer-description">{{ setting('site_description', 'Mempersiapkan generasi muda untuk menjadi talenta digital berkualitas yang siap menghadapi tantangan industri teknologi masa depan.') }}</p>
                    <div class="footer-contact">
                        @if(setting('alamat_sekolah'))
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ setting('alamat_sekolah') }}</span>
                        </div>
                        @endif
                        
                        @if(setting('telepon_sekolah'))
                        <div class="contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <span>{{ setting('telepon_sekolah') }}</span>
                        </div>
                        @endif
                        
                        @if(setting('email_sekolah'))
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ setting('email_sekolah') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-social" data-aos="fade-up" data-aos-delay="100">
                    @if(setting('facebook_url'))
                    <a href="{{ setting('facebook_url') }}" class="social-link" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    
                    @if(setting('instagram_url'))
                    <a href="{{ setting('instagram_url') }}" class="social-link" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    @endif
                    
                    @if(setting('youtube_url'))
                    <a href="{{ setting('youtube_url') }}" class="social-link" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    @endif
                    
                    @if(setting('whatsapp_number'))
                    <a href="https://wa.me/{{ setting('whatsapp_number') }}" class="social-link" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    @endif
                </div>
                
                <div class="footer-copyright" data-aos="fade-up" data-aos-delay="200">
                    &copy; {{ date('Y') }} {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}. Semua hak dilindungi.
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="{{ asset('js/script-new.js') }}"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 50
        });
        
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navMenu = document.querySelector('.nav-menu');
            
            if (mobileMenuBtn && navMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                });
            }
        });
    </script>
</body>
</html>
