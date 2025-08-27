

<?php $__env->startSection('title', 'Detail PKL - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Detail Data PKL</h2>
        <a href="<?php echo e(route('admin.pkl.index')); ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Informasi Siswa -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Siswa</h3>
                
                <div class="flex items-center justify-center mb-4">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                        <?php if($pkl->siswa->foto): ?>
                            <img src="<?php echo e(asset('storage/siswa/' . $pkl->siswa->foto)); ?>" alt="<?php echo e($pkl->siswa->nama_lengkap); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user text-4xl text-gray-400"></i>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800"><?php echo e($pkl->siswa->nama_lengkap); ?></h4>
                    <p class="text-sm text-gray-600">NIS: <?php echo e($pkl->siswa->nis); ?></p>
                    <p class="text-sm text-gray-600">NISN: <?php echo e($pkl->siswa->nisn); ?></p>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kelas:</span>
                        <span class="text-sm font-medium text-gray-800"><?php echo e($pkl->siswa->kelas->nama_kelas); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jurusan:</span>
                        <span class="text-sm font-medium text-gray-800"><?php echo e($pkl->siswa->jurusan->nama_jurusan); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jenis Kelamin:</span>
                        <span class="text-sm font-medium text-gray-800"><?php echo e($pkl->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Telepon:</span>
                        <span class="text-sm font-medium text-gray-800"><?php echo e($pkl->siswa->telepon ?: '-'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Email:</span>
                        <span class="text-sm font-medium text-gray-800"><?php echo e($pkl->siswa->email ?: '-'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Perusahaan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Perusahaan</h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">Nama Perusahaan</h4>
                        <p class="text-base font-semibold text-gray-800"><?php echo e($pkl->nama_perusahaan); ?></p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">Bidang Usaha</h4>
                        <p class="text-base text-gray-800"><?php echo e($pkl->bidang_usaha); ?></p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">Alamat</h4>
                        <p class="text-base text-gray-800"><?php echo e($pkl->alamat_perusahaan); ?></p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">Pembimbing</h4>
                        <p class="text-base font-semibold text-gray-800"><?php echo e($pkl->nama_pembimbing); ?></p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-600">Telepon</h4>
                            <p class="text-base text-gray-800"><?php echo e($pkl->telepon_pembimbing ?: '-'); ?></p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-600">Email</h4>
                            <p class="text-base text-gray-800"><?php echo e($pkl->email_pembimbing ?: '-'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Periode & Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Periode & Status</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-600">Tanggal Mulai</h4>
                            <p class="text-base text-gray-800"><?php echo e($pkl->tanggal_mulai->format('d M Y')); ?></p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-600">Tanggal Selesai</h4>
                            <p class="text-base text-gray-800"><?php echo e($pkl->tanggal_selesai->format('d M Y')); ?></p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">Durasi</h4>
                        <p class="text-base text-gray-800"><?php echo e($pkl->tanggal_mulai->diffInDays($pkl->tanggal_selesai) + 1); ?> Hari</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">Status PKL</h4>
                        <div class="mt-1">
                            <?php if($pkl->status == 'pengajuan'): ?>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pengajuan
                                </span>
                            <?php elseif($pkl->status == 'berlangsung'): ?>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Berlangsung
                                </span>
                            <?php elseif($pkl->status == 'selesai'): ?>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            <?php elseif($pkl->status == 'gagal'): ?>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Gagal
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-600">Keterangan</h4>
                        <p class="text-base text-gray-800"><?php echo e($pkl->keterangan ?: '-'); ?></p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('admin.pkl.edit', $pkl->id)); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-100 border border-yellow-200 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all w-full">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Penilaian & Dokumen -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Penilaian & Dokumen</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-base font-medium text-gray-700 mb-3">Nilai PKL</h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Nilai Teknis:</span>
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full <?php echo e($pkl->nilai_teknis ? ($pkl->nilai_teknis >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') : 'bg-gray-100 text-gray-500'); ?>">
                                <?php echo e($pkl->nilai_teknis ?: '-'); ?>

                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Nilai Sikap:</span>
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full <?php echo e($pkl->nilai_sikap ? ($pkl->nilai_sikap >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') : 'bg-gray-100 text-gray-500'); ?>">
                                <?php echo e($pkl->nilai_sikap ?: '-'); ?>

                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Nilai Laporan:</span>
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full <?php echo e($pkl->nilai_laporan ? ($pkl->nilai_laporan >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') : 'bg-gray-100 text-gray-500'); ?>">
                                <?php echo e($pkl->nilai_laporan ?: '-'); ?>

                            </span>
                        </div>
                        
                        <?php if($pkl->nilai_teknis && $pkl->nilai_sikap && $pkl->nilai_laporan): ?>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="text-sm font-medium text-gray-600">Nilai Rata-rata:</span>
                            <?php
                                $rataRata = ($pkl->nilai_teknis + $pkl->nilai_sikap + $pkl->nilai_laporan) / 3;
                            ?>
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full text-lg <?php echo e($rataRata >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?> font-bold">
                                <?php echo e(number_format($rataRata, 1)); ?>

                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-base font-medium text-gray-700 mb-3">Dokumen</h4>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-file-pdf text-red-500 text-2xl mr-3 mt-1"></i>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700">Laporan PKL</h5>
                                    <p class="text-xs text-gray-500"><?php echo e($pkl->dokumen_laporan ? 'Dokumen tersedia' : 'Belum upload'); ?></p>
                                </div>
                            </div>
                            
                            <?php if($pkl->dokumen_laporan): ?>
                            <a href="<?php echo e(route('admin.pkl.download.laporan', $pkl->id)); ?>" class="inline-flex items-center px-3 py-1 rounded-md bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                            <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-md bg-gray-100 text-gray-400 text-sm">
                                <i class="fas fa-times-circle mr-1"></i> Belum Ada
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-certificate text-yellow-500 text-2xl mr-3 mt-1"></i>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700">Surat Keterangan</h5>
                                    <p class="text-xs text-gray-500"><?php echo e($pkl->surat_keterangan ? 'Dokumen tersedia' : 'Belum upload'); ?></p>
                                </div>
                            </div>
                            
                            <?php if($pkl->surat_keterangan): ?>
                            <a href="<?php echo e(route('admin.pkl.download.surat', $pkl->id)); ?>" class="inline-flex items-center px-3 py-1 rounded-md bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                            <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-md bg-gray-100 text-gray-400 text-sm">
                                <i class="fas fa-times-circle mr-1"></i> Belum Ada
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Aksi -->
    <div class="flex space-x-3">
        <a href="<?php echo e(route('admin.pkl.edit', $pkl->id)); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-200 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all">
            <i class="fas fa-edit mr-2"></i>
            Edit Data PKL
        </a>
        <form action="<?php echo e(route('admin.pkl.destroy', $pkl->id)); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 border border-red-200 text-red-700 rounded-lg hover:bg-red-200 transition-all" onclick="return confirm('Apakah Anda yakin ingin menghapus data PKL ini?')">
                <i class="fas fa-trash mr-2"></i>
                Hapus Data PKL
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\pkl\show.blade.php ENDPATH**/ ?>