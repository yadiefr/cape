

<?php $__env->startSection('title', 'Tambah Jadwal Bel'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Jadwal Bel</h2>
        <a href="<?php echo e(route('admin.bel.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>
            <div>
                <p class="font-bold">Terjadi kesalahan:</p>
                <ul class="list-disc ml-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form action="<?php echo e(route('admin.bel.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Bel -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Bel <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="<?php echo e(old('nama')); ?>" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        placeholder="Contoh: Bel Masuk Jam Pertama">
                </div>

                <!-- Waktu -->
                <div>
                    <label for="waktu" class="block text-sm font-medium text-gray-700 mb-1">Waktu <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu" id="waktu" value="<?php echo e(old('waktu')); ?>" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <!-- Hari -->
                <div>
                    <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="hari" id="hari" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Setiap Hari</option>
                        <?php $__currentLoopData = $daftarHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($day); ?>" <?php echo e(old('hari') == $day ? 'selected' : ''); ?>><?php echo e($dayName); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika bel berlaku setiap hari</p>
                </div>

                <!-- Tipe Bel -->
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Bel <span class="text-red-500">*</span></label>
                    <select name="tipe" id="tipe" required 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <?php $__currentLoopData = $tipeBel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e(old('tipe') == $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Icon -->
                <div>
                    <label for="ikon" class="block text-sm font-medium text-gray-700 mb-1">Ikon <span class="text-red-500">*</span></label>
                    <select name="ikon" id="ikon" required 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <?php $__currentLoopData = $pilihanIkon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e(old('ikon') == $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Warna -->
                <div>
                    <label for="kode_warna" class="block text-sm font-medium text-gray-700 mb-1">Kode Warna</label>
                    <div class="flex space-x-2">
                        <input type="color" name="kode_warna" id="kode_warna" value="<?php echo e(old('kode_warna', '#3B82F6')); ?>"
                            class="h-10 w-10 rounded border-gray-300 shadow-sm">
                        <input type="text" id="kode_warna_text" value="<?php echo e(old('kode_warna', '#3B82F6')); ?>" readonly
                            class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-50">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pilih warna untuk tampilan bel</p>
                </div>

                <!-- File Suara -->
                <div>
                    <label for="file_suara" class="block text-sm font-medium text-gray-700 mb-1">File Suara</label>
                    <input type="file" name="file_suara" id="file_suara" accept="audio/*"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Format: MP3, WAV (maks. 2MB)</p>
                </div>

                <!-- Status Aktif -->
                <div>
                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="aktif" id="aktif" checked
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 h-5 w-5">
                        <label for="aktif" class="ml-2 block text-sm text-gray-700">Aktifkan jadwal bel ini</label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        placeholder="Deskripsi opsional untuk bel ini..."><?php echo e(old('deskripsi')); ?></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Jadwal Bel
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorPicker = document.getElementById('kode_warna');
        const colorText = document.getElementById('kode_warna_text');

        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\bel\create.blade.php ENDPATH**/ ?>