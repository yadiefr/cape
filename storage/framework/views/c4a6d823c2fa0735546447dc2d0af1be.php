<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard - SMK PGRI CIKAMPEK'); ?></title>
    
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
        
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Force Tailwind CDN for immediate styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (window.tailwind) {
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'outfit': ['Outfit', 'sans-serif'],
                            'sans': ['Outfit', 'sans-serif']
                        }
                    }
                }
            };
        }
    </script>

    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Font settings for the entire admin page */
        html, body {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Ensure all text elements inherit the Outfit font */
        h1, h2, h3, h4, h5, h6, p, span, a, button, input, select, textarea, div, table {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Heading styles */
        h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }
        
        h2, h3 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }
        
        h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 500;
        }
        
        /* Button and interactive elements */
        button, a, .btn {
            font-family: 'Outfit', sans-serif;
            font-weight: 500;
        }
        
        /* Text paragraphs */
        p, div, span {
            font-family: 'Outfit', sans-serif;
            font-weight: 400;
        }
        
        /* Font class utilities */
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
        
        .text-base {
            font-size: 1rem;
            font-family: 'Outfit', sans-serif;
        }

        @media (min-width: 1024px) {
            .sidebar-desktop {
                position: fixed !important;
                height: calc(100% - 4rem) !important;
                top: 4rem !important;
                z-index: 20;
                width: 16rem;
                background-color: white;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Content area styling */
            main {
                transition: all 0.3s ease;
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
                z-index: 30;
                width: 16rem;
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
        }
        
        /* Toggle Animation */
        @keyframes sidebarOpen {
            0% { transform: translateX(-16rem); }
            100% { transform: translateX(0); }
        }
        
        @keyframes sidebarClose {
            0% { transform: translateX(0); }
            100% { transform: translateX(-16rem); }
        }
        
        .sidebar-slide-in {
            animation: sidebarOpen 0.3s forwards;
        }
        
        .sidebar-slide-out {
            animation: sidebarClose 0.3s forwards;
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
            height: auto; /* Allow content to determine height */
            flex-grow: 1;
            font-family: 'Outfit', sans-serif;
        }
        
        /* FIX FOR SCROLLING ISSUE */
        html, body {
            height: 100%;
        }
        
        /* Animation */
        @keyframes wave {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(10deg); }
            60% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-wave {
            animation: wave 2s ease-in-out infinite;
            transform-origin: 70% 70%;
            display: inline-block;
        }
        
        /* Sidebar item */
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 2px;
            transition: all 0.2s ease;
            color: #4b5563;
        }
        
        .sidebar-item:hover, .sidebar-item.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .sidebar-item i {
            font-size: 16px;
            width: 20px;
        }
        
        /* Additional styles for better dashboard organization */
        .admin-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .admin-card-header {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .admin-card-body {
            padding: 1rem;
        }
        
        /* Fix for disorganized content */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
            width: 100%;
        }
        
        /* Ensure tables are properly formatted */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .admin-table thead th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #4b5563;
        }
        
        /* Fix for floated elements */
        .clear-floats:after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Mobile sidebar specific styles */
        @media (max-width: 1023px) {
            .fixed-sidebar-mobile {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 16rem;
                z-index: 30;
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
            
            /* Force sidebar to take full height on mobile */
            aside {
                height: 100vh !important;
                top: 0 !important;
                padding-top: rem !important;
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
    
    <?php echo $__env->yieldContent('styles'); ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebarData', () => ({
                sidebarOpen: window.innerWidth >= 1024, // Default to open on desktop, closed on mobile
                isMobile: window.innerWidth < 1024,
                
                init() {
                    this.checkScreenSize();
                    window.addEventListener('resize', () => {
                        this.checkScreenSize();
                    });
                    
                    // Initialize sidebar and content state
                    this.$nextTick(() => {
                        const sidebar = document.querySelector('aside');
                        const contentContainer = document.querySelector('.main-content-container');
                        const mainContent = document.querySelector('main');
                        
                        if (sidebar) {
                            if (this.sidebarOpen) {
                                // Initialize with sidebar open
                                sidebar.classList.add('sidebar-slide-in');
                                sidebar.classList.remove('sidebar-slide-out');
                                sidebar.style.transform = 'translateX(0)';
                                
                                // Set main content position when sidebar is open
                                if (mainContent && window.innerWidth >= 1024) {
                                    mainContent.classList.remove('ml-0');
                                    mainContent.classList.add('lg:ml-64');
                                }
                            } else {
                                // Initialize with sidebar closed
                                sidebar.classList.remove('sidebar-slide-in');
                                sidebar.classList.add('sidebar-slide-out');
                                sidebar.style.transform = 'translateX(-16rem)';
                                
                                // Set main content position when sidebar is closed
                                if (mainContent && window.innerWidth >= 1024) {
                                    mainContent.classList.add('ml-0');
                                    mainContent.classList.remove('lg:ml-64');
                                }
                            }
                        }
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
                            sidebar.classList.add('sidebar-slide-in');
                            sidebar.classList.remove('sidebar-slide-out');
                            sidebar.style.transform = 'translateX(0)';
                        }
                    } else if (window.innerWidth < 1024) {
                        // Mobile behavior - preserve current sidebar state but ensure proper styling
                        // Don't automatically close the sidebar on resize - let the user control it
                        if (mainContent) {
                            mainContent.classList.add('ml-0');
                            mainContent.classList.remove('lg:ml-64');
                        }
                        if (sidebar) {
                            if (this.sidebarOpen) {
                                sidebar.classList.add('sidebar-slide-in');
                                sidebar.classList.remove('sidebar-slide-out');
                                sidebar.style.transform = 'translateX(0)';
                            } else {
                                sidebar.classList.remove('sidebar-slide-in');
                                sidebar.classList.add('sidebar-slide-out');
                                sidebar.style.transform = 'translateX(-16rem)';
                            }
                        }
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
                                // Keep z-index below header (z-50)
                                sidebar.style.zIndex = '30';
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
<body class="bg-gray-100 text-gray-800 antialiased sidebar-expanded font-outfit" x-data="sidebarData">
    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="fixed top-20 right-4 z-50 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-md rounded-r-md" role="alert" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 5000)">
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
    <div class="fixed top-20 right-4 z-50 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-md rounded-r-md" role="alert" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 5000)">
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
            <div class="fixed inset-0 z-20 transition-opacity ease-linear duration-300" 
                :class="{'opacity-100 block': sidebarOpen && window.innerWidth < 1024, 'opacity-0 hidden': !sidebarOpen || window.innerWidth >= 1024}"
                @click="sidebarOpen = false">
                <div class="absolute inset-0 bg-gray-900 backdrop-blur-sm opacity-60"></div>
            </div>

            <!-- Sidebar -->
            <aside class="fixed top-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-all duration-300 ease-in-out h-full pt-16 lg:pt-0 lg:top-16 lg:h-[calc(100%-4rem)] lg:sidebar-desktop" 
                :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen, 'sidebar-slide-in': sidebarOpen, 'sidebar-slide-out': !sidebarOpen}">

                <!-- Sidebar Content -->
                <div class="overflow-y-auto h-full lg:h-full scrollbar-thin scrollbar-thumb-blue-200 scrollbar-track-gray-50">
                    <nav class="p-3 pt-2">
                        <ul class="space-y-0.5">
                            <li>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-tachometer-alt <?php echo e(request()->routeIs('admin.dashboard') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            
                            <li class="pt-2">
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2">Konten Website</div>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.hero.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.hero.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-image <?php echo e(request()->routeIs('admin.hero.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Hero Banner</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.hero-background.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.hero-background.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-photo-video <?php echo e(request()->routeIs('admin.hero-background.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Hero Background</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.keunggulan.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.keunggulan.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-star <?php echo e(request()->routeIs('admin.keunggulan.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Keunggulan</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.galeri.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.galeri.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-images <?php echo e(request()->routeIs('admin.galeri.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Galeri</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.berita.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.berita.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-newspaper <?php echo e(request()->routeIs('admin.berita.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Berita</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.agenda.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.agenda.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-calendar-alt <?php echo e(request()->routeIs('admin.agenda.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Agenda</span>
                                </a>
                            </li>
                                                        
                            <li class="pt-2">
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2">Akademik</div>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.guru.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.guru.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-chalkboard-teacher <?php echo e(request()->routeIs('admin.guru.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Manajemen Guru</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.jurusan.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.jurusan.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-graduation-cap <?php echo e(request()->routeIs('admin.jurusan.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Jurusan</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.kelas.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.kelas.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-school <?php echo e(request()->routeIs('admin.kelas.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Manajemen Kelas</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.siswa.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.siswa.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-user-graduate <?php echo e(request()->routeIs('admin.siswa.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Manajemen Siswa</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.matapelajaran.index')); ?>" data-no-spa="true" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.matapelajaran.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-book <?php echo e(request()->routeIs('admin.matapelajaran.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Mata Pelajaran</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.jadwal.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-clock <?php echo e(request()->routeIs('admin.jadwal.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Jadwal Pelajaran</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo e(route('admin.absensi.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.absensi.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-clipboard-check <?php echo e(request()->routeIs('admin.absensi.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Kehadiran</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.pkl.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.pkl.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-briefcase <?php echo e(request()->routeIs('admin.pkl.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Praktek Kerja Lapangan</span>
                                </a>
                            </li>
                                                        
                            <li>
                                <a href="<?php echo e(route('admin.nilai.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.nilai.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-chart-line <?php echo e(request()->routeIs('admin.nilai.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Penilaian</span>
                                </a>
                            </li>                            
                            
                            <li>
                                <a href="<?php echo e(route('admin.ujian.dashboard')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>" data-no-spa="true">
                                    <i class="fas fa-graduation-cap <?php echo e(request()->routeIs('admin.ujian.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Ujian Online</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('admin.ppdb.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ppdb.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-user-plus <?php echo e(request()->routeIs('admin.ppdb.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>PPDB</span>
                                </a>
                            </li>
                            
                            <li class="pt-2">
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2">Lainnya</div>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.bel.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.bel.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-bell <?php echo e(request()->routeIs('admin.bel.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Bel Sekolah</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.pengumuman.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.pengumuman.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-bullhorn <?php echo e(request()->routeIs('admin.pengumuman.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Pengumuman</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.keuangan.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.keuangan.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-money-bill-wave <?php echo e(request()->routeIs('admin.keuangan.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Keuangan</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.analytics')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.analytics') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-chart-bar <?php echo e(request()->routeIs('admin.analytics') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Analitik Website</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.settings.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.settings.index') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-cog <?php echo e(request()->routeIs('admin.settings.index') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Pengaturan</span>
                                </a>
                            </li>

                            <li class="pt-2">
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2">Sistem</div>
                            </li>

                            <li>
                                <a href="<?php echo e(route('admin.admin-users.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.admin-users.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-users-cog <?php echo e(request()->routeIs('admin.admin-users.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Manajemen Admin</span>
                                </a>
                            </li>
                        </ul>
                        
                        <div class="mt-8 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:shadow-md transition-all">
                            <div class="flex items-center justify-center text-blue-500 mb-3">
                                <i class="fas fa-headset text-xl"></i>
                            </div>
                            <p class="text-sm text-center text-gray-700">Butuh bantuan? Hubungi tim support kami</p>
                            <a href="<?php echo e(route('admin.support')); ?>" class="block w-full mt-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-blue-200 rounded-lg py-2 text-center text-sm text-white hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
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
            <header class="bg-white shadow-md z-50 fixed top-0 left-0 w-full h-16 transition-all duration-300 ease-in-out">
                <div class="px-6 py-3 flex justify-between items-center h-full">
                    <div class="flex items-center">
                        <!-- Sidebar Toggle Button with Animation -->
                        <button @click="toggleSidebar()" type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none mr-4 transition-all duration-300 ease-in-out flex items-center justify-center h-9 w-9 rounded-lg z-50" 
                        :class="{'bg-blue-50': sidebarOpen}">
                            <svg class="h-6 w-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" 
                            :class="{'text-blue-600 rotate-90': sidebarOpen, 'text-gray-700': !sidebarOpen}">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>
                        
                        <!-- Mobile Menu Button (Hidden on desktop) -->
                        <button @click="toggleSidebar()" type="button" 
                            class="fixed bottom-6 right-6 lg:hidden bg-blue-600 hover:bg-blue-700 text-white p-3.5 rounded-full shadow-lg z-50 flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                            style="box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                :class="{'hidden': sidebarOpen}">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                :class="{'hidden': !sidebarOpen}">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        
                        <!-- School Logo and Name -->
                        <div class="flex items-center">
                            <h1 class="text-lg font-bold text-gray-800 hidden sm:block">
                                SMK PGRI CIKAMPEK
                            </h1>
                            <h1 class="text-lg font-bold text-gray-800 sm:hidden">
                                SMK PGRI
                            </h1>
                        </div>
                        
                        <!-- Date and Time Display -->
                        <div class="hidden md:flex items-center ml-6 space-x-3">
                            <!-- Live Clock -->
                            <div class="bg-gradient-to-r from-gray-800 to-gray-700 text-white px-3 py-1.5 rounded-lg font-mono flex items-center shadow-sm">
                                <i class="fas fa-clock mr-2 text-blue-400"></i>
                                <span id="header-clock" class="tracking-wider">00:00:00</span>
                            </div>
                            
                            <!-- Date Display -->
                            <div class="bg-blue-50 px-3 py-1.5 rounded-lg flex items-center">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                                <span class="ml-2 text-sm font-medium text-gray-700"><?php echo e(now()->isoFormat('dddd, D MMMM Y')); ?></span>
                            </div>
                        </div>
                        
                        <!-- Mobile Date Display -->
                        <div class="md:hidden flex items-center ml-4 space-x-2">
                            <div class="bg-gray-800 text-white px-2 py-1 rounded font-mono text-sm flex items-center">
                                <span id="header-clock-mobile" class="tracking-wider">00:00</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-calendar-day text-blue-600 mr-1"></i>
                                <span><?php echo e(now()->format('d/m/Y')); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Breadcrumb (Hidden on small screens) -->
                        <div class="hidden sm:flex items-center text-sm px-3 py-1.5 bg-gray-50 rounded-lg">
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-blue-600 font-medium">Dashboard</a>
                            <?php if(!request()->routeIs('admin.dashboard')): ?>
                            <span class="mx-2 text-gray-400">/</span>
                            <span class="text-gray-600">
                                <?php if(request()->segment(2)): ?>
                                    <?php echo e(ucfirst(request()->segment(2))); ?>

                                <?php endif; ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Divider -->
                        <div class="h-8 w-0.5 bg-gray-200 hidden sm:block"></div>

                        <!-- User Menu Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none" id="user-menu-button">
                                <div class="hidden sm:block">
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm font-medium text-gray-800"><?php echo e(Auth::user()->name ?? 'Admin'); ?></span>
                                        <span class="text-xs text-blue-600 font-medium">Admin</span>
                                    </div>
                                </div>
                                <div class="h-10 w-10 rounded-full ring-2 ring-blue-500 p-0.5 bg-white overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e(Auth::user()->name ?? 'Admin'); ?>&background=3b82f6&color=ffffff" 
                                        alt="Admin Avatar" class="h-full w-full rounded-full object-cover"/>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            
                            <!-- User Dropdown Menu -->
                            <div x-show="open" 
                                @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100" 
                                x-transition:enter-start="transform opacity-0 scale-95" 
                                x-transition:enter-end="transform opacity-100 scale-100" 
                                x-transition:leave="transition ease-in duration-75" 
                                x-transition:leave-start="transform opacity-100 scale-100" 
                                x-transition:leave-end="transform opacity-0 scale-95" 
                                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg z-50 py-2 border border-gray-100">
                                <!-- User Menu Items -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-xs text-gray-500">Selamat <?php echo e(now()->format('H') < 12 ? 'Pagi' : (now()->format('H') < 15 ? 'Siang' : (now()->format('H') < 18 ? 'Sore' : 'Malam'))); ?>,</p>
                                    <p class="text-sm font-semibold text-gray-800"><?php echo e(Auth::user()->name ?? 'Admin'); ?></p>
                                </div>
                                <a href="<?php echo e(route('admin.profile.index')); ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 group">
                                    <i class="fas fa-user-circle mr-2 text-blue-500 group-hover:text-blue-600"></i> Profil Saya
                                </a>
                                <a href="<?php echo e(route('admin.settings.index')); ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 group">
                                    <i class="fas fa-cog mr-2 text-blue-500 group-hover:text-blue-600"></i> Pengaturan
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
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
            <main class="flex-1 overflow-y-visible bg-gray-100 p-6 mt-16 transition-all duration-300 ease-in-out"
                :class="{ 'ml-0 w-full': !sidebarOpen, 'lg:ml-64': sidebarOpen }">
                <div 
                    class="main-content-container transition-all duration-300 origin-right rounded-xl shadow-sm w-full"
                    id="admin-main-content"
                >
                    <?php if (! empty(trim($__env->yieldContent('content')))): ?>
                        <?php echo $__env->yieldContent('content'); ?>
                    <?php else: ?>
                        <?php echo $__env->yieldContent('main-content'); ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Admin Notifications -->
    <script src="<?php echo e(asset('js/admin-notification.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Admin SPA Navigation -->
    <script src="<?php echo e(asset('js/admin-spa-navigation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Header Live Clock -->
    <script>
        // Live clock functionality
        function updateHeaderClock() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            
            // Update desktop clock
            const headerClock = document.getElementById('header-clock');
            if (headerClock) {
                headerClock.textContent = `${hours}:${minutes}:${seconds}`;
            }
            
            // Update mobile clock (without seconds to save space)
            const mobileHeaderClock = document.getElementById('header-clock-mobile');
            if (mobileHeaderClock) {
                mobileHeaderClock.textContent = `${hours}:${minutes}`;
            }
        }
        
        // Update clock every second
        setInterval(updateHeaderClock, 1000);
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', updateHeaderClock);
    </script>

    <!-- Sistem Bel Otomatis dengan Iframe (Khusus Admin) -->
    <?php echo $__env->make('partials.bel-system-iframe', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views/layouts/admin.blade.php ENDPATH**/ ?>