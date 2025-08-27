

<?php $__env->startSection('title', 'Riwayat Pembayaran Siswa'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="bg-white rounded-xl shadow-md p-8 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Pembayaran Siswa</h1>
        <a href="<?php echo e(route('admin.keuangan.index')); ?>" class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gray-600 hover:bg-gray-700">
            Kembali
        </a>
    </div>
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:space-x-8">
            <div>
                <p class="font-semibold text-gray-700 text-lg">Nama Siswa:</p>
                <p class="text-xl text-gray-900"><?php echo e($siswa->nama_lengkap ?? $siswa->nama); ?></p>
            </div>
            <div>
                <p class="font-semibold text-gray-700 text-lg">Kelas:</p>
                <p class="text-xl text-gray-900"><?php echo e($siswa->kelas->nama_kelas ?? '-'); ?></p>
            </div>
        </div>
    </div>

    
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Rincian Tagihan Siswa</h2>
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-base">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Tagihan</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nominal</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Total Dibayar</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Sisa</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $detailTagihan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo e($tagihan['nama_tagihan']); ?></td>
                        <td class="px-6 py-4">Rp <?php echo e(number_format($tagihan['nominal'],0,',','.')); ?></td>
                        <td class="px-6 py-4">Rp <?php echo e(number_format($tagihan['total_dibayar'],0,',','.')); ?></td>
                        <td class="px-6 py-4">Rp <?php echo e(number_format($tagihan['sisa'],0,',','.')); ?></td>
                        <td class="px-6 py-4">
                            <?php if($tagihan['status'] == 'Lunas'): ?>
                                <span class="px-3 py-1.5 rounded-full bg-green-100 text-green-800 text-sm font-medium">Lunas</span>
                            <?php elseif($tagihan['status'] == 'Sebagian'): ?>
                                <span class="px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">Sebagian</span>
                            <?php else: ?>
                                <span class="px-3 py-1.5 rounded-full bg-red-100 text-red-800 text-sm font-medium">Belum Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($tagihan['status'] != 'Lunas'): ?>
                                <form action="<?php echo e(route('admin.keuangan.bayar', $tagihan['id'])); ?>" method="POST" class="flex items-center space-x-3">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="siswa_id" value="<?php echo e($siswa->id); ?>">
                                    <input type="hidden" name="tanggal" value="<?php echo e(now()->format('Y-m-d H:i:s')); ?>">
                                    <span class="text-sm text-gray-600 whitespace-nowrap"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
                                    <input type="number" name="jumlah" class="form-input w-32 rounded-md border-gray-300 text-base" placeholder="Jumlah" required min="1" max="<?php echo e($tagihan['sisa']); ?>">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-base font-medium">Bayar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-base">Tidak ada tagihan</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h2>
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Tanggal & Jam</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Keterangan</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $siswa->pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-base text-gray-900">
                            <div><?php echo e(\Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y')); ?></div>
                            <small class="text-gray-500"><?php echo e(\Carbon\Carbon::parse($pembayaran->tanggal)->format('H:i:s')); ?></small>
                        </td>
                        <td class="px-6 py-4 text-base text-gray-900"><?php echo e($pembayaran->keterangan); ?></td>
                        <td class="px-6 py-4 text-base text-green-700 font-semibold">Rp <?php echo e(number_format($pembayaran->jumlah,0,',','.')); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium <?php echo e($pembayaran->status == 'Lunas' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>"><?php echo e($pembayaran->status); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-base">Belum ada pembayaran</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\keuangan\riwayat.blade.php ENDPATH**/ ?>