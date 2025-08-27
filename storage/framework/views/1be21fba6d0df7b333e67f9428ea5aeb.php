<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title', 'Sistem Ujian - SMK PGRI CIKAMPEK'); ?></title>
    
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

        @media (min-width: 1024px) {
            .sidebar-desktop {
                position: fixed !important;
                height: calc(100% - 4rem) !important;
                top: 4rem !important;
                z-index: 30;
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
        }
        
        /* FIX FOR SCROLLING ISSUE */
        html, body {
            height: 100%;
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
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
    
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
                                sidebar.style.zIndex = '9999';
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
        <!-- Top Navigation -->
        <div class="fixed top-0 left-0 w-full h-16 bg-white shadow-md z-40">
            <div class="flex items-center justify-between h-full px-4">
                <div class="flex items-center">
                    <!-- Toggle Sidebar -->
                    <button @click="toggleSidebar()" class="mr-4 text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900">
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
                        <div class="flex items-center h-8 w-8 mr-2 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-lg flex-shrink-0 shadow-sm">
                            <i class="fas fa-clipboard-list mx-auto text-lg"></i>
                        </div>
                        <h1 class="text-lg font-bold text-gray-800 hidden sm:block">
                            Sistem Ujian - SMK PGRI CIKAMPEK
                        </h1>
                        <h1 class="text-lg font-bold text-gray-800 sm:hidden">
                            Sistem Ujian
                        </h1>
                    </div>
                    
                    <!-- Date Display -->
                    <div class="hidden md:flex items-center ml-6 space-x-1 bg-blue-50 px-3 py-1.5 rounded-lg">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        <span class="ml-2 text-sm font-medium text-gray-700"><?php echo e(now()->isoFormat('dddd, D MMMM Y')); ?></span>
                    </div>
                    
                    <!-- Mobile Date Display -->
                    <div class="md:hidden flex items-center ml-4 text-sm text-gray-600">
                        <i class="fas fa-calendar-day text-blue-600 mr-1.5"></i>
                        <span><?php echo e(now()->format('d/m/Y')); ?></span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Breadcrumb (Hidden on small screens) -->
                    <div class="hidden sm:flex items-center text-sm px-3 py-1.5 bg-gray-50 rounded-lg">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-blue-600 font-medium">Dashboard</a>
                        <span class="mx-2 text-gray-400">/</span>
                        <span class="text-gray-600">Sistem Ujian</span>
                    </div>
                    
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
        </div>

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
                                <a href="<?php echo e(route('admin.ujian.bank-soal.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.bank-soal.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-book <?php echo e(request()->routeIs('admin.ujian.bank-soal.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Bank Soal</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.jadwal.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.jadwal.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-calendar-check <?php echo e(request()->routeIs('admin.ujian.jadwal.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Jadwal Ujian</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.pengawas.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.pengawas.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-user-shield <?php echo e(request()->routeIs('admin.ujian.pengawas.*') ? '' : 'text-gray-600 group-hover:text-orange-600'); ?>"></i>
                                    <span>Jadwal Pengawas</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.monitoring')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.monitoring') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-desktop <?php echo e(request()->routeIs('admin.ujian.monitoring') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Monitoring Ujian</span>
                                </a>
                            </li>
                            
                            <li>
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2 pt-4">Hasil & Analisis</div>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.hasil.kelas')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.hasil.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-file-alt <?php echo e(request()->routeIs('admin.ujian.hasil.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Hasil Ujian</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.analisis.tingkat-kesulitan')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.analisis.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-chart-line <?php echo e(request()->routeIs('admin.ujian.analisis.*') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Analisis Soal</span>
                                </a>
                            </li>
                            
                            <li>
                                <div class="text-xs uppercase text-gray-500 font-semibold px-3 py-2 pt-4">Pengaturan</div>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.pengaturan.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.pengaturan.index') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-cog <?php echo e(request()->routeIs('admin.ujian.pengaturan.index') ? '' : 'text-gray-600 group-hover:text-blue-600'); ?>"></i>
                                    <span>Pengaturan Ujian</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.pengaturan.jenis-ujian.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.pengaturan.jenis-ujian.*') ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-list-alt <?php echo e(request()->routeIs('admin.ujian.pengaturan.jenis-ujian.*') ? '' : 'text-gray-600 group-hover:text-purple-600'); ?>"></i>
                                    <span>Jenis Ujian</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.index')); ?>" class="flex items-center space-x-3 p-3 rounded-lg <?php echo e(request()->routeIs('admin.ujian.pengaturan.ruangan.*') ? 'bg-gradient-to-r from-green-500 to-green-600 text-white font-medium shadow-sm' : 'hover:bg-gray-50 transition-all duration-300 group'); ?>">
                                    <i class="fas fa-door-open <?php echo e(request()->routeIs('admin.ujian.pengaturan.ruangan.*') ? '' : 'text-gray-600 group-hover:text-green-600'); ?>"></i>
                                    <span>Kelola Ruangan</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-all duration-300 group" data-no-spa="true">
                                    <i class="fas fa-arrow-left text-gray-600 group-hover:text-blue-600"></i>
                                    <span>Kembali ke Admin</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
        </div>

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

    <!-- Admin SPA Navigation -->
    <script src="<?php echo e(asset('js/admin-notification.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/admin-spa-navigation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\layouts\ujian.blade.php ENDPATH**/ ?>