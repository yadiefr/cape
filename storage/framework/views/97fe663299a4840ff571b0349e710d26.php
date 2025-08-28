<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Guru - SMK PGRI CIKAMPEK'); ?></title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Browser compatibility fixes -->
    <link href="<?php echo e(asset('css/compatibility-fixes.css')); ?>" rel="stylesheet">
    
    <!-- Admin Fallback CSS untuk memastikan basic styling selalu ada -->
    <link href="<?php echo e(asset('css/admin-fallback.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">

    <!-- Tailwind CSS (Production Build) -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }

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
                height: calc(100vh - 4rem) !important;
                overflow-y: auto !important;
                z-index: 30 !important;
                width: 16rem;
                padding-top: 0 !important;
            }
            
            .sidebar-mobile nav {
                padding-bottom: 3rem !important;
            }
            
            .sidebar-mobile .space-y-8 {
                padding-bottom: 2rem !important;
            }
            
            /* Ensure mobile sidebar scrolling */
            .sidebar-mobile .overflow-y-auto {
                overflow-y: auto !important;
            }
            
            /* Mobile header adjustments */
            header {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            /* Mobile main content */
            main {
                padding: 1rem !important;
                margin-top: 4rem !important;
            }
            
            .main-content-container {
                padding: 1rem !important;
                border-radius: 0.5rem !important;
            }
            
            /* Mobile table responsiveness */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table-responsive table {
                min-width: 100%;
                white-space: nowrap;
            }
            
            /* Mobile form adjustments */
            .form-grid-mobile {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            /* Mobile button adjustments */
            .btn-mobile-full {
                width: 100% !important;
                justify-content: center !important;
            }
            
            /* Mobile card spacing */
            .card-mobile-spacing {
                margin-bottom: 1rem !important;
            }
        }
        
        /* Extra small screens (phones in portrait) */
        @media (max-width: 480px) {
            header {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            main {
                padding: 0.75rem !important;
            }
            
            .main-content-container {
                padding: 0.75rem !important;
            }
            
            /* Hide some elements on very small screens */
            .hide-xs {
                display: none !important;
            }
            
            /* Adjust font sizes for mobile */
            .text-mobile-sm {
                font-size: 0.875rem !important;
            }
            
            .text-mobile-xs {
                font-size: 0.75rem !important;
            }
        }
        
        /* For smooth transition of the main container */
        .main-content-container {
            transform-origin: right center;
            will-change: width;
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            height: auto;
            flex-grow: 1;
        }
        
        /* FIX FOR SCROLLING ISSUE */
        html, body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }
        
        /* Mobile sidebar specific styles */
        @media (max-width: 1023px) {
            .fixed-sidebar-mobile {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 16rem;
                z-index: 50;
                overflow-y: auto;
            }
            
            body.overflow-hidden {
                overflow: hidden;
            }
            
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 20;
            }
            
            /* Improved mobile sidebar */
            aside.translate-x-0 {
                box-shadow: 0 0 25px rgba(0, 0, 0, 0.15) !important;
            }
            
            /* Force sidebar positioning on mobile */
            @media (max-width: 1023px) {
                aside.fixed {
                    z-index: 30 !important;
                    top: 4rem !important;
                    height: calc(100vh - 4rem) !important;
                }
                
                aside.translate-x-0 {
                    z-index: 30 !important;
                }
            }
            
            /* Animation classes for sidebar */
            .sidebar-slide-in {
                animation: slideInMobile 0.3s forwards;
            }
            
            .sidebar-slide-out {
                animation: slideOutMobile 0.3s forwards;
            }
            
            @keyframes slideInMobile {
                from { transform: translateX(-16rem); }
                to { transform: translateX(0); }
            }
            
            @keyframes slideOutMobile {
                from { transform: translateX(0); }
                to { transform: translateX(-16rem); }
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Prevent sidebar flash on mobile -->
    <script>
        // Immediately hide sidebar on mobile and ensure header is stable before Alpine loads
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('aside');
            const header = document.querySelector('header');
            
            // Handle sidebar on mobile
            if (window.innerWidth < 1024 && sidebar) {
                sidebar.style.transition = 'none';
                sidebar.style.transform = 'translateX(-100%)';
            }
            
            // Ensure header is stable
            if (header) {
                header.style.transition = 'none';
                header.style.opacity = '1';
                header.style.transform = 'translateY(0)';
            }
        });
    </script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebarData', () => ({
                sidebarOpen: false, // Always start closed to prevent flash
                isMobile: window.innerWidth < 1024,
                
                init() {
                    // Immediately set correct state without any transition
                    this.isMobile = window.innerWidth < 1024;
                    this.sidebarOpen = !this.isMobile; // Open only on desktop
                    
                    // Set initial state immediately without any animation
                    const sidebar = document.querySelector('aside');
                    const mainContent = document.querySelector('main');
                    
                    if (sidebar) {
                        // Temporarily disable transitions during init
                        sidebar.style.transition = 'none';
                        
                        // Set immediate position based on screen size
                        if (this.isMobile) {
                            sidebar.style.transform = 'translateX(-100%)'; // Hidden on mobile
                            this.sidebarOpen = false;
                        } else {
                            sidebar.style.transform = 'translateX(0)'; // Visible on desktop
                            this.sidebarOpen = true;
                            if (mainContent) {
                                mainContent.classList.add('lg:ml-64');
                                mainContent.classList.remove('ml-0');
                            }
                        }
                        
                        // Restore transition after init is complete
                        setTimeout(() => {
                            sidebar.style.transition = 'transform 0.2s ease';
                        }, 100);
                    }
                    
                    this.checkScreenSize();
                    window.addEventListener('resize', () => {
                        this.checkScreenSize();
                    });
                },
                
                checkScreenSize() {
                    const mainContent = document.querySelector('main');
                    const sidebar = document.querySelector('aside');
                    
                    // Update mobile state
                    this.isMobile = window.innerWidth < 1024;
                    
                    if (window.innerWidth >= 1024) {
                        // Desktop behavior - sidebar always open
                        this.sidebarOpen = true;
                        if (mainContent) {
                            mainContent.classList.remove('ml-0');
                            mainContent.classList.add('lg:ml-64');
                        }
                        if (sidebar) {
                            sidebar.style.transform = 'translateX(0)';
                        }
                    } else if (window.innerWidth < 1024) {
                        // Mobile behavior - keep current sidebar state, just adjust content
                        if (mainContent) {
                            mainContent.classList.add('ml-0');
                            mainContent.classList.remove('lg:ml-64');
                        }
                        // Don't automatically close sidebar on mobile - let user control it
                    }
                },
                
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    
                    // Get the sidebar element and content container
                    const sidebar = document.querySelector('aside');
                    const contentContainer = document.querySelector('.main-content-container');
                    const mainContent = document.querySelector('main');
                    const isMobile = window.innerWidth < 1024;
                    
                    // Apply animation to sidebar
                    if (sidebar) {
                        if (this.sidebarOpen) {
                            // When sidebar opens
                            sidebar.classList.add('sidebar-slide-in');
                            sidebar.classList.remove('sidebar-slide-out');
                            sidebar.style.transform = 'translateX(0)';
                            
                            // Add extra mobile styles
                            if (isMobile) {
                                sidebar.style.boxShadow = '2px 0 20px rgba(0, 0, 0, 0.1)';
                            }
                        } else {
                            // When sidebar closes
                            sidebar.classList.remove('sidebar-slide-in');
                            sidebar.classList.add('sidebar-slide-out');
                            sidebar.style.transform = 'translateX(-16rem)';
                            
                            if (isMobile) {
                                sidebar.style.boxShadow = 'none';
                            }
                        }
                    }
                    
                    // Update the main content width explicitly - only for desktop
                    if (mainContent && !isMobile) {
                        if (this.sidebarOpen) {
                            mainContent.classList.remove('ml-0');
                            mainContent.classList.add('lg:ml-64');
                        } else {
                            mainContent.classList.add('ml-0');
                            mainContent.classList.remove('lg:ml-64');
                        }
                    }
                    
                    // Add a class to body to prevent scrolling when sidebar is open on mobile
                    if (isMobile) {
                        if (this.sidebarOpen) {
                            document.body.classList.add('overflow-hidden');
                        } else {
                            setTimeout(() => {
                                document.body.classList.remove('overflow-hidden');
                            }, 300); // Wait for transition to complete before re-enabling scroll
                        }
                    }
                    
                    // Document body class for layout adjustments
                    if (this.sidebarOpen) {
                        document.body.classList.add('sidebar-expanded');
                        document.body.classList.remove('sidebar-collapsed');
                    } else {
                        document.body.classList.add('sidebar-collapsed');
                        document.body.classList.remove('sidebar-expanded');
                    }
                }
            }))
        })
    </script>
</head>
<body class="bg-gray-100 text-gray-800 antialiased sidebar-expanded" x-data="sidebarData">
    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="fixed top-4 right-4 z-50 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-md rounded-r-md" role="alert" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex items-center">
            <span class="material-symbols-outlined mr-2">check_circle</span>
            <p><?php echo e(session('success')); ?></p>
            <button @click="show = false" class="ml-4 text-green-800 hover:text-green-900">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
    <div class="fixed top-4 right-4 z-50 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-md rounded-r-md" role="alert" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex items-center">
            <span class="material-symbols-outlined mr-2">error</span>
            <p><?php echo e(session('error')); ?></p>
            <button @click="show = false" class="ml-4 text-red-800 hover:text-red-900">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar -->
        <div x-cloak class="relative flex-shrink-0">
            <!-- Overlay untuk mobile ketika sidebar dibuka -->
            <div class="fixed inset-0 z-20 transition-opacity ease-linear duration-100" 
                :class="{'opacity-100 block': sidebarOpen && window.innerWidth < 1024 && isMobile, 'opacity-0 hidden': !sidebarOpen || window.innerWidth >= 1024}"
                @click="sidebarOpen = false">
                <div class="absolute inset-0 bg-gray-900 backdrop-blur-sm opacity-60"></div>
            </div>
            
            <!-- Sidebar -->
            <aside class="fixed top-16 left-0 z-30 w-64 bg-white shadow-lg h-[calc(100vh-4rem)] lg:pt-0 lg:top-16 lg:h-[calc(100%-4rem)] lg:sidebar-desktop lg:translate-x-0" 
                :class="{ 'translate-x-0': sidebarOpen && window.innerWidth < 1024, '-translate-x-full': !sidebarOpen && window.innerWidth < 1024 }"
                style="transition: transform 0.2s ease;">

                <!-- Sidebar Content -->
                <div class="overflow-y-auto h-full lg:h-full scrollbar-thin scrollbar-thumb-blue-200 scrollbar-track-gray-50">
                    <nav class="p-3 pt-2">
                        <ul class="space-y-0.5">
                            <li>
                                <a href="<?php echo e(route('guru.dashboard')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-tachometer-alt <?php echo e(request()->routeIs('guru.dashboard') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            
                            <li class="pt-2">
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2">Akademik</div>
                            </li>
                            <li>
                                <a href="<?php echo e(route('guru.jadwal.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.jadwal*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-calendar <?php echo e(request()->routeIs('guru.jadwal*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Jadwal Mengajar</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('guru.nilai.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.nilai*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-star <?php echo e(request()->routeIs('guru.nilai*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Nilai Siswa</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('guru.absensi.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.absensi*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-clipboard-check <?php echo e(request()->routeIs('guru.absensi*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Absensi</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('guru.siswa.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.siswa*') && !request()->routeIs('guru.wali-kelas*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-user-graduate <?php echo e(request()->routeIs('guru.siswa*') && !request()->routeIs('guru.wali-kelas*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Data Siswa</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('guru.kelas.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.kelas*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-users <?php echo e(request()->routeIs('guru.kelas*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Daftar Kelas</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('guru.materi.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.materi*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-book <?php echo e(request()->routeIs('guru.materi*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Materi & Tugas</span>
                                </a>
                            </li>
                                    
                            <?php if(Auth::guard('guru')->check() && Auth::guard('guru')->user()->is_wali_kelas): ?>
                            <li>
                                <a href="<?php echo e(route('guru.wali-kelas.dashboard')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.wali-kelas*') ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium shadow-sm' : 'hover:bg-purple-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-chalkboard-teacher <?php echo e(request()->routeIs('guru.wali-kelas*') ? '' : 'text-purple-600 group-hover:text-purple-700'); ?>"></i>
                                    <span class="flex items-center">
                                        Wali Kelas
                                        <span class="ml-2 px-2 py-0.5 text-xs bg-purple-100 text-purple-800 rounded-full">Khusus</span>
                                    </span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <li class="pt-2">
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2">Lainnya</div>
                            </li>
                            <li>
                                <a href="<?php echo e(route('guru.pengumuman.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.pengumuman*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-bullhorn <?php echo e(request()->routeIs('guru.pengumuman*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Pengumuman</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('guru.profile.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('guru.profile.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-user-circle <?php echo e(request()->routeIs('guru.profile.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Profil Saya</span>
                                </a>
                            </li>
                        </ul>
                        
                        <div class="mt-8 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:shadow-md transition-all">
                            <div class="flex items-center justify-center text-blue-500 mb-3">
                                <i class="fas fa-headset text-xl"></i>
                            </div>
                            <p class="text-sm text-center text-gray-700">Butuh bantuan? Hubungi administrator</p>
                            <a href="<?php echo e(route('guru.dashboard')); ?>" class="block w-full mt-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-blue-200 rounded-lg py-2 text-center text-sm text-white hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                                Bantuan
                            </a>
                        </div>
                    </nav>
                </div>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col w-full h-full flex-grow">
            <!-- Top Navigation -->
            <header class="bg-white shadow-md z-40 fixed top-0 left-0 w-full h-16">
                <div class="px-4 sm:px-6 py-3 flex justify-between items-center h-full">
                    <div class="flex items-center flex-1 min-w-0">
                        <!-- Sidebar Toggle Button with Animation -->
                        <button @click="toggleSidebar()" type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none mr-3 sm:mr-4 transition-colors duration-200 ease-in-out flex items-center justify-center h-9 w-9 rounded-lg z-50 flex-shrink-0" 
                        :class="{'bg-blue-50': sidebarOpen}">
                            <svg class="h-6 w-6 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" 
                            :class="{'text-blue-600 rotate-90': sidebarOpen, 'text-gray-700': !sidebarOpen}">
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
                            <span class="hidden xs:inline"><?php echo e(now()->format('d/m/Y')); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">

                        <!-- User Menu Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-1 sm:space-x-2 focus:outline-none" id="user-menu-button">
                                <div class="hidden sm:block">
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm font-medium text-gray-800 truncate max-w-24 lg:max-w-none"><?php echo e(auth()->user()->name ?? 'Guru'); ?></span>
                                        <span class="text-xs text-blue-600 font-medium">Guru</span>
                                    </div>
                                </div>
                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full ring-2 ring-blue-500 p-0.5 bg-white overflow-hidden flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name)); ?>&background=3b82f6&color=ffffff" 
                                        class="h-full w-full rounded-full object-cover"
                                        alt="<?php echo e(auth()->user()->name); ?>">
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500 hidden sm:block"></i>
                            </button>
                              <!-- User Dropdown Menu -->
                            <div x-show="open" x-cloak
                                @click.away="open = false" 
                                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg z-50 py-2 border border-gray-100">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-xs text-gray-500">Selamat <?php echo e(now()->format('H') < 12 ? 'Pagi' : (now()->format('H') < 15 ? 'Siang' : (now()->format('H') < 18 ? 'Sore' : 'Malam'))); ?>,</p>
                                    <p class="text-sm font-semibold text-gray-800"><?php echo e(auth()->user()->name ?? 'Guru'); ?></p>
                                </div>
                                <a href="<?php echo e(route('guru.profile.index')); ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 group">
                                    <i class="fas fa-user-circle mr-2 text-blue-500 group-hover:text-blue-600"></i> Profil Saya
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>                                
                                <form method="POST" action="/guru/logout">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 group">
                                        <i class="fas fa-sign-out-alt mr-2 group-hover:text-red-700"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-visible bg-gray-100 p-3 sm:p-4 lg:p-6 mt-16 transition-all duration-300 ease-in-out"
                :class="{ 'ml-0 w-full': !sidebarOpen, 'lg:ml-64': sidebarOpen }">
                <div class="main-content-container transition-all duration-300 origin-right rounded-xl shadow-sm w-full">


                    <?php if (! empty(trim($__env->yieldContent('content')))): ?>
                        <?php echo $__env->yieldContent('content'); ?>
                    <?php else: ?>
                        <?php echo $__env->yieldContent('main-content'); ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\layouts\guru.blade.php ENDPATH**/ ?>