<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ setting('site_title') }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ setting('site_description', 'SMK PGRI CIKAMPEK menyediakan pendidikan berkualitas dengan fasilitas modern dan tenaga pengajar profesional') }}">
    <meta name="keywords" content="{{ setting('site_keywords', 'smk, sekolah kejuruan, cikampek, pendidikan, teknologi') }}">
    <meta name="author" content="{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ setting('site_title', 'SMK PGRI CIKAMPEK') }}">
    <meta property="og:description" content="{{ setting('site_description', 'Pendidikan Berkualitas untuk Masa Depan') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
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
    <link href="{{ asset('css/style-new.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
      
    <!-- Header -->
    <header>
        <div class="container-custom header-container">
            <a href="#" class="logo">
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
                <li><a href="#programs" class="nav-link">Program Keahlian</a></li>
                <li><a href="#features" class="nav-link">Keunggulan</a></li>
                <li><a href="#achievements" class="nav-link">Prestasi</a></li>
                <li><a href="#calendar" class="nav-link">Agenda</a></li>
                <li><a href="#gallery" class="nav-link">Galeri</a></li>                
                <li><a href="#news" class="nav-link">Berita</a></li>
                <li><a href="{{ route('pendaftaran.index') }}" class="cta-button">Daftar PPDB</a></li>
                <li><a href="/login" class="btn btn-primary">Login</a></li>
                <li class="search-toggle">
                    <button class="search-toggle-btn" aria-label="Cari">
                        <i class="fas fa-search"></i>
                    </button>
                </li>
            </ul>
        </div>
        <div class="search-overlay">
            <div class="container-custom">
                <form class="search-form">
                    <input type="text" class="search-input" placeholder="Cari informasi...">
                    <button type="submit" class="search-submit" aria-label="Cari">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <button class="search-close" aria-label="Tutup pencarian">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container-custom">
            <div class="hero-grid">
                @php
                    $activeBanner = \App\Models\HeroBanner::where('is_active', true)->latest()->first();
                @endphp

                @if($activeBanner)
                    <div class="hero-content" data-aos="fade-right" data-aos-duration="1000">
                        <div class="hero-badge">
                            <i class="fas fa-check-circle"></i> {{ $activeBanner->subtitle }}
                        </div>
                        <h1 class="hero-title">{!! $activeBanner->title !!}</h1>
                        <p class="hero-description">{{ $activeBanner->description }}</p>
                        <div class="hero-cta">
                            <a href="{{ $activeBanner->button_url }}" class="hero-cta-primary">{{ $activeBanner->button_text }}</a>
                            <a href="#programs" class="hero-cta-secondary">
                                <i class="fas fa-play-circle"></i> <span class="d-none d-sm-inline">Jelajahi </span>Program
                            </a>
                        </div>
                    </div>
                    <div class="hero-image" data-aos="fade-left" data-aos-duration="1000" style="background: none !important; filter: none !important;">
                        <img src="{{ $activeBanner->image_url }}" alt="{{ $activeBanner->title }}" style="filter: none !important; -webkit-filter: none !important; opacity: 1 !important; mix-blend-mode: normal !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important; background: none !important; box-shadow: none !important; object-fit: cover; width: 100%; height: 100%;">
                    </div>
                @else
                    <div class="hero-content" data-aos="fade-right" data-aos-duration="1000">
                        <div class="hero-badge">
                            <i class="fas fa-check-circle"></i> Pendaftaran TA 2025/2026 Dibuka
                        </div>
                        <h1 class="hero-title">Siapkan Masa Depan Kamu Di <span>{{ setting('nama_sekolah') }}</span></h1>
                        <p class="hero-description">{{ setting('site_description', 'SMK PGRI CIKAMPEK menyediakan pendidikan yang relevan dengan kebutuhan industri teknologi saat ini, didukung oleh fasilitas terbaik dan pengajar profesional untuk mengembangkan potensi siswa.') }}</p>
                        <div class="hero-cta">
                            <a href="{{ route('pendaftar.register') }}" class="hero-cta-primary">Daftar Sekarang</a>
                            <a href="#programs" class="hero-cta-secondary">
                                <i class="fas fa-play-circle"></i> <span class="d-none d-sm-inline">Jelajahi </span>Program
                            </a>
                        </div>
                    </div>
                    <div class="hero-image" data-aos="fade-left" data-aos-duration="1000">
                        <div style="width: 100%; height: 100%; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #495057; font-size: 2rem; text-align: center; padding: 2rem; border-radius: 10px;">
                            <div>
                                <i class="fas fa-graduation-cap" style="font-size: 4rem; margin-bottom: 1rem; display: block; color: #007bff;"></i>
                                <div style="font-weight: 600; color: #343a40;">SMK PGRI CIKAMPEK</div>
                                <div style="font-size: 1rem; margin-top: 0.5rem; color: #6c757d;">Pendidikan Berkualitas</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>    
    
    <!-- Features Section -->
    <section class="features with-bg-pattern" id="features">
        <div class="container-custom">
            <div class="section-header" data-aos="fade-up" data-aos-duration="800">
                <div class="section-subtitle">KEUNGGULAN KAMI</div>
                <h2 class="section-title">Mengapa Memilih {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}?</h2>
                <p class="section-description">Kami menawarkan pendidikan komprehensif dengan dukungan teknologi terbaru dan kerjasama industri untuk mempersiapkan siswa menghadapi dunia kerja digital.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="feature-title">Kurikulum Berbasis Industri</h3>
                    <p class="feature-description">Kurikulum kami dirancang bersama partner industri teknologi terkemuka untuk memastikan relevansi dengan kebutuhan dunia kerja.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3 class="feature-title">Fasilitas</h3>
                    <p class="feature-description">Lab komputer dengan spesifikasi tinggi, studio multimedia, ruang praktik, dan kelas yang dilengkapi teknologi terkini untuk pengalaman belajar optimal.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="feature-title">Kerjasama Industri</h3>
                    <p class="feature-description">Kemitraan dengan perusahaan teknologi terkemuka untuk program magang, sertifikasi profesional, dan rekrutmen langsung setelah lulus.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="feature-title">Pengajar Berpengalaman</h3>
                    <p class="feature-description">Tim pengajar kami adalah praktisi industri dengan pengalaman nyata yang membawa pengetahuan dan keterampilan terkini ke dalam kelas.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 class="feature-title">Sertifikasi Internasional</h3>
                    <p class="feature-description">Siswa memiliki kesempatan mendapatkan sertifikasi internasional seperti Microsoft, Cisco, Oracle, dan Google yang diakui di seluruh dunia.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3 class="feature-title">Program Pertukaran</h3>
                    <p class="feature-description">Kesempatan mengikuti program pertukaran dengan sekolah teknologi di luar negeri untuk memperluas wawasan global.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Programs Section -->
    <section class="programs" id="programs">
        <div class="container-custom">
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle">PROGRAM KEAHLIAN</div>
                <h2 class="section-title">Program Unggulan Kami</h2>
                <p class="section-description">Pilih jurusan yang sesuai dengan minat dan bakatmu untuk mempersiapkan karir teknologi masa depan.</p>
            </div>
            <div class="programs-grid">
                @foreach($jurusan as $jur)
                <div class="program-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    @if(file_exists(public_path('images/jurusan/'.$jur->kode_jurusan.'.jpg')))
                        <img src="{{ asset('images/jurusan/'.$jur->kode_jurusan.'.jpg') }}" class="program-image" alt="{{ $jur->nama_jurusan }}">
                    @else
                        <div class="feature-icon" style="width: 100%; height: 240px; margin-bottom: 0; font-size: 3rem; border-radius: 0;">
                            <i class="fas fa-microchip"></i>
                        </div>
                    @endif
                    <div class="program-content">
                        <h3 class="program-title">{{ $jur->nama_jurusan }}</h3>
                        <p class="program-description">{{ Str::limit($jur->deskripsi, 120) }}</p>
                        <a href="{{ url('jurusan/'.$jur->id) }}" class="program-link">
                            Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Calendar & Events Section -->
    <section class="calendar-events" id="calendar">
        <div class="container-custom">
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle">AGENDA KEGIATAN</div>
                <h2 class="section-title">Kalender Akademik</h2>
                <p class="section-description">Jadwal kegiatan dan acara penting SMK PGRI CIKAMPEK untuk periode tahun ajaran 2025/2026.</p>
            </div>
            
            <div class="calendar-container" data-aos="fade-up">
                <div class="calendar">
                    <div class="calendar-header">
                        Kalender Akademik 2025
                    </div>
                    <div class="calendar-month">
                        <div class="month-name">Mei 2025</div>
                        <div class="calendar-nav">
                            <button class="prev-month"><i class="fas fa-chevron-left"></i></button>
                            <button class="next-month"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="calendar-days">
                        <div class="calendar-day-name">Min</div>
                        <div class="calendar-day-name">Sen</div>
                        <div class="calendar-day-name">Sel</div>
                        <div class="calendar-day-name">Rab</div>
                        <div class="calendar-day-name">Kam</div>
                        <div class="calendar-day-name">Jum</div>
                        <div class="calendar-day-name">Sab</div>
                        
                        <div class="calendar-date other-month">27</div>
                        <div class="calendar-date other-month">28</div>
                        <div class="calendar-date other-month">29</div>
                        <div class="calendar-date other-month">30</div>
                        <div class="calendar-date">1</div>
                        <div class="calendar-date">2</div>
                        <div class="calendar-date">3</div>
                        
                        <div class="calendar-date">4</div>
                        <div class="calendar-date has-event">5</div>
                        <div class="calendar-date">6</div>
                        <div class="calendar-date">7</div>
                        <div class="calendar-date">8</div>
                        <div class="calendar-date">9</div>
                        <div class="calendar-date">10</div>
                        
                        <div class="calendar-date">11</div>
                        <div class="calendar-date">12</div>
                        <div class="calendar-date has-event">13</div>
                        <div class="calendar-date">14</div>
                        <div class="calendar-date">15</div>
                        <div class="calendar-date has-event">16</div>
                        <div class="calendar-date">17</div>
                        
                        <div class="calendar-date">18</div>
                        <div class="calendar-date">19</div>
                        <div class="calendar-date current">20</div>
                        <div class="calendar-date has-event">21</div>
                        <div class="calendar-date">22</div>
                        <div class="calendar-date">23</div>
                        <div class="calendar-date">24</div>
                        
                        <div class="calendar-date">25</div>
                        <div class="calendar-date has-event">26</div>
                        <div class="calendar-date">27</div>
                        <div class="calendar-date">28</div>
                        <div class="calendar-date">29</div>
                        <div class="calendar-date">30</div>
                        <div class="calendar-date">31</div>
                    </div>
                </div>
                
                <div class="upcoming-events">
                    <h3 class="upcoming-events-title">Acara Mendatang</h3>
                    
                    <div class="event-card">
                        <div class="event-date">
                            <div class="event-day">21</div>
                            <div class="event-month">Mei</div>
                        </div>
                        <div class="event-content">
                            <h4 class="event-title">Workshop Teknologi AI</h4>
                            <div class="event-time">
                                <i class="far fa-clock"></i> 09:00 - 15:00 WIB
                            </div>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Aula Utama SMK PGRI CIKAMPEK
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-card">
                        <div class="event-date">
                            <div class="event-day">26</div>
                            <div class="event-month">Mei</div>
                        </div>
                        <div class="event-content">
                            <h4 class="event-title">Kunjungan Industri ke Tech Park</h4>
                            <div class="event-time">
                                <i class="far fa-clock"></i> 08:00 - 17:00 WIB
                            </div>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Bandung Tech Park
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-card">
                        <div class="event-date">
                            <div class="event-day">5</div>
                            <div class="event-month">Jun</div>
                        </div>
                        <div class="event-content">
                            <h4 class="event-title">Seminar Peluang Karir di Industri Digital</h4>
                            <div class="event-time">
                                <i class="far fa-clock"></i> 13:00 - 16:00 WIB
                            </div>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Ruang Seminar Lt. 3
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-card">
                        <div class="event-date">
                            <div class="event-day">13</div>
                            <div class="event-month">Jun</div>
                        </div>
                        <div class="event-content">
                            <h4 class="event-title">Pameran Proyek Akhir Siswa</h4>
                            <div class="event-time">
                                <i class="far fa-clock"></i> 09:00 - 16:00 WIB
                            </div>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Gedung Serbaguna SMK PGRI CIKAMPEK
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="achievements" id="achievements">
        <div class="container-custom">
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle">PRESTASI KAMI</div>
                <h2 class="section-title">Prestasi Terbaru Siswa</h2>
                <p class="section-description">Siswa-siswi kami telah meraih berbagai prestasi di tingkat nasional maupun internasional di bidang teknologi dan inovasi.</p>
            </div>
            
            <div class="achievements-carousel" data-aos="fade-up">
                <div class="carousel-container">
                    <div class="carousel-track">
                        <div class="achievement-card">
                            <div class="achievement-image">
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-robot" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <div class="achievement-content">
                                <div class="achievement-badge">Robotika</div>
                                <h3 class="achievement-title">Juara 1 Lomba Robotik Nasional 2025</h3>
                                <p class="achievement-description">Tim robotik kami berhasil menjuarai kompetisi robotik tingkat nasional dengan robot inovatif yang mampu mengatasi berbagai rintangan.</p>
                            </div>
                        </div>
                        
                        <div class="achievement-card">
                            <div class="achievement-image">
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-code" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <div class="achievement-content">
                                <div class="achievement-badge">Pemrograman</div>
                                <h3 class="achievement-title">Finalis International Coding Olympics</h3>
                                <p class="achievement-description">Tiga siswa kami berhasil melaju ke babak final International Coding Olympics di Singapura, mengharumkan nama Indonesia.</p>
                            </div>
                        </div>
                        
                        <div class="achievement-card">
                            <div class="achievement-image">
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-palette" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <div class="achievement-content">
                                <div class="achievement-badge">Desain</div>
                                <h3 class="achievement-title">Juara 2 UI/UX Design Competition</h3>
                                <p class="achievement-description">Karya inovatif antarmuka aplikasi kesehatan dari tim desain kami mendapatkan pengakuan dari juri internasional.</p>
                            </div>
                        </div>
                        
                        <div class="achievement-card">
                            <div class="achievement-image">
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-laptop-code" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <div class="achievement-content">
                                <div class="achievement-badge">Hackathon</div>
                                <h3 class="achievement-title">Juara 1 Hackathon Pendidikan Nasional</h3>
                                <p class="achievement-description">Aplikasi pembelajaran adaptif karya tim kami menjadi solusi terbaik untuk meningkatkan kualitas pendidikan di daerah tertinggal.</p>
                            </div>
                        </div>
                        
                        <div class="achievement-card">
                            <div class="achievement-image">
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-wifi" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <div class="achievement-content">
                                <div class="achievement-badge">Internet of Things</div>
                                <h3 class="achievement-title">Penghargaan Inovasi IoT Terbaik</h3>
                                <p class="achievement-description">Sistem monitoring kualitas air berbasis IoT karya siswa kami mendapat penghargaan dari Kementerian Lingkungan Hidup.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="carousel-nav">
                    <button class="carousel-button carousel-prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="carousel-button carousel-next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>    
    
    <!-- News Section -->
    <section class="news" id="news">
        <div class="container-custom">
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle">BERITA & PENGUMUMAN</div>
                <h2 class="section-title">Informasi Terbaru</h2>
                <p class="section-description">Dapatkan informasi terkini tentang kegiatan dan pengumuman penting dari SMK PGRI CIKAMPEK.</p>
            </div>
            
            <div class="news-filter" data-aos="fade-up">
                <button class="news-filter-btn active" data-filter="all">Semua</button>
                <button class="news-filter-btn" data-filter="berita">Berita</button>
                <button class="news-filter-btn" data-filter="pengumuman">Pengumuman</button>
                <button class="news-filter-btn" data-filter="kegiatan">Kegiatan</button>
            </div>
            
            <div class="news-grid">
                @foreach($pengumuman as $p)
                <div class="news-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="news-image">
                        <div class="news-category">Pengumuman</div>
                        @if($p->lampiran)
                            <img src="{{ asset('storage/' . $p->lampiran) }}" alt="{{ $p->judul }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="news-content">
                        <div class="news-date">
                            <i class="fas fa-calendar-alt"></i> {{ $p->tanggal_mulai->format('d M Y') }}
                        </div>
                        <h3 class="news-title">{{ $p->judul }}</h3>
                        <p class="news-description">{{ Str::limit($p->isi, 100) }}</p>
                        <a href="{{ url('pengumuman/'.$p->id) }}" class="news-link">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach

                @foreach($berita as $b)
                <div class="news-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="news-image">
                        <div class="news-category">Berita</div>
                        @if($b->lampiran)
                            <img src="{{ asset('storage/' . $b->lampiran) }}" alt="{{ $b->judul }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="news-content">
                        <div class="news-date">
                            <i class="fas fa-calendar-alt"></i> {{ $b->created_at->format('d M Y') }}
                        </div>
                        <h3 class="news-title">{{ $b->judul }}</h3>
                        <p class="news-description">{{ Str::limit($b->isi, 100) }}</p>
                        <a href="{{ url('berita/'.$b->id) }}" class="news-link">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="news-pagination" data-aos="fade-up">
                <a href="#" class="pagination-arrow disabled"><i class="fas fa-chevron-left"></i></a>
                <a href="#" class="pagination-number active">1</a>
                <a href="#" class="pagination-number">2</a>
                <a href="#" class="pagination-number">3</a>
                <a href="#" class="pagination-arrow"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery" id="gallery">
        <div class="container-custom">
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle">GALERI KAMI</div>
                <h2 class="section-title">Momen & Fasilitas</h2>
                <p class="section-description">Lihat koleksi foto kegiatan, fasilitas, dan kehidupan siswa di SMK PGRI CIKAMPEK.</p>
            </div>
            
            <div class="gallery-filter" data-aos="fade-up">
                <button class="gallery-filter-btn active" data-filter="all">Semua</button>
                <button class="gallery-filter-btn" data-filter="facilities">Fasilitas</button>
                <button class="gallery-filter-btn" data-filter="activities">Kegiatan</button>
                <button class="gallery-filter-btn" data-filter="competitions">Kompetisi</button>
                <button class="gallery-filter-btn" data-filter="campus">Sekolah</button>
            </div>
            
            <div class="gallery-grid" data-aos="fade-up">
                @forelse($galeri as $item)
                <div class="gallery-item" data-category="{{ $item->kategori }}">
                    <img src="{{ asset('uploads/galeri/' . $item->gambar) }}" alt="{{ $item->judul }}">
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h3>{{ $item->judul }}</h3>
                            <p>{{ Str::limit($item->deskripsi, 100) }}</p>
                            <div class="gallery-actions">
                                <button class="gallery-view-btn" onclick="openSimpleGalleryModal({{ $item->id }}, '{{ str_replace("'", "\\'", $item->judul) }}', '{{ str_replace("'", "\\'", $item->deskripsi) }}')" title="Lihat semua foto">
                                    <i class="fas fa-images"></i>
                                </button>
                                <a href="{{ asset('uploads/galeri/' . $item->gambar) }}" class="gallery-zoom" target="_blank" title="Perbesar foto utama">
                                    <i class="fas fa-search-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-16 text-gray-400">
                    <i class="fas fa-images text-6xl mb-4"></i>
                    <div class="text-lg">Belum ada foto di galeri</div>
                </div>
                @endforelse
            </div>
            
            <!-- Simple Gallery Detail Modal -->
            <div class="simple-gallery-modal" id="simpleGalleryModal">
                <div class="simple-gallery-content">
                    <div class="simple-gallery-header">
                        <h3 id="simpleGalleryTitle">Judul Galeri</h3>
                        <button class="simple-gallery-close" onclick="closeSimpleGalleryModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="simple-gallery-description" id="simpleGalleryDescription">
                        Deskripsi galeri
                    </div>
                    <div class="simple-gallery-photos" id="simpleGalleryPhotos">
                        <!-- Photos will be loaded here -->
                    </div>
                    <div class="simple-gallery-footer">
                        <button class="btn-close-simple" onclick="closeSimpleGalleryModal()">
                            <i class="fas fa-times mr-2"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="testimonials" id="testimonials">
        <div class="container-custom">
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle">TESTIMONI</div>
                <h2 class="section-title">Pengalaman Bersama Kami</h2>
                <p class="section-description">Pendapat siswa, alumni, dan orang tua tentang perjalanan pendidikan di SMK PGRI CIKAMPEK.</p>
            </div>
            
            <div class="testimonial-slider" data-aos="fade-up">
                <div class="testimonial-track">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <i class="fas fa-quote-left testimonial-quote"></i>
                            <p class="testimonial-text">SMK PGRI CIKAMPEK memberikan saya bekal keterampilan yang sangat relevan dengan dunia kerja. Setelah lulus, saya langsung mendapatkan pekerjaan di perusahaan teknologi yang saya impikan.</p>
                            <div class="testimonial-author">
                                <div class="testimonial-author-img">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Alumni">
                                </div>
                                <div class="testimonial-author-info">
                                    <h4 class="testimonial-name">Andi Pratama</h4>
                                    <p class="testimonial-position">Alumni 2023 - Software Engineer di TechCorp</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <i class="fas fa-quote-left testimonial-quote"></i>
                            <p class="testimonial-text">Sebagai orang tua, saya sangat puas dengan perkembangan anak saya di SMK PGRI CIKAMPEK. Fasilitas lengkap, guru kompeten, dan lingkungan belajar digital membuat anak saya berkembang pesat.</p>
                            <div class="testimonial-author">
                                <div class="testimonial-author-img">
                                    <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Orang Tua Siswa">
                                </div>
                                <div class="testimonial-author-info">
                                    <h4 class="testimonial-name">Nita Sari</h4>
                                    <p class="testimonial-position">Orang Tua Siswa Kelas XI</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <i class="fas fa-quote-left testimonial-quote"></i>
                            <p class="testimonial-text">Program magang di perusahaan partner SMK PGRI CIKAMPEK membuka banyak peluang karir untuk saya. Pengalaman praktik langsung di industri teknologi sangat berharga untuk masa depan saya.</p>
                            <div class="testimonial-author">
                                <div class="testimonial-author-img">
                                    <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Siswa">
                                </div>
                                <div class="testimonial-author-info">
                                    <h4 class="testimonial-name">Dina Fatimah</h4>
                                    <p class="testimonial-position">Siswa Kelas XII - Jurusan Rekayasa Perangkat Lunak</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <i class="fas fa-quote-left testimonial-quote"></i>
                            <p class="testimonial-text">Sebagai lulusan tahun 2024, saya mendapat banyak tawaran kerja berkat keterampilan dan sertifikasi yang saya peroleh di SMK PGRI CIKAMPEK. Kurikulum berbasis industri mempersiapkan saya dengan sempurna.</p>
                            <div class="testimonial-author">
                                <div class="testimonial-author-img">
                                    <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Alumni Baru">
                                </div>
                                <div class="testimonial-author-info">
                                    <h4 class="testimonial-name">Budi Santoso</h4>
                                    <p class="testimonial-position">Alumni 2024 - IoT Developer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-nav">
                    <button class="testimonial-button testimonial-prev"><i class="fas fa-chevron-left"></i></button>
                    <div class="testimonial-dots">
                        <span class="testimonial-dot active"></span>
                        <span class="testimonial-dot"></span>
                        <span class="testimonial-dot"></span>
                        <span class="testimonial-dot"></span>
                    </div>
                    <button class="testimonial-button testimonial-next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="daftar">
        <div class="container-custom">
            <div class="cta-content" data-aos="zoom-in">
                <h2 class="cta-title">Siap Bergabung Dengan Kami?</h2>
                <p class="cta-description">Jangan lewatkan kesempatan untuk mendapatkan pendidikan berkualitas dan mempersiapkan masa depan cerahmu.</p>
                <a href="{{ route('pendaftar.register') }}" class="hero-cta-primary">Daftar Sekarang</a>
            </div>
        </div>
    </section>

    <!-- Social Media Integration -->
    <section class="social-media-section">
        <div class="container-custom text-center">
            <h2>Ikuti Kami di Media Sosial</h2>
            <div class="social-icons">
                @if(setting('facebook_url'))
                <a href="{{ setting('facebook_url') }}" target="_blank" class="social-icon">
                    <i class="fab fa-facebook-f"></i>
                </a>
                @endif
                
                @if(setting('instagram_url'))
                <a href="{{ setting('instagram_url') }}" target="_blank" class="social-icon">
                    <i class="fab fa-instagram"></i>
                </a>
                @endif
                
                @if(setting('youtube_url'))
                <a href="{{ setting('youtube_url') }}" target="_blank" class="social-icon">
                    <i class="fab fa-youtube"></i>
                </a>
                @endif
                
                @if(setting('whatsapp_number'))
                <a href="https://wa.me/{{ setting('whatsapp_number') }}" target="_blank" class="social-icon">
                    <i class="fab fa-whatsapp"></i>
                </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-custom">
            <div class="footer-main" data-aos="fade-up">
                <div class="footer-info">
                    <a href="#" class="footer-logo">
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
                
                <div class="footer-links-wrapper">
                    <div class="footer-links-column">
                        <h4 class="footer-links-title">Navigasi</h4>
                        <ul class="footer-links-list">
                            <li><a href="#programs">Program Keahlian</a></li>
                            <li><a href="#features">Keunggulan</a></li>
                            <li><a href="#achievements">Prestasi</a></li>
                            <li><a href="#calendar">Agenda</a></li>
                            <li><a href="#news">Berita</a></li>
                            <li><a href="#testimonials">Testimoni</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links-column">
                        <h4 class="footer-links-title">Informasi</h4>
                        <ul class="footer-links-list">
                            <li><a href="#">Tentang Kami</a></li>
                            <li><a href="#">Fasilitas</a></li>
                            <li><a href="#">Tenaga Pengajar</a></li>
                            <li><a href="#">Mitra Industri</a></li>
                            <li><a href="#">Karir</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links-column">
                        <h4 class="footer-links-title">Siswa & Alumni</h4>
                        <ul class="footer-links-list">
                            <li><a href="#">Portal Siswa</a></li>
                            <li><a href="#">E-Learning</a></li>
                            <li><a href="#">Perpustakaan Digital</a></li>
                            <li><a href="#">Jejaring Alumni</a></li>
                            <li><a href="#">Lowongan Kerja</a></li>
                            <li><a href="#">Magang Industri</a></li>
                        </ul>
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

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" aria-label="Kembali ke atas">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Newsletter Popup -->
    <div class="newsletter-popup" id="newsletterPopup">
        <div class="newsletter-content">
            <button class="newsletter-close" id="newsletterClose">
                <i class="fas fa-times"></i>
            </button>
            <div class="newsletter-header">
                <div class="newsletter-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h3>Dapatkan Informasi Terbaru</h3>
                <p>Berlangganan newsletter kami untuk mendapatkan informasi terbaru tentang program, acara, dan berita {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}.</p>
            </div>
            <form class="newsletter-form" id="newsletterForm">
                <div class="form-group">
                    <input type="text" id="newsletterName" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <input type="email" id="newsletterEmail" placeholder="Alamat Email" required>
                </div>
                <div class="newsletter-options">
                    <label>
                        <input type="checkbox" id="newsletterCategory1" value="berita">
                        <span>Berita Sekolah</span>
                    </label>
                    <label>
                        <input type="checkbox" id="newsletterCategory2" value="event">
                        <span>Event & Kegiatan</span>
                    </label>
                    <label>
                        <input type="checkbox" id="newsletterCategory3" value="pendaftaran" checked>
                        <span>Info Pendaftaran</span>
                    </label>
                </div>
                <button type="submit" class="newsletter-submit">Berlangganan Sekarang</button>
            </form>
            <div class="newsletter-success" id="newsletterSuccess">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4>Terima Kasih!</h4>
                <p>Anda telah berhasil berlangganan newsletter kami.</p>
            </div>
        </div>
    </div>

    <!-- Widget Chat untuk Calon Siswa -->
    <div id="chat-widget" class="fixed bottom-5 right-5 bg-blue text-red p-4 rounded-lg shadow-lg">
        <h4 class="text-lg font-bold">Tanya Kami</h4>
        <p class="text-sm">Ada pertanyaan? Klik tombol di bawah untuk memulai chat.</p>
        <button id="start-chat" class="mt-2 bg-white text-blue-500 px-4 py-2 rounded">Mulai Chat</button>
    </div>

    <script>
        document.getElementById('start-chat').addEventListener('click', function() {
            window.open('https://wa.me/6281234567890?text=Halo%20saya%20ingin%20bertanya%20tentang%20SMK%20PGRI%20Cikamepek', '_blank');
        });
        
        // Pastikan gambar hero tidak memiliki filter
        document.addEventListener('DOMContentLoaded', function() {
            const heroImages = document.querySelectorAll('.hero-image img, .hero img');
            heroImages.forEach(function(img) {
                img.style.filter = 'none';
                img.style.webkitFilter = 'none';
                img.style.backdropFilter = 'none';
                img.style.webkitBackdropFilter = 'none';
                img.style.mixBlendMode = 'normal';
                img.style.opacity = '1';
                img.style.background = 'transparent';
                img.style.boxShadow = 'none';
            });
            
            // Hapus filter dari parent containers juga
            const heroContainers = document.querySelectorAll('.hero, .hero-image, section.hero');
            heroContainers.forEach(function(container) {
                container.style.filter = 'none';
                container.style.webkitFilter = 'none';
                container.style.backdropFilter = 'none';
                container.style.webkitBackdropFilter = 'none';
                container.style.mixBlendMode = 'normal';
            });
        });
    </script>

    <!-- Simple Gallery Modal Functions -->
    <style>
        /* Hero image reset - pastikan tidak ada filter apapun */
        .hero-image, 
        .hero-image *,
        .hero-image::before,
        .hero-image::after,
        .hero-image img,
        .hero-image img::before,
        .hero-image img::after {
            filter: none !important;
            -webkit-filter: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            opacity: 1 !important;
            mix-blend-mode: normal !important;
            background: none !important;
            background-color: transparent !important;
            background-image: none !important;
            box-shadow: none !important;
            text-shadow: none !important;
        }
        
        /* Hapus overlay apapun */
        .hero-image::before,
        .hero-image::after {
            content: none !important;
            display: none !important;
        }
        
        /* Reset untuk section hero */
        .hero {
            background: none !important;
            background-color: transparent !important;
        }
        
        /* Pastikan gambar tampil natural */
        .hero-image img {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        .simple-gallery-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            overflow-y: auto;
        }

        .simple-gallery-content {
            background-color: #fff;
            margin: 2% auto;
            padding: 0;
            border-radius: 10px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow: hidden;
        }

        .simple-gallery-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .simple-gallery-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .simple-gallery-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .simple-gallery-close:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .simple-gallery-description {
            padding: 20px;
            color: #666;
            line-height: 1.6;
            border-bottom: 1px solid #eee;
        }

        .simple-gallery-photos {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            max-height: 400px;
            overflow-y: auto;
        }

        .simple-gallery-footer {
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            background: #f9f9f9;
        }

        .btn-close-simple {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-close-simple:hover {
            background: #5a6268;
        }

        .simple-photo-item {
            border-radius: 8px;
            overflow: hidden;
        }

        .simple-photo-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .simple-gallery-modal.show {
            display: block;
        }

        .gallery-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .gallery-view-btn, .gallery-zoom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.9);
            color: #333;
            border: none;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
        }

        .gallery-view-btn:hover, .gallery-zoom:hover {
            background: white;
            transform: scale(1.1);
            color: #667eea;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .simple-gallery-content {
                width: 95%;
                margin: 1% auto;
            }

            .simple-gallery-photos {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                max-height: 300px;
            }
        }
    </style>

    <script>
        // Simple gallery modal functions
        function openSimpleGalleryModal(galleryId, title, description) {
            document.getElementById('simpleGalleryTitle').textContent = title;
            document.getElementById('simpleGalleryDescription').textContent = description;
            
            // Show loading
            var photosContainer = document.getElementById('simpleGalleryPhotos');
            photosContainer.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #666;"></i><br><span style="color: #666;">Memuat foto...</span></div>';
            
            // Show modal
            document.getElementById('simpleGalleryModal').classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Fetch photos
            fetch('/api/galeri/' + galleryId + '/photos')
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                    }
                    return response.json();
                })
                .then(function(data) {
                    photosContainer.innerHTML = '';
                    
                    if (data.length === 0) {
                        photosContainer.innerHTML = '<div style="text-align: center; color: #666; padding: 40px;"><i class="fas fa-images" style="font-size: 3rem; margin-bottom: 10px;"></i><br>Tidak ada foto dalam galeri ini</div>';
                    } else {
                        for (var i = 0; i < data.length; i++) {
                            var photo = data[i];
                            var photoDiv = document.createElement('div');
                            photoDiv.className = 'simple-photo-item';
                            photoDiv.innerHTML = '<img src="/uploads/galeri/' + photo.foto + '" alt="Foto ' + (i + 1) + '">';
                            photosContainer.appendChild(photoDiv);
                        }
                    }
                })
                .catch(function(error) {
                    photosContainer.innerHTML = '<div style="text-align: center; color: #dc3545; padding: 40px;"><i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 10px;"></i><br>Gagal memuat foto galeri<br><small>' + error.message + '</small></div>';
                });
        }

        function closeSimpleGalleryModal() {
            document.getElementById('simpleGalleryModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('simpleGalleryModal');
            if (modal) {
                modal.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeSimpleGalleryModal();
                    }
                });
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                var modal = document.getElementById('simpleGalleryModal');
                if (modal && modal.classList.contains('show')) {
                    closeSimpleGalleryModal();
                }
            }
        });
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gallery-detail-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            overflow-y: auto;
            cursor: pointer;
        }

        .gallery-detail-content {
            background-color: #fff;
            margin: 2% auto;
            padding: 0;
            border-radius: 10px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow: hidden;
            cursor: default;
            position: relative;
        }

        .gallery-detail-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .gallery-detail-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .gallery-detail-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .gallery-detail-close:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .gallery-detail-description {
            padding: 20px;
            color: #666;
            line-height: 1.6;
            border-bottom: 1px solid #eee;
        }

        .gallery-detail-photos {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            max-height: 400px;
            overflow-y: auto;
        }

        .gallery-detail-footer {
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            background: #f9f9f9;
        }

        .btn-close-modal {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-close-modal:hover {
            background: #5a6268;
        }

        .gallery-detail-photo {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .gallery-detail-photo:hover {
            transform: scale(1.05);
        }

        .gallery-detail-photo img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .gallery-detail-photo .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gallery-detail-photo:hover .photo-overlay {
            opacity: 1;
        }

        .gallery-detail-photo .photo-overlay i {
            color: white;
            font-size: 1.5rem;
        }

        .gallery-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .gallery-view-btn, .gallery-zoom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.9);
            color: #333;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s;
        }

        .gallery-view-btn:hover, .gallery-zoom:hover {
            background: white;
            transform: scale(1.1);
            color: #667eea;
        }

        /* Image zoom modal styles */
        #imageZoomModal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            cursor: pointer;
        }

        #imageZoomModal.show {
            display: block;
        }

        .gallery-modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 8px;
            cursor: default;
        }

        .gallery-modal-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1002;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .gallery-modal-close:hover {
            background: rgba(0,0,0,0.8);
        }

        .gallery-modal-caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: white;
            padding: 10px 0;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            border-radius: 5px;
            padding: 10px 20px;
        }

        .gallery-modal-prev, .gallery-modal-next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            background: rgba(0,0,0,0.5);
            border: none;
            border-radius: 0 3px 3px 0;
            user-select: none;
            transition: background-color 0.3s;
        }

        .gallery-modal-next {
            right: 20px;
            border-radius: 3px 0 0 3px;
        }

        .gallery-modal-prev {
            left: 20px;
        }

        .gallery-modal-prev:hover, .gallery-modal-next:hover {
            background: rgba(0,0,0,0.8);
        }

        .gallery-detail-modal.show {
            display: block;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .gallery-detail-content {
                width: 95%;
                margin: 1% auto;
            }

            .gallery-detail-photos {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                max-height: 300px;
            }

            .gallery-modal-prev, .gallery-modal-next {
                padding: 12px;
                font-size: 16px;
            }

            .gallery-modal-close {
                top: 10px;
                right: 15px;
                font-size: 30px;
                width: 40px;
                height: 40px;
            }
        }
    </style>

    <script>
        let currentGalleryPhotos = [];
        let currentPhotoIndex = 0;

        // Gallery Filter Function
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.gallery-filter-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter gallery items
                    galleryItems.forEach(item => {
                        const category = item.getAttribute('data-category');
                        
                        if (filter === 'all' || category === filter) {
                            item.style.display = 'block';
                            item.style.animation = 'fadeIn 0.5s ease-in';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });

        // Open gallery detail modal
        function openGalleryModal(galleryId, title, description) {
            try {
                const galleryDetailTitle = document.getElementById('galleryDetailTitle');
                const galleryDetailDescription = document.getElementById('galleryDetailDescription');
                const photosContainer = document.getElementById('galleryDetailPhotos');
                const galleryDetailModal = document.getElementById('galleryDetailModal');
                
                if (!galleryDetailTitle || !galleryDetailDescription || !photosContainer || !galleryDetailModal) {
                    return;
                }
                
                galleryDetailTitle.textContent = title;
                galleryDetailDescription.textContent = description;
                
                // Show loading state
                photosContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-500"></i><br><span class="text-gray-500">Memuat foto...</span></div>';
                
                // Show modal first
                galleryDetailModal.classList.add('show');
                
                // Disable body scroll
                document.body.style.overflow = 'hidden';
                
                // Fetch gallery photos
                fetch(`/api/galeri/${galleryId}/photos`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        photosContainer.innerHTML = '';
                        currentGalleryPhotos = data;
                        
                        if (data.length === 0) {
                            photosContainer.innerHTML = '<div class="text-center text-gray-500 py-8"><i class="fas fa-images text-4xl mb-2"></i><br>Tidak ada foto dalam galeri ini</div>';
                        } else {
                            data.forEach((photo, index) => {
                                const photoDiv = document.createElement('div');
                                photoDiv.className = 'gallery-detail-photo';
                                photoDiv.innerHTML = `
                                    <img src="/uploads/galeri/${photo.foto}" alt="Foto ${index + 1}" loading="lazy">
                                    <div class="photo-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                `;
                                photoDiv.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    openImageZoom(index);
                                });
                                photosContainer.appendChild(photoDiv);
                            });
                        }
                    })
                    .catch(error => {
                        photosContainer.innerHTML = '<div class="text-center text-red-500 py-8"><i class="fas fa-exclamation-triangle text-4xl mb-2"></i><br>Gagal memuat foto galeri</div>';
                    });
            } catch (error) {
                // Error handling
            }
        }

        // Close gallery detail modal
        function closeGalleryModal() {
            try {
                const modal = document.getElementById('galleryDetailModal');
                if (modal) {
                    modal.classList.remove('show');
                    document.body.style.overflow = 'auto';
                } else {
                    // Modal not found
                }
            } catch (error) {
                // Error handling
            }
        }

        // Open image zoom modal
        function openImageZoom(photoIndex) {
            try {
                currentPhotoIndex = photoIndex;
                const photo = currentGalleryPhotos[photoIndex];
                const zoomedImage = document.getElementById('zoomedImage');
                const imageCaption = document.getElementById('imageCaption');
                const imageZoomModal = document.getElementById('imageZoomModal');
                
                if (zoomedImage && imageCaption && imageZoomModal) {
                    zoomedImage.src = `/uploads/galeri/${photo.foto}`;
                    imageCaption.textContent = `Foto ${photoIndex + 1} dari ${currentGalleryPhotos.length}`;
                    imageZoomModal.classList.add('show');
                } else {
                    // Modal elements not found
                }
            } catch (error) {
                // Error handling
            }
        }

        // Close image zoom modal
        function closeImageZoom() {
            try {
                const imageZoomModal = document.getElementById('imageZoomModal');
                if (imageZoomModal) {
                    imageZoomModal.classList.remove('show');
                } else {
                    // Modal not found
                }
            } catch (error) {
                // Error handling
            }
        }

        // Navigate to previous image
        function prevImage() {
            currentPhotoIndex = (currentPhotoIndex - 1 + currentGalleryPhotos.length) % currentGalleryPhotos.length;
            openImageZoom(currentPhotoIndex);
        }

        // Navigate to next image
        function nextImage() {
            currentPhotoIndex = (currentPhotoIndex + 1) % currentGalleryPhotos.length;
            openImageZoom(currentPhotoIndex);
        }

        // Close modals when clicking outside
        function handleModalClick(event) {
            if (event.target.id === 'galleryDetailModal') {
                closeGalleryModal();
            }
        }

        function handleZoomModalClick(event) {
            if (event.target.id === 'imageZoomModal') {
                closeImageZoom();
            }
        }

        // Handle escape key and navigation - use a different approach
        document.addEventListener('keydown', function(event) {
            const galleryDetailModal = document.getElementById('galleryDetailModal');
            const imageZoomModal = document.getElementById('imageZoomModal');
            
            const detailModalOpen = galleryDetailModal && galleryDetailModal.classList.contains('show');
            const zoomModalOpen = imageZoomModal && imageZoomModal.classList.contains('show');
            
            switch(event.key) {
                case 'Escape':
                    event.preventDefault();
                    if (zoomModalOpen) {
                        closeImageZoom();
                    } else if (detailModalOpen) {
                        closeGalleryModal();
                    }
                    break;
                case 'ArrowLeft':
                    if (zoomModalOpen) {
                        event.preventDefault();
                        prevImage();
                    }
                    break;
                case 'ArrowRight':
                    if (zoomModalOpen) {
                        event.preventDefault();
                        nextImage();
                    }
                    break;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="{{ asset('js/script-new.js') }}"></script>
    <script src="{{ asset('js/newsletter.js') }}"></script>
    
    <!-- CSS untuk memastikan gambar hero natural tanpa filter -->
    <style>
        /* Override semua kemungkinan filter dari CSS eksternal */
        section.hero .hero-image img,
        .hero .hero-image img,
        .hero-image img,
        img[src*="image_url"] {
            filter: none !important;
            -webkit-filter: none !important;
            -moz-filter: none !important;
            -o-filter: none !important;
            -ms-filter: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            mix-blend-mode: normal !important;
            opacity: 1 !important;
            background: transparent !important;
            box-shadow: none !important;
            text-shadow: none !important;
        }
        
        /* Pastikan container hero juga tidak ada filter */
        section.hero,
        .hero,
        .hero-image,
        section.hero .hero-image {
            filter: none !important;
            -webkit-filter: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            background: transparent !important;
            mix-blend-mode: normal !important;
        }
        
        /* Hapus pseudo elements yang mungkin menyebabkan overlay */
        .hero::before,
        .hero::after,
        .hero-image::before,
        .hero-image::after,
        section.hero::before,
        section.hero::after {
            display: none !important;
            content: none !important;
            background: none !important;
        }
    </style>
    
    <!-- Error handling untuk Cloudflare Insights -->
    <script>
        // Suppress Cloudflare Insights errors yang sering diblokir ad blocker
        window.addEventListener('error', function(event) {
            if (event.filename && (event.filename.includes('beacon.min.js') || event.filename.includes('cloudflareinsights'))) {
                event.preventDefault();
                return false;
            }
        });
        
        // Handle network errors
        window.addEventListener('unhandledrejection', function(event) {
            if (event.reason && event.reason.toString().includes('beacon.min.js')) {
                event.preventDefault();
            }
        });
        
        // Error handling for Cloudflare Insights
    </script>
</body>
</html>