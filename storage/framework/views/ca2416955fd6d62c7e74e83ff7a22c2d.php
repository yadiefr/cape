<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', setting('site_title', 'SMK PGRI CIKAMPEK')); ?></title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', setting('site_description', 'SMK PGRI CIKAMPEK menyediakan pendidikan berkualitas dengan fasilitas modern')); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', setting('site_keywords', 'smk, sekolah kejuruan, cikampek, pendidikan')); ?>">
    <meta name="author" content="<?php echo e(setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', setting('site_title')); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', setting('site_description')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <?php if(setting('logo_sekolah')): ?>
    <meta property="og:image" content="<?php echo e(asset('storage/' . setting('logo_sekolah'))); ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <?php if(setting('site_favicon')): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('storage/' . setting('site_favicon'))); ?>">
    <?php else: ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <?php endif; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AOS Animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Custom Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <?php if(setting('logo_sekolah')): ?>
                            <img src="<?php echo e(asset('storage/' . setting('logo_sekolah'))); ?>" 
                                 alt="<?php echo e(setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?>" 
                                 class="h-10 w-auto">
                        <?php else: ?>
                            <div class="bg-blue-600 text-white p-2 rounded-lg">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="hidden md:block">
                            <div class="font-bold text-gray-800 text-lg"><?php echo e(setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?></div>
                            <div class="text-sm text-gray-600">Galeri Foto</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="<?php echo e(route('galeri.index')); ?>" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-images mr-2"></i>Galeri
                    </a>
                    <a href="/login" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-4">
                <div class="flex flex-col space-y-3">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 py-2">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="<?php echo e(route('galeri.index')); ?>" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 py-2">
                        <i class="fas fa-images mr-2"></i>Galeri
                    </a>
                    <a href="/login" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 inline-block text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- School Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <?php if(setting('logo_sekolah')): ?>
                            <img src="<?php echo e(asset('storage/' . setting('logo_sekolah'))); ?>" 
                                 alt="<?php echo e(setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?>" 
                                 class="h-10 w-auto">
                        <?php endif; ?>
                        <div>
                            <div class="font-bold text-lg"><?php echo e(setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?></div>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4"><?php echo e(setting('site_description', 'Mempersiapkan generasi unggul dengan pendidikan berkualitas')); ?></p>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Kontak Kami</h4>
                    <div class="space-y-2 text-gray-300">
                        <?php if(setting('alamat_sekolah')): ?>
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span><?php echo e(setting('alamat_sekolah')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if(setting('telepon_sekolah')): ?>
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-3"></i>
                            <span><?php echo e(setting('telepon_sekolah')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if(setting('email_sekolah')): ?>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span><?php echo e(setting('email_sekolah')); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Social Media -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <?php if(setting('facebook_url')): ?>
                        <a href="<?php echo e(setting('facebook_url')); ?>" target="_blank" class="bg-blue-600 hover:bg-blue-700 p-3 rounded-full transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>
                        <?php if(setting('instagram_url')): ?>
                        <a href="<?php echo e(setting('instagram_url')); ?>" target="_blank" class="bg-pink-600 hover:bg-pink-700 p-3 rounded-full transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if(setting('youtube_url')): ?>
                        <a href="<?php echo e(setting('youtube_url')); ?>" target="_blank" class="bg-red-600 hover:bg-red-700 p-3 rounded-full transition-colors duration-200">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?>. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
        });

        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\layouts\app.blade.php ENDPATH**/ ?>