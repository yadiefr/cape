

<?php $__env->startSection('title', 'Tambah Tugas - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="px-3 py-4">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-orange-600 mr-3"></i>
                    Tambah Tugas Siswa
                </h1>
                <p class="text-gray-600 mt-1">Buat tugas baru untuk siswa</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <form action="<?php echo e(route('guru.materi.store-tugas')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Judul Tugas -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Tugas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" value="<?php echo e(old('judul')); ?>" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Masukkan judul tugas">
                            <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select id="kelas_id" name="kelas_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Pilih Kelas</option>
                                <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kelas->id); ?>" <?php echo e(old('kelas_id') == $kelas->id ? 'selected' : ''); ?>>
                                        <?php echo e($kelas->nama_kelas); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div>
                            <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select id="mapel_id" name="mapel_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 <?php $__errorArgs = ['mapel_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Pilih Mata Pelajaran</option>
                                <?php $__currentLoopData = $mapelList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($mapel->id); ?>" <?php echo e(old('mapel_id') == $mapel->id ? 'selected' : ''); ?>>
                                        <?php echo e($mapel->nama); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['mapel_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Tanggal Deadline -->
                        <div>
                            <label for="tanggal_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal_deadline" name="tanggal_deadline" value="<?php echo e(old('tanggal_deadline')); ?>" required
                                   min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 <?php $__errorArgs = ['tanggal_deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['tanggal_deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="mt-1 text-sm text-gray-500">
                                Minimal H+1 dari hari ini
                            </p>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                File Soal (Opsional)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-colors">
                                <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="hidden" onchange="updateFileName(this)">
                                <label for="file" class="cursor-pointer">
                                    <div class="text-gray-400 mb-2">
                                        <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="text-orange-600 hover:text-orange-700">Klik untuk upload</span> atau drag & drop file
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        PDF, DOC, DOCX, JPG, PNG (Maks. 10MB)
                                    </p>
                                </label>
                                <div id="fileName" class="mt-2 text-sm text-green-600 hidden"></div>
                            </div>
                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Tugas <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="12" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="Jelaskan tugas yang harus dikerjakan siswa...

Contoh:
1. Bacalah materi tentang...
2. Kerjakan soal nomor 1-10
3. Buatlah resume dengan minimal 500 kata
4. Upload jawaban dalam format PDF"><?php echo e(old('deskripsi')); ?></textarea>
                            <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Preview Section -->
                        <div class="bg-orange-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-orange-800 mb-2">
                                <i class="fas fa-info-circle mr-1"></i> Tips Membuat Tugas Efektif
                            </h4>
                            <ul class="text-sm text-orange-700 space-y-1">
                                <li>• Berikan instruksi yang jelas dan spesifik</li>
                                <li>• Tentukan deadline yang realistic</li>
                                <li>• Sertakan file soal jika diperlukan</li>
                                <li>• Jelaskan format pengumpulan tugas</li>
                                <li>• Berikan rubrik penilaian yang jelas</li>
                            </ul>
                        </div>

                        <!-- Deadline Warning -->
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800 mb-1">Perhatian Deadline</h4>
                                    <p class="text-sm text-yellow-700">
                                        Pastikan memberikan waktu yang cukup untuk siswa mengerjakan tugas. 
                                        Disarankan minimal 3-7 hari untuk tugas biasa dan 1-2 minggu untuk tugas besar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                <div class="flex justify-end space-x-3">
                    <a href="<?php echo e(route('guru.materi.index')); ?>" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Tugas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = document.getElementById('fileName');
    if (input.files && input.files[0]) {
        fileName.textContent = `File dipilih: ${input.files[0].name}`;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
}

// Set minimum date to tomorrow
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const deadlineInput = document.getElementById('tanggal_deadline');
    deadlineInput.min = tomorrow.toISOString().split('T')[0];
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\materi\create-tugas.blade.php ENDPATH**/ ?>