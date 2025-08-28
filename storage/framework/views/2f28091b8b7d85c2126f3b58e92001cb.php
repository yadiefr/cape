<?php $__env->startSection('title', 'Edit Nilai Batch - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="px-3 py-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Edit Nilai <?php echo e(ucfirst($request->jenis_nilai)); ?> - <?php echo e($mapel->nama); ?>

            </h1>
            <p class="text-sm sm:text-base text-gray-600">
                Kelas <?php echo e($kelas->nama_kelas); ?> - <?php echo e($kelas->jurusan->nama_jurusan ?? $kelas->jurusan->nama ?? 'Belum ada jurusan'); ?>

            </p>
        </div>
        <a href="<?php echo e(route('guru.nilai.show', $kelas->id)); ?>?action=detail&mapel_id=<?php echo e($mapel->id); ?>" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors text-center">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Edit Nilai untuk Semua Siswa</h2>
            <p class="text-sm text-gray-600 mt-1">
                Jenis Nilai: <span class="font-medium"><?php echo e(ucfirst($request->jenis_nilai)); ?></span>
            </p>
        </div>

        <form action="<?php echo e(route('guru.nilai.update-batch')); ?>" method="POST" class="p-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <input type="hidden" name="kelas_id" value="<?php echo e($kelas->id); ?>">
            <input type="hidden" name="mapel_id" value="<?php echo e($mapel->id); ?>">
            <input type="hidden" name="jenis_nilai" value="<?php echo e($request->jenis_nilai); ?>">

            <!-- Deskripsi Global -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi (akan diterapkan ke semua nilai)
                </label>
                <input type="text" 
                       id="deskripsi" 
                       name="deskripsi" 
                       value="<?php echo e($nilaiRecords->first()->deskripsi ?? ''); ?>"
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Masukkan deskripsi untuk nilai ini...">
            </div>

            <!-- Daftar Siswa dan Nilai -->
            <div class="space-y-3">
                <?php $__currentLoopData = $nilaiRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nilai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                            <?php echo e($index + 1); ?>

                        </span>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">
                                <?php echo e($nilai->siswa->nama_lengkap ?? $nilai->siswa->nama ?? 'Nama tidak tersedia'); ?>

                            </h3>
                            <p class="text-xs text-gray-500"><?php echo e($nilai->siswa->nis ?? '-'); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <input type="hidden" name="nilai_ids[]" value="<?php echo e($nilai->id); ?>">
                        <input type="number" 
                               name="nilai_values[]" 
                               value="<?php echo e($nilai->nilai); ?>"
                               min="0" 
                               max="100" 
                               step="0.01"
                               class="w-20 text-center rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        <span class="text-xs text-gray-400"><?php echo e($nilai->created_at->format('d/m/Y')); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
                <a href="<?php echo e(route('guru.nilai.show', $kelas->id)); ?>?action=detail&mapel_id=<?php echo e($mapel->id); ?>" 
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors text-center font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select all input values when focused
    const nilaiInputs = document.querySelectorAll('input[name="nilai_values[]"]');
    nilaiInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.select();
        });
    });
    
    // Keyboard navigation
    nilaiInputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === 'ArrowDown') {
                e.preventDefault();
                const nextInput = nilaiInputs[index + 1];
                if (nextInput) {
                    nextInput.focus();
                    nextInput.select();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevInput = nilaiInputs[index - 1];
                if (prevInput) {
                    prevInput.focus();
                    prevInput.select();
                }
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\nilai\edit-batch.blade.php ENDPATH**/ ?>