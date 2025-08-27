<!-- Sidebar Ujian -->
<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu Ujian</li>
        
        <li class="sidebar-item">
            <a href="<?php echo e(route('admin.ujian.dashboard')); ?>" class="sidebar-link">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard Ujian</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="<?php echo e(route('admin.ujian.bank-soal')); ?>" class="sidebar-link">
                <i class="bi bi-file-text-fill"></i>
                <span>Bank Soal</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="<?php echo e(route('admin.ujian.jadwal')); ?>" class="sidebar-link">
                <i class="bi bi-calendar-event-fill"></i>
                <span>Jadwal Ujian</span>
            </a>
        </li>

        <li class="sidebar-item has-sub">
            <a href="#" class="sidebar-link">
                <i class="bi bi-clipboard-data-fill"></i>
                <span>Hasil Ujian</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="<?php echo e(route('admin.ujian.hasil.kelas')); ?>">Per Kelas</a>
                </li>
                <li class="submenu-item">
                    <a href="<?php echo e(route('admin.ujian.hasil.siswa')); ?>">Per Siswa</a>
                </li>
                <li class="submenu-item">
                    <a href="<?php echo e(route('admin.ujian.hasil.mapel')); ?>">Per Mata Pelajaran</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item">
            <a href="<?php echo e(route('admin.ujian.pengaturan')); ?>" class="sidebar-link">
                <i class="bi bi-gear-fill"></i>
                <span>Pengaturan Ujian</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="<?php echo e(route('admin.ujian.monitoring')); ?>" class="sidebar-link">
                <i class="bi bi-display"></i>
                <span>Monitoring Ujian</span>
            </a>
        </li>
    </ul>
</div>
<!-- End Sidebar Ujian -->
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\ujian\sidebar.blade.php ENDPATH**/ ?>