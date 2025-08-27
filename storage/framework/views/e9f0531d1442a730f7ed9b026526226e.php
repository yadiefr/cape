

<?php $__env->startSection('title', 'Tambah Jadwal Ujian'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo e(route('admin.ujian.dashboard')); ?>" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-home w-4 h-4"></i>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 w-3 h-3 mx-1"></i>
                    <a href="<?php echo e(route('admin.ujian.jadwal.index')); ?>" class="text-gray-600 hover:text-gray-900">Jadwal Ujian</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 w-3 h-3 mx-1"></i>
                    <span class="text-gray-500">Tambah Jadwal</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Jadwal Ujian</h1>
            <p class="text-gray-600 mt-1">Buat jadwal ujian baru untuk siswa</p>
        </div>
        <a href="<?php echo e(route('admin.ujian.jadwal.index')); ?>" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Jadwal Ujian</h3>
                </div>
                
                <form action="<?php echo e(route('admin.ujian.jadwal.store')); ?>" method="POST" class="p-6">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Ujian <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_ujian" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: Ujian Tengah Semester">
                            <?php $__errorArgs = ['nama_ujian'];
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
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Ujian <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_ujian" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Jenis Ujian</option>
                                <option value="uts">Ujian Tengah Semester</option>
                                <option value="uas">Ujian Akhir Semester</option>
                                <option value="quiz">Quiz/Kuis</option>
                                <option value="tugas">Tugas</option>
                                <option value="praktek">Ujian Praktek</option>
                            </select>
                            <?php $__errorArgs = ['jenis_ujian'];
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select name="mata_pelajaran_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Mata Pelajaran</option>
                                <?php $__currentLoopData = $mataPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($mapel->id); ?>" <?php echo e(old('mata_pelajaran_id') == $mapel->id ? 'selected' : ''); ?>>
                                        <?php echo e($mapel->nama); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['mata_pelajaran_id'];
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
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="kelas_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kelas</option>
                                <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelasItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kelasItem->id); ?>" <?php echo e(old('kelas_id') == $kelasItem->id ? 'selected' : ''); ?>>
                                        <?php echo e($kelasItem->nama_kelas); ?>

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
                    </div>

                    <!-- Schedule Information -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Waktu Pelaksanaan</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       min="<?php echo e(date('Y-m-d')); ?>">
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
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="waktu_mulai" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php $__errorArgs = ['waktu_mulai'];
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
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Durasi (menit) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="durasi" required min="1" max="480"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="60">
                                <?php $__errorArgs = ['durasi'];
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
                    </div>

                    <!-- Exam Settings -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Pengaturan Ujian</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bank Soal
                                </label>
                                <select name="bank_soal_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Bank Soal (Opsional)</option>
                                    <option value="1">Matematika - Aljabar Linear</option>
                                    <option value="2">Pemrograman - Algoritma Dasar</option>
                                    <option value="3">Bahasa Indonesia - Teks Argumentasi</option>
                                </select>
                                <?php $__errorArgs = ['bank_soal_id'];
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
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ruangan
                                </label>
                                <select name="ruangan_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Ruangan (Opsional)</option>
                                    <option value="1">Lab Komputer 1</option>
                                    <option value="2">Lab Komputer 2</option>
                                    <option value="3">Ruang Kelas 10A</option>
                                    <option value="4">Ruang Kelas 10B</option>
                                </select>
                                <?php $__errorArgs = ['ruangan_id'];
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
                    </div>

                    <!-- Additional Settings -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Pengaturan Tambahan</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="acak_soal" id="acak_soal" value="1"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="acak_soal" class="ml-2 text-sm text-gray-700">
                                    Acak urutan soal
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="acak_jawaban" id="acak_jawaban" value="1"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="acak_jawaban" class="ml-2 text-sm text-gray-700">
                                    Acak urutan jawaban
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="tampilkan_hasil" id="tampilkan_hasil" value="1"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="tampilkan_hasil" class="ml-2 text-sm text-gray-700">
                                    Tampilkan hasil setelah ujian selesai
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi/Catatan
                        </label>
                        <textarea name="deskripsi" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Tambahkan catatan atau instruksi khusus untuk ujian ini..."></textarea>
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

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="<?php echo e(route('admin.ujian.jadwal.index')); ?>" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" name="action" value="draft"
                                class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                            Simpan sebagai Draft
                        </button>
                        <button type="submit" name="action" value="schedule"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            Jadwalkan Ujian
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <!-- Quick Tips -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="p-2 bg-blue-600 rounded-lg">
                            <i class="fas fa-lightbulb text-white w-4 h-4"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-800 ml-3">Tips Penjadwalan</h4>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                            Pastikan tidak ada jadwal bentrok
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                            Siapkan bank soal terlebih dahulu
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                            Berikan waktu persiapan yang cukup
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                            Informasikan kepada siswa H-1
                        </li>
                    </ul>
                </div>

                <!-- Schedule Overview -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Jadwal Hari Ini</h4>
                    <div class="space-y-3">
                        <div class="text-xs">
                            <div class="font-medium text-gray-700">08:00 - 10:00</div>
                            <div class="text-gray-500">Matematika - X RPL 1</div>
                        </div>
                        <div class="text-xs">
                            <div class="font-medium text-gray-700">10:30 - 12:00</div>
                            <div class="text-gray-500">Bahasa Indonesia - XI RPL 2</div>
                        </div>
                        <div class="text-xs">
                            <div class="font-medium text-gray-700">13:00 - 14:30</div>
                            <div class="text-gray-500">Pemrograman - XII RPL 1</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto calculate end time
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const waktuMulaiInput = document.querySelector('input[name="waktu_mulai"]');
    const durasiInput = document.querySelector('input[name="durasi"]');
    
    function updateEndTime() {
        if (waktuMulaiInput.value && durasiInput.value) {
            const [hours, minutes] = waktuMulaiInput.value.split(':').map(Number);
            const durasi = parseInt(durasiInput.value);
            
            const startTime = new Date();
            startTime.setHours(hours, minutes, 0, 0);
            
            const endTime = new Date(startTime.getTime() + durasi * 60000);
            const endHours = String(endTime.getHours()).padStart(2, '0');
            const endMinutes = String(endTime.getMinutes()).padStart(2, '0');
            
            // Display end time info (you can add a display element for this)
            console.log(`Ujian berakhir pada: ${endHours}:${endMinutes}`);
        }
    }
    
    waktuMulaiInput?.addEventListener('change', updateEndTime);
    durasiInput?.addEventListener('input', updateEndTime);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.ujian', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\ujian\jadwal\create.blade.php ENDPATH**/ ?>