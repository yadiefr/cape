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
    <link href="{{ asset('css/homepage-enhancements.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive-utilities.css') }}" rel="stylesheet">
    <link href="{{ asset('css/homepage-modal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/gallery-modal.css') }}" rel="stylesheet">
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
                <li><a href="#features" class="nav-link">Keunggulan</a></li>
                <li><a href="#programs" class="nav-link">Program Keahlian</a></li>
                <li><a href="#news" class="nav-link">Berita</a></li>
                <li><a href="#gallery" class="nav-link">Galeri</a></li>                
                <li><a href="/login" class="btn btn-primary">Login</a></li>
                </li>
            </ul>
        </div>
    </header>
    
    <!-- Hero Section -->
    @php
        $activeBanner = \App\Models\HeroBanner::where('is_active', true)->latest()->first();
        $heroStyle = '';
        
        if($activeBanner && $activeBanner->background_image) {
            // Background Image
            $heroStyle .= 'background-image: url(' . $activeBanner->background_image_url . ') !important;';
            $heroStyle .= 'background-size: cover !important;';
            $heroStyle .= 'background-position: center center !important;';
            $heroStyle .= 'background-repeat: no-repeat !important;';
            $heroStyle .= 'position: relative !important;';
        }
    @endphp
    <section class="hero" id="hero-section" style="{{ $heroStyle }}">
        @if($activeBanner && $activeBanner->background_image && $activeBanner->background_opacity)
        <div class="background-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,{{ 1 - ($activeBanner->background_opacity ?? 0.8) }}); z-index: 1;"></div>
        @endif
        
        <div class="container-custom" style="position: relative; z-index: 10;">
            <div class="hero-grid">
                @if($activeBanner)
                    <div class="hero-content" data-aos="fade-right" data-aos-duration="1000">

                        <h1 class="hero-title">{!! $activeBanner->title !!}</h1>
                        <p class="hero-description">{{ $activeBanner->description }}</p>
                        <div class="hero-cta">
                            <a href="{{ $activeBanner->button_url }}" class="hero-cta-primary">{{ $activeBanner->button_text }}</a>
                        </div>
                    </div>
                    <div class="hero-image" data-aos="fade-left" data-aos-duration="1000" style="background: none !important; filter: none !important;">
                        <img src="{{ $activeBanner->image_url }}" alt="{{ $activeBanner->title }}" style="filter: none !important; -webkit-filter: none !important; opacity: 1 !important; mix-blend-mode: normal !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important; background: none !important; box-shadow: none !important; object-fit: cover; width: 100%; height: 100%;">
                    </div>
                @else
                    <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
                        <h1 class="hero-title">Selamat Datang di<br><span>{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}</span></h1>
                        <p class="hero-description">{{ setting('site_description', 'Mempersiapkan generasi unggul dengan pendidikan berkualitas dan fasilitas modern untuk masa depan yang gemilang.') }}</p>
                        <div class="hero-cta">
                            <a href="{{ route('pendaftar.register') }}" class="hero-cta-primary">
                                <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                            </a>
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

    <!-- Facilities Section -->
    <section class="facilities" style="background: linear-gradient(135deg, #fefefe 0%, #f1f5f9 100%); padding: 5rem 0; position: relative;">
        <!-- Background Decoration -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(circle at 25% 25%, rgba(124, 58, 237, 0.03) 2px, transparent 2px), radial-gradient(circle at 75% 75%, rgba(59, 130, 246, 0.03) 2px, transparent 2px); background-size: 100px 100px; background-position: 0 0, 50px 50px;"></div>

        <div class="container-custom" style="position: relative; z-index: 2;">
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle" style="color: #3b82f6; font-weight: 700; letter-spacing: 2px;">FASILITAS UNGGULAN</div>
                <h2 class="section-title" style="background: linear-gradient(135deg, #3b82f6, #1e40af); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Fasilitas Modern untuk Pembelajaran Optimal</h2>
                <p class="section-description" style="color: #64748b; font-size: 1.1rem;">Dilengkapi dengan fasilitas terdepan untuk mendukung proses pembelajaran yang efektif dan menyenangkan.</p>
            </div>

            <div class="row g-4">
                <!-- Fasilitas RPL -->
                <div class="col-lg-4 col-md-6 col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="facility-card" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; height: 100%;">
                        <div class="facility-image" style="height: clamp(150px, 25vw, 200px); background: linear-gradient(45deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-laptop-code" style="color: white; font-size: clamp(2.5rem, 5vw, 4rem);"></i>
                        </div>
                        <div class="facility-content" style="padding: clamp(1rem, 3vw, 1.5rem); flex-grow: 1; display: flex; flex-direction: column;">
                            <h4 style="color: #333; margin-bottom: 1rem; font-size: clamp(1.1rem, 2.5vw, 1.25rem);">Lab Komputer & RPL</h4>
                            <p style="color: #666; line-height: 1.6; font-size: clamp(0.9rem, 2vw, 1rem); margin-bottom: 1rem;">Laboratorium komputer lengkap untuk pembelajaran programming, web development, dan rekayasa perangkat lunak.</p>
                            <ul style="color: #666; margin-top: auto; padding-left: 1rem; font-size: clamp(0.85rem, 1.8vw, 0.95rem);">
                                <li>40 Unit PC Spek Tinggi</li>
                                <li>IDE & Development Tools</li>
                                <li>Server & Database Systems</li>
                                <li>Software Testing Environment</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Fasilitas TKR -->
                <div class="col-lg-4 col-md-6 col-12" data-aos="fade-up" data-aos-delay="200">
                    <div class="facility-card" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; height: 100%;">
                        <div class="facility-image" style="height: clamp(150px, 25vw, 200px); background: linear-gradient(45deg, #60a5fa, #2563eb); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-car" style="color: white; font-size: clamp(2.5rem, 5vw, 4rem);"></i>
                        </div>
                        <div class="facility-content" style="padding: clamp(1rem, 3vw, 1.5rem); flex-grow: 1; display: flex; flex-direction: column;">
                            <h4 style="color: #333; margin-bottom: 1rem; font-size: clamp(1.1rem, 2.5vw, 1.25rem);">Bengkel Otomotif (TKR)</h4>
                            <p style="color: #666; line-height: 1.6; font-size: clamp(0.9rem, 2vw, 1rem); margin-bottom: 1rem;">Bengkel otomotif standar industri untuk praktik perawatan dan perbaikan kendaraan ringan.</p>
                            <ul style="color: #666; margin-top: auto; padding-left: 1rem; font-size: clamp(0.85rem, 1.8vw, 0.95rem);">
                                <li>Mobil Praktik & Engine Stand</li>
                                <li>Alat Diagnostik EFI</li>
                                <li>Peralatan Tune Up & Overhaul</li>
                                <li>Lift Hidrolik & Tools Set</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Fasilitas TMI -->
                <div class="col-lg-4 col-md-12 col-12" data-aos="fade-up" data-aos-delay="300">
                    <div class="facility-card" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; height: 100%;">
                        <div class="facility-image" style="height: clamp(150px, 25vw, 200px); background: linear-gradient(45deg, #34d399, #059669); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-industry" style="color: white; font-size: clamp(2.5rem, 5vw, 4rem);"></i>
                        </div>
                        <div class="facility-content" style="padding: clamp(1rem, 3vw, 1.5rem); flex-grow: 1; display: flex; flex-direction: column;">
                            <h4 style="color: #333; margin-bottom: 1rem; font-size: clamp(1.1rem, 2.5vw, 1.25rem);">Bengkel Mesin (TMI)</h4>
                            <p style="color: #666; line-height: 1.6; font-size: clamp(0.9rem, 2vw, 1rem); margin-bottom: 1rem;">Bengkel teknik mekanik industri untuk praktik mesin bubut, frais, CNC, dan perawatan mesin pabrik.</p>
                            <ul style="color: #666; margin-top: auto; padding-left: 1rem; font-size: clamp(0.85rem, 1.8vw, 0.95rem);">
                                <li>Mesin Bubut & Frais</li>
                                <li>Mesin CNC Lathe & Milling</li>
                                <li>Peralatan Pengukuran Presisi</li>
                                <li>Simulasi Otomasi Industri</li>
                            </ul>
                        </div>
                    </div>
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
                <p class="section-description">Pilih jurusan yang sesuai dengan minat dan bakatmu untuk mempersiapkan karir di masa depan.</p>
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

    <!-- Quick Info Cards Section -->
    <section class="quick-info" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 50%, #be185d 100%); padding: 4rem 0; color: white; position: relative; overflow: hidden;">
        <!-- Background Elements -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(circle at 15% 85%, rgba(255,255,255,0.1) 2px, transparent 2px), radial-gradient(circle at 85% 15%, rgba(255,255,255,0.1) 2px, transparent 2px); background-size: 70px 70px; background-position: 0 0, 35px 35px;"></div>

        <div class="container-custom" style="position: relative; z-index: 2;">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <div class="section-subtitle" style="color: rgba(255,255,255,0.9); font-weight: 700; letter-spacing: 2px;">INFORMASI CEPAT</div>
                <h2 class="section-title" style="color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Hubungi Kami</h2>
                <p class="section-description" style="color: rgba(255,255,255,0.95); font-size: 1.1rem;">Dapatkan informasi lengkap tentang pendaftaran dan program keahlian kami.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="info-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: clamp(1.5rem, 3vw, 2rem); border-radius: 15px; text-align: center; height: 100%; border: 1px solid rgba(255,255,255,0.2); display: flex; flex-direction: column;">
                        <div class="info-icon mb-3">
                            <i class="fas fa-clock" style="color: #ffd700; font-size: clamp(2rem, 4vw, 3rem);"></i>
                        </div>
                        <h4 style="color: white; margin-bottom: 1rem; font-size: clamp(1.1rem, 2.5vw, 1.25rem);">Jam Operasional</h4>
                        <div style="color: rgba(255,255,255,0.9); line-height: 1.6; font-size: clamp(0.9rem, 2vw, 1rem); flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                            <div><strong>Senin - Jumat:</strong></div>
                            <div>07:00 - 16:00 WIB</div>
                            <div style="margin-top: 0.5rem;"><strong>Sabtu:</strong></div>
                            <div>07:00 - 12:00 WIB</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: clamp(1.5rem, 3vw, 2rem); border-radius: 15px; text-align: center; height: 100%; border: 1px solid rgba(255,255,255,0.2); display: flex; flex-direction: column;">
                        <div class="info-icon mb-3">
                            <i class="fas fa-phone" style="color: #00ff88; font-size: clamp(2rem, 4vw, 3rem);"></i>
                        </div>
                        <h4 style="color: white; margin-bottom: 1rem; font-size: clamp(1.1rem, 2.5vw, 1.25rem);">Kontak Kami</h4>
                        <div style="color: rgba(255,255,255,0.9); line-height: 1.6; font-size: clamp(0.85rem, 1.8vw, 0.95rem); flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                            <div style="margin-bottom: 0.5rem;"><i class="fas fa-phone mr-2"></i>{{ setting('telepon_sekolah', '(0264) 123456') }}</div>
                            <div style="margin-bottom: 0.5rem; word-break: break-all;"><i class="fas fa-envelope mr-2"></i>{{ setting('email_sekolah', 'info@smkpgricikampek.sch.id') }}</div>
                            <div><i class="fab fa-whatsapp mr-2"></i>{{ setting('whatsapp_number', '08123456789') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="300">
                    <div class="info-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: clamp(1.5rem, 3vw, 2rem); border-radius: 15px; text-align: center; height: 100%; border: 1px solid rgba(255,255,255,0.2); display: flex; flex-direction: column;">
                        <div class="info-icon mb-3">
                            <i class="fas fa-map-marker-alt" style="color: #ff6b6b; font-size: clamp(2rem, 4vw, 3rem);"></i>
                        </div>
                        <h4 style="color: white; margin-bottom: 1rem; font-size: clamp(1.1rem, 2.5vw, 1.25rem);">Lokasi</h4>
                        <div style="color: rgba(255,255,255,0.9); line-height: 1.6; font-size: clamp(0.85rem, 1.8vw, 0.95rem); flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                            <div style="margin-bottom: 1rem;">{{ setting('alamat_sekolah', 'Jl. Raya Cikampek No. 123, Cikampek, Karawang, Jawa Barat') }}</div>
                            <a href="https://maps.google.com" target="_blank" style="color: #ffd700; text-decoration: none; display: inline-block; font-size: clamp(0.8rem, 1.6vw, 0.9rem);">
                                <i class="fas fa-external-link-alt mr-1"></i>Lihat di Maps
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12 col-12" data-aos="fade-up" data-aos-delay="400">
                    <div class="info-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: clamp(1.5rem, 3vw, 2rem); border-radius: 15px; text-align: center; height: 100%; border: 1px solid rgba(255,255,255,0.2); display: flex; flex-direction: column;">
                        <div class="info-icon mb-3">
                            <i class="fas fa-user-plus" style="color: #4ecdc4; font-size: clamp(2rem, 4vw, 3rem);"></i>
                        </div>
                        <h4 style="color: white; margin-bottom: 1rem; font-size: clamp(1.1rem, 2.5vw, 1.25rem);">Pendaftaran</h4>
                        <div style="color: rgba(255,255,255,0.9); line-height: 1.6; margin-bottom: 1rem; font-size: clamp(0.85rem, 1.8vw, 0.95rem); flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                            <div><strong>PPDB 2025/2026</strong></div>
                            <div>Gelombang 1: Maret - Mei</div>
                            <div>Gelombang 2: Juni - Juli</div>
                        </div>
                        <a href="{{ route('pendaftar.register') }}" style="background: #ffd700; color: #333; padding: clamp(0.5rem, 2vw, 0.75rem) clamp(1rem, 3vw, 1.5rem); border-radius: 25px; text-decoration: none; font-weight: 600; display: inline-block; font-size: clamp(0.85rem, 1.8vw, 0.95rem);">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
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

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/{{ setting('whatsapp_number', '08123456789') }}?text=Halo,%20saya%20ingin%20bertanya%20tentang%20SMK%20PGRI%20CIKAMPEK"
       class="whatsapp-float"
       target="_blank"
       aria-label="Chat WhatsApp"
       style="position: fixed; bottom: 80px; right: 20px; background: #25d366; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; z-index: 1000; box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4); transition: all 0.3s ease; text-decoration: none;">
        <i class="fab fa-whatsapp"></i>
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
    // <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="{{ asset('js/script-new.js') }}"></script>
    <script src="{{ asset('js/newsletter.js') }}"></script>
    <script src="{{ asset('js/welcome-scripts.js') }}"></script>

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

        // Counter Animation for Statistics
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200; // The lower the slower

            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                };
                updateCount();
            });
        }

        // Trigger counter animation when statistics section is in view
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.statistics');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Enhanced smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add hover effects to cards
        document.querySelectorAll('.feature-card, .program-card, .facility-card, .testimonial-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        // Add loading animation to CTA buttons
        document.querySelectorAll('.hero-cta-primary, .cta-button').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.classList.contains('loading')) {
                    this.classList.add('loading');
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';

                    setTimeout(() => {
                        this.classList.remove('loading');
                        this.innerHTML = originalText;
                    }, 2000);
                }
            });
        });

        // Responsive adjustments based on screen size
        function adjustForScreenSize() {
            const screenWidth = window.innerWidth;
            const isMobile = screenWidth < 768;
            const isTablet = screenWidth >= 768 && screenWidth < 992;
            const isDesktop = screenWidth >= 992;

            // Adjust AOS animations for mobile
            if (isMobile) {
                // Reduce animation duration for mobile
                document.querySelectorAll('[data-aos]').forEach(element => {
                    element.setAttribute('data-aos-duration', '600');
                    element.setAttribute('data-aos-delay', '0');
                });
            }

            // Adjust hero stats layout
            const heroStats = document.querySelector('.hero-stats');
            if (heroStats) {
                if (isMobile) {
                    heroStats.style.flexWrap = 'nowrap';
                    heroStats.style.justifyContent = 'space-between';
                } else {
                    heroStats.style.flexWrap = 'wrap';
                    heroStats.style.justifyContent = 'center';
                }
            }

            // Adjust WhatsApp button position
            const whatsappButton = document.querySelector('.whatsapp-float');
            if (whatsappButton) {
                if (isMobile) {
                    whatsappButton.style.bottom = '15px';
                    whatsappButton.style.right = '10px';
                    whatsappButton.style.width = '50px';
                    whatsappButton.style.height = '50px';
                } else {
                    whatsappButton.style.bottom = '80px';
                    whatsappButton.style.right = '20px';
                    whatsappButton.style.width = '60px';
                    whatsappButton.style.height = '60px';
                }
            }
        }

        // Run on load and resize
        adjustForScreenSize();
        window.addEventListener('resize', debounce(adjustForScreenSize, 250));

        // Debounce function for performance
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Optimize images loading for mobile
        if (window.innerWidth < 768) {
            // Lazy load images more aggressively on mobile
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.loading = 'lazy';
            });
        }

        // Touch-friendly enhancements for mobile
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');

            // Add touch feedback to cards
            document.querySelectorAll('.testimonial-card, .facility-card, .info-card').forEach(card => {
                card.addEventListener('touchstart', function() {
                    this.style.transform = 'translateY(-5px)';
                });

                card.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.style.transform = 'translateY(0)';
                    }, 150);
                });
            });
        }
    </script>
</body>
</html>