

<?php $__env->startSection('title', 'Hasil Status Pendaftaran - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="ppdb-card">
    <!-- Header -->
    <div class="ppdb-section-header">
        <h2 class="text-center mb-0">Status Pendaftaran PPDB</h2>
    </div>

    <div class="mt-4">
        <div class="ppdb-title">Hasil Pendaftaran</div>
        <p class="ppdb-subtitle">
            Berikut adalah informasi status pendaftaran Anda
        </p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-lg me-2"></i>
                <h5 class="mb-0">Informasi Pendaftaran</h5>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded border-start border-primary border-4">
                        <label class="fw-bold text-primary mb-1 small">Nomor Pendaftaran:</label>
                        <div class="fs-5 fw-semibold"><?php echo e($pendaftaran->nomor_pendaftaran); ?></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded border-start border-primary border-4">
                        <label class="fw-bold text-primary mb-1 small">Tanggal Daftar:</label>
                        <div class="fs-5 fw-semibold"><?php echo e($pendaftaran->tanggal_pendaftaran->format('d M Y H:i')); ?></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <label class="fw-bold text-secondary mb-1 small">Nama Lengkap:</label>
                        <span class="fs-6"><?php echo e($pendaftaran->nama_lengkap); ?></span>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <label class="fw-bold text-secondary mb-1 small">NISN:</label>
                        <span class="fs-6"><?php echo e($pendaftaran->nisn); ?></span>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <label class="fw-bold text-secondary mb-1 small">Asal Sekolah:</label>
                        <span class="fs-6"><?php echo e($pendaftaran->asal_sekolah); ?></span>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <label class="fw-bold text-secondary mb-1 small">Tahun Ajaran:</label>
                        <span class="fs-6"><?php echo e($pendaftaran->tahun_ajaran); ?></span>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="d-flex flex-column">
                        <label class="fw-bold text-secondary mb-1 small">Pilihan Jurusan:</label>
                        <span class="fs-6"><?php echo e($pendaftaran->jurusanPertama?->nama_jurusan ?? '-'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-clipboard-check fa-lg me-2"></i>
                <h5 class="mb-0">Status Pendaftaran</h5>
            </div>
        </div>
        
        <div class="card-body p-4">
            <?php if($pendaftaran->status == 'menunggu'): ?>
            <div class="alert alert-warning d-flex align-items-center py-4 px-3" role="alert">
                <div class="flex-shrink-0 me-3">
                    <div class="bg-warning bg-opacity-25 p-3 rounded-circle">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
                <div>
                    <h4 class="alert-heading fs-5 fw-bold">Menunggu Verifikasi</h4>
                    <p class="mb-0">Pendaftaran Anda sedang dalam proses verifikasi. Silakan cek kembali secara berkala.</p>
                </div>
            </div>
            <?php elseif($pendaftaran->status == 'diterima'): ?>
            <div class="alert alert-success d-flex align-items-center py-4 px-3" role="alert">
                <div class="flex-shrink-0 me-3">
                    <div class="bg-success bg-opacity-25 p-3 rounded-circle">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
                <div>
                    <h4 class="alert-heading fs-5 fw-bold">Selamat! Anda Diterima</h4>
                    <p class="mb-0">Anda diterima di SMK PGRI CIKAMPEK. Silakan cetak bukti pendaftaran dan ikuti petunjuk selanjutnya.</p>
                </div>
            </div>
            <?php elseif($pendaftaran->status == 'ditolak'): ?>
            <div class="alert alert-danger d-flex align-items-center py-4 px-3" role="alert">
                <div class="flex-shrink-0 me-3">
                    <div class="bg-danger bg-opacity-25 p-3 rounded-circle">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
                <div>
                    <h4 class="alert-heading fs-5 fw-bold">Tidak Diterima</h4>
                    <p class="mb-0">Maaf, pendaftaran Anda belum berhasil. Silakan lihat keterangan untuk informasi lebih lanjut.</p>
                </div>
            </div>
            <?php elseif($pendaftaran->status == 'cadangan'): ?>
            <div class="alert alert-info d-flex align-items-center py-4 px-3" role="alert">
                <div class="flex-shrink-0 me-3">
                    <div class="bg-info bg-opacity-25 p-3 rounded-circle">
                        <i class="fas fa-user-clock fa-2x text-info"></i>
                    </div>
                </div>
                <div>
                    <h4 class="alert-heading fs-5 fw-bold">Cadangan</h4>
                    <p class="mb-0">Anda masuk dalam daftar cadangan. Anda akan diterima jika ada kuota tersedia.</p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($pendaftaran->keterangan): ?>
            <div class="mt-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title fw-bold fs-6 text-primary">Keterangan:</h5>
                        <p class="card-text"><?php echo e($pendaftaran->keterangan); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="d-flex flex-column flex-md-row gap-3 mt-4">
        <a href="<?php echo e(route('pendaftaran.print', ['nomor' => $pendaftaran->nomor_pendaftaran, 'nisn' => $pendaftaran->nisn])); ?>"
           target="_blank" 
           class="btn btn-ppdb flex-grow-1">
            <i class="fas fa-print me-2"></i>Cetak Bukti Pendaftaran
        </a>
        <a href="<?php echo e(url('/')); ?>" 
           class="btn btn-secondary-ppdb flex-grow-1">
            <i class="fas fa-home me-2"></i>Kembali ke Beranda
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app-ppdb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\ppdb\result.blade.php ENDPATH**/ ?>