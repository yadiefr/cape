

<?php $__env->startSection('title', 'Edit Mata Pelajaran - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="w-full bg-gray-100 min-h-screen p-4 md:p-6 font-sans">
    <div class="flex flex-col space-y-6">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden">
            <div class="absolute right-0 -top-12 h-40 w-40 bg-amber-100 opacity-50 rounded-full"></div>
            <div class="absolute -right-8 top-20 h-20 w-20 bg-amber-200 opacity-30 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <div class="flex items-center">
                            <a href="<?php echo e(route('admin.matapelajaran.index')); ?>" class="text-gray-500 hover:text-gray-700 mr-2">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-edit text-amber-600 mr-3"></i>
                                Edit Mata Pelajaran
                            </h1>
                        </div>                        
                        <p class="text-gray-600 mt-1">Perbarui informasi mata pelajaran: <?php echo e($mapel->nama); ?></p>
                    </div>
                </div>
            </div>        
        </div>
            
        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <form action="<?php echo e(route('admin.matapelajaran.update', $mapel->kode)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Mapel -->
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Pelajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="kode" id="kode" value="<?php echo e($mapel->kode); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 bg-gray-50" placeholder="Contoh: MP001" readonly>
                        <p class="text-xs text-gray-500 mt-1">Kode unik untuk mata pelajaran ini (tidak dapat diubah)</p>
                    </div>

                    <!-- Nama Mapel -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" value="<?php echo e(old('nama', $mapel->nama)); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Contoh: Matematika" required>
                        <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <select name="tahun_ajaran" id="tahun_ajaran" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" required>
                            <option value="2025/2026" <?php echo e(old('tahun_ajaran', $mapel->tahun_ajaran) == '2025/2026' ? 'selected' : ''); ?>>2025/2026</option>
                            <option value="2024/2025" <?php echo e(old('tahun_ajaran', $mapel->tahun_ajaran) == '2024/2025' ? 'selected' : ''); ?>>2024/2025</option>
                            <option value="2023/2024" <?php echo e(old('tahun_ajaran', $mapel->tahun_ajaran) == '2023/2024' ? 'selected' : ''); ?>>2023/2024</option>
                        </select>
                        <?php $__errorArgs = ['tahun_ajaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- KKM -->
                    <div>
                        <label for="kkm" class="block text-sm font-medium text-gray-700 mb-1">KKM <span class="text-red-500">*</span></label>
                        <input type="number" name="kkm" id="kkm" min="1" max="100" value="<?php echo e(old('kkm', $mapel->kkm)); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Contoh: 75" required>
                        <p class="text-xs text-gray-500 mt-1">Kriteria Ketuntasan Minimal (1-100)</p>
                        <?php $__errorArgs = ['kkm'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Mata Pelajaran Jurusan Checkbox -->
                    <div>
                        <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                            <input type="checkbox" name="is_jurusan" id="is_jurusan" value="1" class="rounded border-blue-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" <?php echo e($mapel->jenis == 'Kejuruan' ? 'checked' : ''); ?>>
                            <label for="is_jurusan" class="ml-2 text-sm text-blue-800 font-medium">Apakah ini mata pelajaran kejuruan?</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Centang jika mata pelajaran ini khusus untuk program kejuruan</p>
                    </div>


                    <!-- Spacer untuk grid -->
                    <div></div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Mata Pelajaran</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Deskripsi singkat tentang mata pelajaran ini..."><?php echo e(old('deskripsi', $mapel->deskripsi)); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Deskripsi singkat tentang mata pelajaran ini (opsional)</p>
                        <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Materi Pokok -->
                    <div class="md:col-span-2">
                        <label for="materi_pokok" class="block text-sm font-medium text-gray-700 mb-1">Materi Pokok</label>
                        <textarea name="materi_pokok" id="materi_pokok" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Daftar materi pokok yang akan diajarkan..."><?php echo e(old('materi_pokok', $mapel->materi_pokok)); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Pisahkan tiap materi dengan baris baru (opsional)</p>
                        <?php $__errorArgs = ['materi_pokok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <a href="<?php echo e(route('admin.matapelajaran.index')); ?>" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- History Log -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history text-gray-600 mr-2"></i>
                Riwayat Perubahan
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admin
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Perubahan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                5 Mei 2025, 10:15
                            </td>                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Admin Mata Pelajaran
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Dibuat
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                Mata pelajaran baru ditambahkan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation for form submission
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // No specific validation needed
        });

        // Handle form submission feedback
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        });

        // Enhanced guru checkbox interaction
        const guruCheckboxes = document.querySelectorAll('input[name="guru_pengajar[]"]');
        guruCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const parentDiv = this.closest('.flex.items-center');
                const checkIcon = parentDiv.querySelector('.fa-check-circle');
                
                if (this.checked) {
                    parentDiv.classList.add('bg-amber-50', 'border-amber-300');
                    parentDiv.classList.remove('bg-white', 'border-gray-100');
                    if (!checkIcon) {
                        const icon = document.createElement('i');
                        icon.className = 'fas fa-check-circle text-green-500 ml-2';
                        parentDiv.appendChild(icon);
                    }
                } else {
                    parentDiv.classList.remove('bg-amber-50', 'border-amber-300');
                    parentDiv.classList.add('bg-white', 'border-gray-100');
                    // Only remove check icon if it was added by this script (not pre-existing)
                    const wasPreSelected = this.defaultChecked;
                    if (!wasPreSelected && checkIcon) {
                        checkIcon.remove();
                    }
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\matapelajaran\edit.blade.php ENDPATH**/ ?>