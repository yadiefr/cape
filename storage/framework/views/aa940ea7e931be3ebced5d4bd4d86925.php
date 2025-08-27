

<?php $__env->startSection('title', 'Detail PKL'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="p-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Detail Praktik Kerja Lapangan</h2>
                <a href="<?php echo e(route('siswa.pkl')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Informasi Perusahaan</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="mb-2"><span class="font-medium">Nama Perusahaan:</span> <?php echo e($pkl->nama_perusahaan); ?></p>
                        <p class="mb-2"><span class="font-medium">Alamat:</span> <?php echo e($pkl->alamat_perusahaan); ?></p>
                        <p class="mb-2"><span class="font-medium">Bidang Usaha:</span> <?php echo e($pkl->bidang_usaha); ?></p>
                        <p class="mb-2"><span class="font-medium">Pembimbing:</span> <?php echo e($pkl->nama_pembimbing); ?></p>
                        <?php if($pkl->telepon_pembimbing): ?>
                            <p class="mb-2"><span class="font-medium">Telepon Pembimbing:</span> <?php echo e($pkl->telepon_pembimbing); ?></p>
                        <?php endif; ?>
                        <?php if($pkl->email_pembimbing): ?>
                            <p class="mb-2"><span class="font-medium">Email Pembimbing:</span> <?php echo e($pkl->email_pembimbing); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Status PKL</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="mb-2">
                            <span class="font-medium">Status:</span> 
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                <?php if($pkl->status == 'pengajuan'): ?> bg-yellow-100 text-yellow-800
                                <?php elseif($pkl->status == 'berlangsung'): ?> bg-blue-100 text-blue-800
                                <?php elseif($pkl->status == 'selesai'): ?> bg-green-100 text-green-800
                                <?php else: ?> bg-red-100 text-red-800
                                <?php endif; ?>">
                                <?php echo e(ucfirst($pkl->status)); ?>

                            </span>
                        </p>
                        <p class="mb-2"><span class="font-medium">Tanggal Mulai:</span> <?php echo e($pkl->tanggal_mulai->format('d M Y')); ?></p>
                        <p class="mb-2"><span class="font-medium">Tanggal Selesai:</span> <?php echo e($pkl->tanggal_selesai->format('d M Y')); ?></p>
                        
                        <?php if($pkl->status === 'selesai'): ?>
                            <div class="mt-4 pt-4 border-t">
                                <h4 class="font-medium mb-2">Nilai:</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                        <p class="text-sm text-gray-600">Teknis</p>
                                        <p class="text-xl font-bold"><?php echo e($pkl->nilai_teknis ?? '-'); ?></p>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                        <p class="text-sm text-gray-600">Sikap</p>
                                        <p class="text-xl font-bold"><?php echo e($pkl->nilai_sikap ?? '-'); ?></p>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                        <p class="text-sm text-gray-600">Laporan</p>
                                        <p class="text-xl font-bold"><?php echo e($pkl->nilai_laporan ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Dokumen</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium mb-2">Laporan PKL</h4>
                            <?php if($pkl->status !== 'pengajuan'): ?>
                                <?php if($pkl->dokumen_laporan): ?>
                                    <div class="flex items-center">
                                        <a href="<?php echo e(route('siswa.pkl.laporan.download', $pkl->id)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                        <?php if($pkl->status === 'berlangsung'): ?>
                                            <form action="<?php echo e(route('siswa.pkl.laporan.upload', $pkl->id)); ?>" method="POST" enctype="multipart/form-data" class="flex-grow">
                                                <?php echo csrf_field(); ?>
                                                <div class="flex items-center">
                                                    <input type="file" name="dokumen_laporan" accept=".pdf,.doc,.docx" class="flex-grow">
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 ml-2">
                                                        <i class="fas fa-upload mr-2"></i>Upload
                                                    </button>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <?php if($pkl->status === 'berlangsung'): ?>
                                        <form action="<?php echo e(route('siswa.pkl.laporan.upload', $pkl->id)); ?>" method="POST" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="flex items-center">
                                                <input type="file" name="dokumen_laporan" accept=".pdf,.doc,.docx" class="flex-grow">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 ml-2">
                                                    <i class="fas fa-upload mr-2"></i>Upload
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <p class="text-gray-500">Belum ada dokumen laporan</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-gray-500">Upload laporan dapat dilakukan saat PKL berlangsung</p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">Surat Keterangan Selesai</h4>
                            <?php if($pkl->surat_keterangan): ?>
                                <a href="<?php echo e(route('siswa.pkl.surat.download', $pkl->id)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <i class="fas fa-download mr-2"></i>Download
                                </a>
                            <?php else: ?>
                                <p class="text-gray-500">Belum ada surat keterangan</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($pkl->keterangan): ?>
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Keterangan Tambahan</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700"><?php echo e($pkl->keterangan); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\pkl\show.blade.php ENDPATH**/ ?>