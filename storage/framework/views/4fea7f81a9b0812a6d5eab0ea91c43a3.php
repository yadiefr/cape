

<?php $__env->startSection('title', 'Input Kas Keluar - Bendahara'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Input Kas Keluar</h1>
                <nav class="text-xs sm:text-sm text-gray-600">
                    <a href="<?php echo e(route('siswa.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a>
                    <span class="mx-1 sm:mx-2">></span>
                    <a href="<?php echo e(route('siswa.bendahara.dashboard')); ?>" class="hover:text-blue-600">Bendahara</a>
                    <span class="mx-1 sm:mx-2">></span>
                    <span class="text-blue-600">Input Kas Keluar</span>
                </nav>
            </div>
            <a href="<?php echo e(route('siswa.bendahara.dashboard')); ?>"
               class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg transition duration-200 text-sm sm:text-base w-full sm:w-auto text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Input Kas Keluar -->
    <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6">
        <form action="<?php echo e(route('siswa.bendahara.simpan-kas-keluar')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <!-- Kelas Info -->
            <div class="mb-6 p-3 sm:p-4 bg-red-50 rounded-lg">
                <h3 class="text-base sm:text-lg font-semibold text-red-800 mb-2">Informasi Kelas</h3>
                <div class="space-y-1">
                    <p class="text-sm sm:text-base text-red-700">
                        <span class="font-medium">Kelas:</span> <?php echo e($siswa->kelas->nama_kelas); ?> (<?php echo e($siswa->kelas->jurusan->nama_jurusan); ?>)
                    </p>
                    <p class="text-sm sm:text-base text-red-700">
                        <span class="font-medium">Saldo Saat Ini:</span>
                        <span class="font-bold">Rp <?php echo e(number_format($saldoKas, 0, ',', '.')); ?></span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Transaksi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal" 
                               name="tanggal" 
                               value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        <?php $__errorArgs = ['tanggal'];
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

                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Pengeluaran <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori" 
                                name="kategori" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="acara_kelas" <?php echo e(old('kategori') == 'acara_kelas' ? 'selected' : ''); ?>>
                                Acara/Event Kelas
                            </option>
                            <option value="pembelian_alat" <?php echo e(old('kategori') == 'pembelian_alat' ? 'selected' : ''); ?>>
                                Pembelian Alat/Perlengkapan
                            </option>
                            <option value="kebersihan" <?php echo e(old('kategori') == 'kebersihan' ? 'selected' : ''); ?>>
                                Kebersihan Kelas
                            </option>
                            <option value="konsumsi" <?php echo e(old('kategori') == 'konsumsi' ? 'selected' : ''); ?>>
                                Konsumsi/Snack
                            </option>
                            <option value="dekorasi" <?php echo e(old('kategori') == 'dekorasi' ? 'selected' : ''); ?>>
                                Dekorasi Kelas
                            </option>
                            <option value="transportasi" <?php echo e(old('kategori') == 'transportasi' ? 'selected' : ''); ?>>
                                Transportasi
                            </option>
                            <option value="hadiah" <?php echo e(old('kategori') == 'hadiah' ? 'selected' : ''); ?>>
                                Hadiah/Apresiasi
                            </option>
                            <option value="kegiatan_belajar" <?php echo e(old('kategori') == 'kegiatan_belajar' ? 'selected' : ''); ?>>
                                Kegiatan Belajar
                            </option>
                            <option value="administrasi" <?php echo e(old('kategori') == 'administrasi' ? 'selected' : ''); ?>>
                                Administrasi
                            </option>
                            <option value="lainnya" <?php echo e(old('kategori') == 'lainnya' ? 'selected' : ''); ?>>
                                Lainnya
                            </option>
                        </select>
                        <?php $__errorArgs = ['kategori'];
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

                    <!-- Nominal -->
                    <div>
                        <label for="nominal" class="block text-sm font-medium text-gray-700 mb-2">
                            Nominal (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" 
                                   id="nominal" 
                                   name="nominal" 
                                   value="<?php echo e(old('nominal')); ?>"
                                   min="1"
                                   max="<?php echo e($saldoKas); ?>"
                                   step="1"
                                   placeholder="0"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Maksimal: Rp <?php echo e(number_format($saldoKas, 0, ',', '.')); ?>

                        </p>
                        <?php $__errorArgs = ['nominal'];
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

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan Detail <span class="text-red-500">*</span>
                        </label>
                        <textarea id="keterangan" 
                                  name="keterangan" 
                                  rows="4"
                                  placeholder="Contoh: Pembelian spidol dan penghapus untuk kelas, 5 spidol x Rp5.000 + 2 penghapus x Rp3.000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  required><?php echo e(old('keterangan')); ?></textarea>
                        <?php $__errorArgs = ['keterangan'];
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

                    <!-- Upload Bukti Transaksi -->
                    <div>
                        <label for="bukti_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                            Bukti Transaksi <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               id="bukti_transaksi" 
                               name="bukti_transaksi"
                               accept="image/*,.pdf"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        <p class="mt-1 text-xs text-gray-500">
                            Format yang didukung: JPG, PNG, PDF. Maksimal 2MB. <strong>Wajib upload bukti untuk pengeluaran!</strong>
                        </p>
                        <?php $__errorArgs = ['bukti_transaksi'];
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

                    <!-- Preview Upload -->
                    <div id="preview-container" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div class="border border-gray-300 rounded-md p-4">
                            <img id="preview-image" class="max-w-full h-32 object-contain hidden" alt="Preview">
                            <div id="preview-file" class="hidden flex items-center space-x-2">
                                <i class="fas fa-file-pdf text-red-500"></i>
                                <span id="preview-filename" class="text-sm text-gray-700"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Peringatan Saldo -->
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium mb-1">Perhatian:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan nominal tidak melebihi saldo kas kelas</li>
                                    <li>Wajib upload bukti transaksi (struk/nota)</li>
                                    <li>Keterangan harus detail dan jelas</li>
                                    <li>Transaksi tidak dapat diubah setelah disimpan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="<?php echo e(route('siswa.bendahara.kas-keluar')); ?>" 
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Kas Keluar
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('bukti_transaksi');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const previewFile = document.getElementById('preview-file');
    const previewFilename = document.getElementById('preview-filename');
    const nominalInput = document.getElementById('nominal');
    const saldoKas = <?php echo e($saldoKas); ?>;

    // File preview
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            previewContainer.classList.remove('hidden');
            
            if (file.type.startsWith('image/')) {
                // Preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    previewFile.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                // Preview file PDF
                previewImage.classList.add('hidden');
                previewFile.classList.remove('hidden');
                previewFilename.textContent = file.name;
            }
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    // Validasi nominal
    nominalInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = value;
        
        // Validasi saldo
        const nominal = parseInt(value) || 0;
        if (nominal > saldoKas) {
            e.target.setCustomValidity('Nominal tidak boleh melebihi saldo kas kelas');
            e.target.classList.add('border-red-500');
        } else {
            e.target.setCustomValidity('');
            e.target.classList.remove('border-red-500');
        }
    });

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const nominal = parseInt(nominalInput.value) || 0;
        if (nominal > saldoKas) {
            e.preventDefault();
            alert('Nominal tidak boleh melebihi saldo kas kelas!');
            nominalInput.focus();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\bendahara\kas-keluar.blade.php ENDPATH**/ ?>