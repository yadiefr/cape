<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $berita->judul }} - {{ setting('site_title', 'SMK PGRI CIKAMPEK') }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ Str::limit(strip_tags($berita->isi), 160) }}">
    <meta name="keywords" content="{{ $berita->judul }}, berita, {{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}">
    <meta name="author" content="{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $berita->judul }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($berita->isi), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($berita->foto)
    <meta property="og:image" content="{{ asset('storage/' . $berita->foto) }}">
    @elseif(setting('logo_sekolah'))
    <meta property="og:image" content="{{ asset('storage/' . setting('logo_sekolah')) }}">
    @endif
    
    <!-- Article specific meta -->
    <meta property="article:published_time" content="{{ $berita->created_at->toISOString() }}">
    <meta property="article:modified_time" content="{{ $berita->updated_at->toISOString() }}">
    <meta property="article:author" content="{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}">
    
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
        @php
            // Helper function to get file icon based on file extension
            function getFileIcon($filename) {
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                $icons = [
                    'pdf' => 'fas fa-file-pdf',
                    'doc' => 'fas fa-file-word',
                    'docx' => 'fas fa-file-word',
                    'xls' => 'fas fa-file-excel',
                    'xlsx' => 'fas fa-file-excel',
                    'ppt' => 'fas fa-file-powerpoint',
                    'pptx' => 'fas fa-file-powerpoint',
                    'jpg' => 'fas fa-file-image',
                    'jpeg' => 'fas fa-file-image',
                    'png' => 'fas fa-file-image',
                    'gif' => 'fas fa-file-image',
                    'svg' => 'fas fa-file-image',
                    'zip' => 'fas fa-file-archive',
                    'rar' => 'fas fa-file-archive',
                    '7z' => 'fas fa-file-archive',
                    'txt' => 'fas fa-file-alt',
                    'mp4' => 'fas fa-file-video',
                    'avi' => 'fas fa-file-video',
                    'mov' => 'fas fa-file-video',
                    'mp3' => 'fas fa-file-audio',
                    'wav' => 'fas fa-file-audio',
                ];
                
                return $icons[$extension] ?? 'fas fa-file';
            }
            
            // Helper function to format file size
            function formatFileSize($filePath) {
                if (file_exists(public_path('storage/' . $filePath))) {
                    $bytes = filesize(public_path('storage/' . $filePath));
                    if ($bytes >= 1048576) {
                        return number_format($bytes / 1048576, 2) . ' MB';
                    } elseif ($bytes >= 1024) {
                        return number_format($bytes / 1024, 2) . ' KB';
                    } elseif ($bytes > 1) {
                        return $bytes . ' bytes';
                    } elseif ($bytes == 1) {
                        return $bytes . ' byte';
                    } else {
                        return '0 bytes';
                    }
                }
                return 'Unknown size';
            }
        @endphp
        
        /* Article Page Styles */
        .article-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 50%, #7c3aed 100%);
            padding: 6rem 0 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .article-header::before {
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
        
        .article-header .container-custom {
            position: relative;
            z-index: 2;
        }
        
        .breadcrumb {
            background: rgba(255,255,255,0.1);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            margin-bottom: 2rem;
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
        
        .article-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .meta-item i {
            color: rgba(255,255,255,0.8);
        }
        
        /* Article Content */
        .article-section {
            padding: 5rem 0;
            background: white;
        }
        
        .article-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .article-image {
            width: 100%;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #374151;
        }
        
        .article-content h1,
        .article-content h2,
        .article-content h3,
        .article-content h4,
        .article-content h5,
        .article-content h6 {
            color: #1e293b;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        
        .article-content blockquote {
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 0 10px 10px 0;
            font-style: italic;
        }
        
        /* Article Attachments */
        .article-attachments {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 3rem;
            border: 2px dashed #cbd5e1;
        }
        
        .attachments-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .attachments-title i {
            color: #3b82f6;
        }
        
        .attachment-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .attachment-item:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .attachment-item:last-child {
            margin-bottom: 0;
        }
        
        .attachment-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .attachment-info {
            flex: 1;
            min-width: 0;
        }
        
        .attachment-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
            word-break: break-word;
        }
        
        .attachment-meta {
            font-size: 0.875rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .attachment-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .attachment-meta i {
            color: #94a3b8;
        }
        
        .attachment-download {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .attachment-download:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            color: white;
            transform: translateY(-1px);
        }
        
        .attachment-download i {
            font-size: 1rem;
        }
        
        /* Responsive Attachments */
        @media (max-width: 768px) {
            .article-attachments {
                padding: 1.5rem;
                margin-top: 2rem;
            }
            
            .attachments-title {
                font-size: 1.125rem;
            }
            
            .attachment-item {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1.5rem 1rem;
            }
            
            .attachment-info {
                text-align: center;
            }
            
            .attachment-meta {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .attachment-download {
                align-self: stretch;
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .attachment-item {
                padding: 1rem;
            }
            
            .attachment-meta {
                flex-direction: column;
                gap: 0.5rem;
                align-items: center;
            }
            
            .attachment-name {
                font-size: 0.875rem;
            }
        }
        
        /* Share Buttons */
        .article-share {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 3rem;
            text-align: center;
        }
        
        .share-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
        }
        
        .share-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .share-btn:hover {
            transform: translateY(-2px);
            color: white;
        }
        
        .share-facebook {
            background: #1877f2;
        }
        
        .share-facebook:hover {
            background: #166fe5;
        }
        
        .share-twitter {
            background: #1da1f2;
        }
        
        .share-twitter:hover {
            background: #1a94da;
        }
        
        .share-whatsapp {
            background: #25d366;
        }
        
        .share-whatsapp:hover {
            background: #22c55e;
        }
        
        .share-copy {
            background: #6b7280;
        }
        
        .share-copy:hover {
            background: #4b5563;
        }
        
        /* Related Articles */
        .related-section {
            background: #f8fafc;
            padding: 5rem 0;
        }
        
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .related-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        
        .related-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .related-card:hover .related-image img {
            transform: scale(1.05);
        }
        
        .related-date {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(59, 130, 246, 0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .related-content {
            padding: 1.5rem;
        }
        
        .related-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .related-excerpt {
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .related-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .related-link:hover {
            color: #1e40af;
        }
        
        /* Back to News */
        .back-to-news {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }
        
        .back-btn i {
            transition: transform 0.3s ease;
        }
        
        .back-btn:hover i {
            transform: translateX(-3px);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .article-header {
                padding: 4rem 0 2rem;
            }
            
            .article-title {
                font-size: 1.75rem;
            }
            
            .article-meta {
                flex-direction: column;
                align-items: start;
                gap: 1rem;
            }
            
            .article-image {
                height: 250px;
                margin-bottom: 2rem;
            }
            
            .article-content {
                font-size: 1rem;
            }
            
            .share-buttons {
                justify-content: center;
            }
            
            .related-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .article-section {
                padding: 3rem 0;
            }
            
            .related-section {
                padding: 3rem 0;
            }
            
            .share-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .share-btn {
                min-width: 200px;
                justify-content: center;
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
    
    <!-- Article Header -->
    <section class="article-header">
        <div class="container-custom">
            <nav class="breadcrumb-nav" data-aos="fade-up">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Berita</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($berita->judul, 30) }}</li>
                </ol>
            </nav>
            
            <h1 class="article-title" data-aos="fade-up" data-aos-delay="100">{{ $berita->judul }}</h1>
            
            <div class="article-meta" data-aos="fade-up" data-aos-delay="200">
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $berita->created_at->format('d M Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $berita->created_at->diffForHumans() }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ setting('nama_sekolah', 'SMK PGRI CIKAMPEK') }}</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Back to News -->
    <section class="back-to-news">
        <div class="container-custom">
            <a href="{{ route('berita.index') }}" class="back-btn" data-aos="fade-right">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar Berita
            </a>
        </div>
    </section>
    
    <!-- Article Content -->
    <section class="article-section">
        <div class="container-custom">
            <div class="article-container">
                @if($berita->foto)
                    <div class="article-image" data-aos="fade-up">
                        <img src="{{ asset('storage/' . $berita->foto) }}" alt="{{ $berita->judul }}">
                    </div>
                @endif
                
                <div class="article-content" data-aos="fade-up" data-aos-delay="100">
                    {!! nl2br(e($berita->isi)) !!}
                </div>
                
                <!-- Article Attachments -->
                @if($berita->lampiran)
                    @php
                        // Handle both JSON array and single string attachment
                        if (is_string($berita->lampiran)) {
                            // Try to decode as JSON first
                            $decodedAttachments = json_decode($berita->lampiran, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedAttachments)) {
                                $attachments = $decodedAttachments;
                            } else {
                                // If not valid JSON, treat as single file path
                                $attachments = [$berita->lampiran];
                            }
                        } else {
                            $attachments = is_array($berita->lampiran) ? $berita->lampiran : [$berita->lampiran];
                        }
                    @endphp
                    
                    @if(!empty($attachments))
                        <div class="article-attachments" data-aos="fade-up" data-aos-delay="150">
                            <div class="attachments-title">
                                <i class="fas fa-paperclip"></i>
                                Lampiran {{ count($attachments) > 1 ? '(' . count($attachments) . ' file)' : '' }}
                            </div>
                            
                            @foreach($attachments as $attachment)
                                @if($attachment)
                                    <div class="attachment-item" data-aos="fade-up" data-aos-delay="200">
                                        <div class="attachment-icon">
                                            <i class="{{ getFileIcon($attachment) }}"></i>
                                        </div>
                                        <div class="attachment-info">
                                            <div class="attachment-name">
                                                {{ basename($attachment) }}
                                            </div>
                                            <div class="attachment-meta">
                                                <span>
                                                    <i class="fas fa-file"></i>
                                                    {{ strtoupper(pathinfo($attachment, PATHINFO_EXTENSION)) }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-hdd"></i>
                                                    {{ formatFileSize($attachment) }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $berita->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $attachment) }}" 
                                           class="attachment-download" 
                                           target="_blank"
                                           download>
                                            <i class="fas fa-download"></i>
                                            Download
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endif
                
                <!-- Share Buttons -->
                <div class="article-share" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="share-title">Bagikan Berita Ini</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="share-btn share-facebook">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}" 
                           target="_blank" 
                           class="share-btn share-twitter">
                            <i class="fab fa-twitter"></i>
                            Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . url()->current()) }}" 
                           target="_blank" 
                           class="share-btn share-whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                        <button onclick="copyToClipboard('{{ url()->current() }}')" 
                                class="share-btn share-copy">
                            <i class="fas fa-copy"></i>
                            Salin Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Related Articles -->
    @if($relatedBerita->count() > 0)
    <section class="related-section">
        <div class="container-custom">
            <h2 class="section-title" data-aos="fade-up">Berita Lainnya</h2>
            
            <div class="related-grid">
                @foreach($relatedBerita as $item)
                    <div class="related-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="related-image">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #e2e8f0, #cbd5e1); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-newspaper" style="font-size: 2rem; color: #94a3b8;"></i>
                                </div>
                            @endif
                            <div class="related-date">{{ $item->created_at->format('d M Y') }}</div>
                        </div>
                        
                        <div class="related-content">
                            <h3 class="related-title">{{ $item->judul }}</h3>
                            <p class="related-excerpt">{{ Str::limit(strip_tags($item->isi), 100) }}</p>
                            <a href="{{ route('berita.show', $item->id) }}" class="related-link">
                                Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
        
        // Attachment download tracking
        document.addEventListener('DOMContentLoaded', function() {
            const attachmentDownloads = document.querySelectorAll('.attachment-download');
            
            attachmentDownloads.forEach(function(downloadBtn) {
                downloadBtn.addEventListener('click', function(e) {
                    const fileName = this.closest('.attachment-item').querySelector('.attachment-name').textContent.trim();
                    console.log('Downloading attachment:', fileName);
                    
                    // Add visual feedback
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                });
            });
        });
        
        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const button = event.target.closest('.share-copy');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
                button.style.background = '#10b981';
                
                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.style.background = '#6b7280';
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    const button = event.target.closest('.share-copy');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
                    button.style.background = '#10b981';
                    
                    setTimeout(function() {
                        button.innerHTML = originalText;
                        button.style.background = '#6b7280';
                    }, 2000);
                } catch (err) {
                    console.error('Fallback: Oops, unable to copy', err);
                }
                document.body.removeChild(textArea);
            });
        }
    </script>
</body>
</html>
