<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance - <?php echo e($site_title); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .maintenance-container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }
        
        .maintenance-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .maintenance-subtitle {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        
        .maintenance-message {
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.8;
            margin-bottom: 2rem;
        }
        
        .maintenance-button {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }
        
        .maintenance-button:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }

        .logout-button {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.3);
            margin-top: 1rem;
            margin-bottom: 2rem;
        }

        .logout-button:hover {
            background: rgba(239, 68, 68, 0.3);
            border-color: rgba(239, 68, 68, 0.4);
        }
        
        .social-links {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .social-link {
            display: inline-block;
            width: 3rem;
            height: 3rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            color: white;
        }
        
        @media (max-width: 768px) {
            .maintenance-title {
                font-size: 2rem;
            }
            
            .maintenance-subtitle {
                font-size: 1rem;
            }
            
            .maintenance-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1 class="maintenance-title">Website Sedang Maintenance</h1>
        <h2 class="maintenance-subtitle"><?php echo e($nama_sekolah); ?></h2>
        
        <p class="maintenance-message">
            Mohon maaf atas ketidaknyamanannya. Kami sedang melakukan pemeliharaan website untuk memberikan layanan yang lebih baik. 
            Website akan kembali normal dalam waktu dekat.
        </p>

        <?php if($isAuthenticated): ?>
            <?php
                $logoutRoute = match($activeGuard) {
                    'guru' => 'guru.logout',
                    'siswa' => 'siswa.logout',
                    default => 'logout'
                };
                
                $roleText = match($activeGuard) {
                    'guru' => 'Guru',
                    'siswa' => 'Siswa',
                    default => 'Pengguna'
                };
            ?>
            
            <div style="margin-bottom: 1rem;">
                <span style="background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 20px; display: inline-block;">
                    <i class="fas fa-user"></i> Status Login : <?php echo e($roleText); ?>

                </span>
            </div>

            <form action="<?php echo e(route($logoutRoute)); ?>" method="POST" style="margin: 0;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="maintenance-button logout-button">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        <?php endif; ?>

        <div class="social-links">
            <?php if(setting('facebook_url')): ?>
                <a href="<?php echo e(setting('facebook_url')); ?>" class="social-link" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
            <?php endif; ?>
            
            <?php if(setting('instagram_url')): ?>
                <a href="<?php echo e(setting('instagram_url')); ?>" class="social-link" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            <?php endif; ?>
            
            <?php if(setting('youtube_url')): ?>
                <a href="<?php echo e(setting('youtube_url')); ?>" class="social-link" target="_blank">
                    <i class="fab fa-youtube"></i>
                </a>
            <?php endif; ?>
            
            <?php if(setting('whatsapp_number')): ?>
                <a href="https://wa.me/<?php echo e(setting('whatsapp_number')); ?>" class="social-link" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html><?php /**PATH C:\wamp64\www\website-smk3\resources\views\maintenance.blade.php ENDPATH**/ ?>