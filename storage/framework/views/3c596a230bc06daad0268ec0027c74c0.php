<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Siswa'); ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Admin Fallback CSS untuk memastikan basic styling selalu ada -->
    <link href="<?php echo e(asset('css/admin-fallback.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Force Tailwind CDN for immediate styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Outfit', sans-serif;
            overflow: auto;
        }

        html {
            overflow: auto;
        }

        /* Line clamp utility for text truncation */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        @media (min-width: 1024px) {
            .sidebar-desktop {
                position: fixed !important;
                height: calc(100% - 4rem) !important;
                top: 4rem !important;
                z-index: 30;
                width: 16rem;
                background-color: white;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                transition: transform 0.1s ease;
            }
            
            /* Content area styling */
            main {
                transition: all 0.1s ease;
                width: 100%;
            }
            
            main.ml-0 {
                margin-left: 0;
                width: 100%;
            }
            
            main.lg\:ml-64 {
                margin-left: 16rem;
                width: calc(100% - 16rem);
            }
            
            @media (max-width: 1023px) {
                main {
                    margin-left: 0 !important;
                    width: 100% !important;
                }
            }
        }
        
        /* Scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
        }
        
        .scrollbar-thumb-blue-200::-webkit-scrollbar-thumb {
            background-color: #bfdbfe;
            border-radius: 3px;
        }
        
        .scrollbar-track-gray-50::-webkit-scrollbar-track {
            background-color: #f9fafb;
        }

        /* Mobile layout improvements */
        @media (max-width: 1023px) {
            .sidebar-mobile {
                position: fixed !important;
                top: 4rem !important;
                left: 0 !important;
                height: calc(100vh - 4rem) !important;
                overflow-y: auto !important;
                z-index: 50 !important;
                width: 16rem;
                padding-top: 0 !important;
                background-color: white !important;
                box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                pointer-events: auto !important;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
            
            .sidebar-mobile.translate-x-0 {
                transform: translateX(0) !important;
            }
            
            .sidebar-mobile.-translate-x-full {
                transform: translateX(-100%) !important;
            }
            
            .sidebar-mobile nav {
                padding-bottom: 3rem !important;
                pointer-events: auto !important;
            }
            
            .sidebar-mobile .space-y-8 {
                padding-bottom: 2rem !important;
            }
            
            .sidebar-mobile a, .sidebar-mobile button {
                pointer-events: auto !important;
                cursor: pointer !important;
            }
            
            /* Mobile overlay */
            .mobile-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background-color: rgba(0, 0, 0, 0.5) !important;
                z-index: 40 !important;
                pointer-events: auto !important;
                transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
            
            /* Hide overlay completely when sidebar is closed */
            .mobile-overlay.hidden {
                display: none !important;
                pointer-events: none !important;
            }
            
            /* Ensure main content is not affected by overlay when sidebar is closed */
            .main-content-mobile-free {
                position: relative !important;
                z-index: 1 !important;
                pointer-events: auto !important;
                background-color: #f3f4f6 !important;
            }
            
            /* Mobile-specific interactivity */
            .mobile-interactive {
                -webkit-tap-highlight-color: rgba(59, 130, 246, 0.1);
                touch-action: manipulation;
                pointer-events: auto !important;
            }
            
            .mobile-nav-item {
                min-height: 48px;
                display: flex;
                align-items: center;
                pointer-events: auto !important;
            }
            
            .mobile-smooth-scroll {
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }
            
            .mobile-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            
            .mobile-card:active {
                transform: scale(0.98);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
        }
        
        /* Content area improvements - simplified like guru layout */
        .main-content-container {
            position: relative;
            z-index: 1;
        }
        
        /* Mobile content fixes */
        @media (max-width: 1023px) {
            /* Ensure content is scrollable on mobile when sidebar closed */
            .main-content-mobile-free {
                position: relative !important;
                z-index: 10 !important;
                pointer-events: auto !important;
                background-color: #f3f4f6 !important;
                height: auto !important;
                overflow-y: auto !important;
                touch-action: auto !important;
            }
            
            .main-content-mobile-free * {
                pointer-events: auto !important;
                touch-action: auto !important;
            }
            
            /* Prevent overlay interference with main content */
            main:not(.sidebar-overlay-active) {
                position: relative !important;
                z-index: 10 !important;
                pointer-events: auto !important;
            }
            
            /* Fix scrolling issues on mobile */
            .mobile-scroll-fix {
                -webkit-overflow-scrolling: touch !important;
                overflow-y: auto !important;
                height: auto !important;
                min-height: calc(100vh - 6rem) !important;
                pointer-events: auto !important;
                touch-action: auto !important;
            }
            
            /* Body fixes for mobile sidebar closed state */
            body.sidebar-closed-mobile {
                overflow: auto !important;
                position: static !important;
                height: auto !important;
                pointer-events: auto !important;
            }
            
            body.sidebar-closed-mobile main {
                pointer-events: auto !important;
                touch-action: auto !important;
                z-index: 10 !important;
                position: relative !important;
            }
        }
        
        /* Mobile content fixes - simplified */
        @media (max-width: 1023px) {
            /* Ensure content is scrollable on mobile when sidebar closed */
            .main-content-mobile-free {
                position: relative !important;
                z-index: 10 !important;
                pointer-events: auto !important;
                background-color: #f3f4f6 !important;
                height: auto !important;
                overflow-y: auto !important;
                touch-action: auto !important;
            }
            
            .main-content-mobile-free * {
                pointer-events: auto !important;
            }
        }
        
        /* Smooth scrolling for all devices */
        main {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        
        /* iOS specific fixes */
        @supports (-webkit-touch-callout: none) {
            .ios-safe-area {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<<body class="bg-gray-100 text-gray-800 antialiased" x-data="{ 
    sidebarOpen: true,
    isMobile: false,

    init() {
        this.checkScreenSize();
        window.addEventListener('resize', () => {
            this.checkScreenSize();
        });
    },

    checkScreenSize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth < 1024;
        
        if (wasMobile !== this.isMobile) {
            if (this.isMobile) {
                this.sidebarOpen = false;
                document.body.classList.remove('overflow-hidden');
            } else {
                this.sidebarOpen = true;
                document.body.classList.remove('overflow-hidden');
            }
        }
    },

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        
        if (this.isMobile) {
            if (this.sidebarOpen) {
                document.body.classList.add('overflow-hidden');
                document.body.classList.remove('sidebar-closed-mobile');
            } else {
                document.body.classList.remove('overflow-hidden');
                document.body.classList.add('sidebar-closed-mobile');
                
                // Ensure main content is scrollable
                const mainContent = document.querySelector('main');
                if (mainContent) {
                    mainContent.classList.add('mobile-scroll-fix');
                    mainContent.style.pointerEvents = 'auto';
                    mainContent.style.touchAction = 'auto';
                }
            }
        }
    }
}" x-init="init()" x-cloak>
    
    <!-- Main Container -->
    <div class="flex min-h-screen bg-gray-100" 
         @click.stop=""
         :class="{ 'pointer-events-auto': !sidebarOpen || !isMobile }">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen && isMobile" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="toggleSidebar()" 
             class="mobile-overlay lg:hidden"
             :class="{ 'hidden': !sidebarOpen || !isMobile }"
             x-cloak></div>
             
        <!-- Sidebar -->
        <aside class="bg-white shadow-lg lg:relative lg:block sidebar-desktop"
            x-transition:enter="transition-transform ease-out duration-300"
            x-transition:enter-start="transform -translate-x-full"
            x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition-transform ease-in duration-200"
            x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform -translate-x-full"
            :class="{
                'sidebar-desktop transform translate-x-0': !isMobile && sidebarOpen,
                'sidebar-desktop transform -translate-x-full': !isMobile && !sidebarOpen,
                'sidebar-mobile transform translate-x-0': isMobile && sidebarOpen,
                'sidebar-mobile transform -translate-x-full': isMobile && !sidebarOpen
            }">
            
            <div class="flex flex-col h-full overflow-hidden">
                <!-- User Profile -->
                <div class="flex-shrink-0 flex items-center justify-between p-4 border-b border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <?php
                                $siswa = Auth::guard('siswa')->user();
                                $fotoProfil = $siswa && !empty($siswa->foto) ? asset('storage/' . $siswa->foto) : null;
                                $namaSiswa = $siswa->nama_lengkap ?? 'Siswa';
                                $initials = '';
                                if ($namaSiswa && !$fotoProfil) {
                                    $parts = preg_split('/\s+/', trim($namaSiswa));
                                    if (count($parts) > 1) {
                                        $initials = strtoupper(mb_substr($parts[0],0,1) . mb_substr($parts[1],0,1));
                                    } else {
                                        $initials = strtoupper(mb_substr($parts[0],0,1));
                                    }
                                }
                            ?>
                            <?php if($fotoProfil): ?>
                                <img class="h-10 w-10 rounded-full border-2 border-blue-500 object-cover" src="<?php echo e($fotoProfil); ?>" alt="<?php echo e($namaSiswa); ?>">
                            <?php elseif($initials): ?>
                                <div class="h-10 w-10 rounded-full border-2 border-blue-500 bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-lg"><?php echo e($initials); ?></div>
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full border-2 border-blue-500 bg-blue-100 flex items-center justify-center"><i class="fas fa-user-graduate text-xl text-blue-400"></i></div>
                            <?php endif; ?>
                            <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-green-500 rounded-full border-2 border-white">
                                <span class="absolute inset-0 h-full w-full rounded-full animate-ping bg-green-500 opacity-75"></span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo e(Auth::guard('siswa')->user()->nama_lengkap ?? 'Siswa'); ?></p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    Siswa
                                </span>
                                <?php if(Auth::guard('siswa')->user()->kelas): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <?php echo e(Auth::guard('siswa')->user()->kelas->nama_kelas); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <button @click="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-200 scrollbar-track-gray-50">
                    <div class="p-4 space-y-8 pb-12">
                        <!-- Main Navigation -->
                        <div class="space-y-2">
                            <a href="<?php echo e(route('siswa.dashboard')); ?>" 
                               class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                               <?php echo e(request()->routeIs('siswa.dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.dashboard') ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600 group-hover:bg-blue-200'); ?> rounded-lg transition-colors mr-3">
                                    <i class="fas fa-home"></i>
                                </div>
                                <span>Dashboard</span>
                            </a>
                        </div>

                        <!-- Academic Section -->
                        <div class="space-y-3">
                            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Akademik</p>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('siswa.jadwal.index')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.jadwal.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.jadwal.*') ? 'bg-white/20 text-white' : 'bg-green-100 text-green-600 group-hover:bg-green-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <span>Jadwal Pelajaran</span>
                                </a>

                                <a href="<?php echo e(route('siswa.materi.index')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.materi.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.materi.*') ? 'bg-white/20 text-white' : 'bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <span>Materi & Tugas</span>
                                </a>

                                <a href="<?php echo e(route('siswa.nilai.index')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.nilai.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.nilai.*') ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600 group-hover:bg-purple-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span>Nilai Pelajaran</span>
                                </a>

                                <a href="<?php echo e(route('siswa.ujian.index')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.ujian.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.ujian.*') ? 'bg-white/20 text-white' : 'bg-red-100 text-red-600 group-hover:bg-red-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                    <span>Ujian Online</span>
                                </a>

                                <a href="<?php echo e(route('siswa.absensi')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.absensi*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.absensi*') ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600 group-hover:bg-orange-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <span>Absensi</span>
                                </a>
                            </div>
                        </div>

                        <!-- Keuangan Section -->
                        <div class="space-y-3">
                            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Keuangan</p>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('siswa.keuangan.index')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.keuangan.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.keuangan.*') ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600 group-hover:bg-blue-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <span>Keuangan</span>
                                </a>
                            </div>
                        </div>

                        <!-- Role Siswa Section -->
                        <?php
                            $currentSiswa = Auth::guard('siswa')->user();
                        ?>
                        <?php if($currentSiswa && ($currentSiswa->is_ketua_kelas || $currentSiswa->is_bendahara)): ?>
                        <div class="space-y-3">
                            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Role Khusus</p>
                            <div class="space-y-1">
                                <?php if($currentSiswa->is_ketua_kelas): ?>
                                <div x-data="{ openKM: false }" class="relative">
                                    <button @click="openKM = !openKM" 
                                           class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                           <?php echo e(request()->routeIs('siswa.ketua-kelas.*') ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-sm' : 'text-gray-700 hover:bg-purple-50'); ?>">
                                        <div class="flex items-center">
                                            <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.ketua-kelas.*') ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600 group-hover:bg-purple-200'); ?> rounded-lg transition-colors mr-3">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                            <span>Ketua Kelas</span>
                                        </div>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'transform rotate-180': openKM }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="openKM" class="ml-4 mt-2 space-y-1">
                                        <a href="<?php echo e(route('siswa.ketua-kelas.dashboard')); ?>" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                            <i class="fas fa-tachometer-alt w-4 mr-3"></i>
                                            Dashboard
                                        </a>
                                        <a href="<?php echo e(route('siswa.ketua-kelas.absensi')); ?>" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                            <i class="fas fa-user-check w-4 mr-3"></i>
                                            Input Absensi
                                        </a>
                                        <a href="<?php echo e(route('siswa.ketua-kelas.rekap-absensi')); ?>" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                            <i class="fas fa-chart-bar w-4 mr-3"></i>
                                            Rekap Absensi
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($currentSiswa->is_bendahara): ?>
                                <a href="<?php echo e(route('siswa.bendahara.dashboard')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.bendahara.*') ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-sm' : 'text-gray-700 hover:bg-green-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.bendahara.*') ? 'bg-white/20 text-white' : 'bg-green-100 text-green-600 group-hover:bg-green-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <span>Bendahara</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Others Section -->
                        <div class="space-y-3">
                            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Informasi</p>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('siswa.pengumuman.index')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.pengumuman.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.pengumuman.*') ? 'bg-white/20 text-white' : 'bg-red-100 text-red-600 group-hover:bg-red-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <span>Pengumuman</span>
                                </a>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="space-y-3">
                            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pengaturan</p>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('siswa.profile.index')); ?>" 
                                   class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group
                                   <?php echo e(request()->routeIs('siswa.profile.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50'); ?>">
                                    <div class="flex items-center justify-center w-8 h-8 <?php echo e(request()->routeIs('siswa.profile.*') ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600 group-hover:bg-blue-200'); ?> rounded-lg transition-colors mr-3">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <span>Profil Saya</span>
                                </a>
                                <!-- Tombol Logout -->
                                <form method="POST" action="<?php echo e(route('siswa.logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="mobile-interactive mobile-nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group text-gray-700 hover:bg-red-50 w-full">
                                        <div class="flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 group-hover:bg-red-200 rounded-lg transition-colors mr-3">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col w-full flex-grow">
            <!-- Top Navigation -->
            <header class="bg-white shadow-md z-40 fixed top-0 left-0 w-full h-16">
                <div class="px-4 sm:px-6 py-3 flex justify-between items-center h-full">
                    <div class="flex items-center flex-1 min-w-0">
                        <!-- Sidebar Toggle Button -->
                        <button @click="toggleSidebar()" type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none mr-3 sm:mr-4 transition-all duration-300 ease-in-out flex items-center justify-center h-9 w-9 rounded-lg z-50 flex-shrink-0 transform hover:scale-105 active:scale-95" 
                        :class="{'bg-blue-50 text-blue-600': sidebarOpen, 'hover:bg-gray-50': !sidebarOpen}">
                            <svg class="h-6 w-6 transition-all duration-300 ease-in-out" fill="none" viewBox="0 0 24 24" stroke="currentColor" 
                            :class="{'rotate-90 text-blue-600': sidebarOpen, 'rotate-0 text-gray-700': !sidebarOpen}">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>

                        <!-- School Logo and Name -->
                        <div class="flex items-center min-w-0 flex-1">
                            <h1 class="text-base sm:text-lg font-bold text-gray-800 truncate">
                                SMK PGRI CIKAMPEK
                            </h1>
                        </div>
                        
                        <!-- Date Display -->
                        <div class="hidden lg:flex items-center ml-4 space-x-1 bg-blue-50 px-3 py-1.5 rounded-lg flex-shrink-0">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                            <span class="ml-2 text-sm font-medium text-gray-700"><?php echo e(now()->isoFormat('dddd, D MMMM Y')); ?></span>
                        </div>
                        
                        <!-- Mobile Date Display -->
                        <div class="lg:hidden flex items-center ml-2 text-xs sm:text-sm text-gray-600 flex-shrink-0">
                            <i class="fas fa-calendar-day text-blue-600 mr-1"></i>
                            <span class="inline"><?php echo e(now()->format('d/m/Y')); ?></span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                        <!-- Breadcrumb -->
                        <div class="hidden md:flex items-center text-sm px-3 py-1.5 bg-gray-50 rounded-lg">
                            <a href="<?php echo e(route('siswa.dashboard')); ?>" class="text-blue-600 font-medium">Dashboard</a>
                            <?php if(!request()->routeIs('siswa.dashboard')): ?>
                            <span class="mx-2 text-gray-400">/</span>
                            <span class="text-gray-600">
                                <?php if(request()->segment(2)): ?>
                                    <?php echo e(ucfirst(request()->segment(2))); ?>

                                <?php endif; ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- User Menu Dropdown -->
                     <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-1 sm:space-x-2 focus:outline-none" id="user-menu-button">
                            <div class="hidden sm:block">
                            </div>
                            <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full ring-2 ring-blue-500 p-0.5 bg-white overflow-hidden flex-shrink-0">
                                <?php
                                    $siswa = Auth::guard('siswa')->user();
                                    $fotoProfil = $siswa && !empty($siswa->foto) ? asset('storage/' . $siswa->foto) : null;
                                    $namaSiswa = $siswa->nama_lengkap ?? 'Siswa';
                                    $initials = '';
                                    if ($namaSiswa && !$fotoProfil) {
                                        $parts = preg_split('/\s+/', trim($namaSiswa));
                                        if (count($parts) > 1) {
                                            $initials = strtoupper(mb_substr($parts[0],0,1) . mb_substr($parts[1],0,1));
                                        } else {
                                            $initials = strtoupper(mb_substr($parts[0],0,1));
                                        }
                                    }
                                ?>
                                <?php if($fotoProfil): ?>
                                    <img src="<?php echo e($fotoProfil); ?>" class="h-full w-full rounded-full object-cover" alt="<?php echo e($namaSiswa); ?>">
                                <?php elseif($initials): ?>
                                    <div class="h-full w-full rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-base"><?php echo e($initials); ?></div>
                                <?php else: ?>
                                    <div class="h-full w-full rounded-full bg-blue-100 flex items-center justify-center"><i class="fas fa-user-graduate text-lg text-blue-400"></i></div>
                                <?php endif; ?>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden sm:block"></i>
                        </button>

                    <!-- User Dropdown Menu -->
                        <div x-show="open" x-cloak
                            @click.away="open = false" 
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg z-50 py-2 border border-gray-100">
                                <a href="<?php echo e(route('siswa.profile.index')); ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 group">
                                    <i class="fas fa-user-circle mr-2 text-blue-500 group-hover:text-blue-600"></i> Profil Saya
                                </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="/siswa/logout">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 group">
                                    <i class="fas fa-sign-out-alt mr-2 group-hover:text-red-700"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 bg-gray-100 p-3 sm:p-4 lg:p-6 mt-10 transition-all duration-300 ease-in-out"
                :class="{ 
                    'ml-0 w-full': !sidebarOpen, 
                    'lg:ml-64': sidebarOpen,
                    'main-content-mobile-free': isMobile && !sidebarOpen
                }"
                @click.stop="">
                <div class="main-content-container transition-all duration-300 origin-right w-full min-h-full overflow-visible"
                     @click.stop="">
                    <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 rounded-r-lg shadow-sm flex items-center" role="alert" x-data="{show: true}" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                        <i class="fas fa-check-circle text-green-500 mr-3 flex-shrink-0"></i>
                        <div class="flex-grow text-sm"><?php echo e(session('success')); ?></div>
                        <button @click="show = false" class="text-green-700 hover:text-green-900 focus:outline-none ml-2 flex-shrink-0">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 rounded-r-lg shadow-sm flex items-center" role="alert" x-data="{show: true}" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3 flex-shrink-0"></i>
                        <div class="flex-grow text-sm"><?php echo e(session('error')); ?></div>
                        <button @click="show = false" class="text-red-700 hover:text-red-900 focus:outline-none ml-2 flex-shrink-0">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php endif; ?>

                    <!-- Main Content Area (without white container) -->
                    <?php if (! empty(trim($__env->yieldContent('content')))): ?>
                        <?php echo $__env->yieldContent('content'); ?>
                    <?php else: ?>
                        <?php echo $__env->yieldContent('main-content'); ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }" x-cloak><?php /**PATH C:\wamp64\www\website-smk3\resources\views\layouts\siswa.blade.php ENDPATH**/ ?>