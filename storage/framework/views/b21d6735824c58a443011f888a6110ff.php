

<?php $__env->startSection('title', 'Input Kas Masuk - Bendahara'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Input Kas Masuk</h1>
            </div>
            <div class="text-left sm:text-right">
                <p class="text-xs sm:text-sm text-gray-500">Tanggal Input</p>
                <p class="text-lg sm:text-xl font-bold text-blue-600"><?php echo e($tanggalObj->format('d/m/Y')); ?></p>
            </div>
        </div>
    </div>

    <!-- Transaksi Hari Ini -->
    <?php if($transaksiHariIni->count() > 0): ?>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <h3 class="text-lg font-semibold text-green-800 mb-3">
            <i class="fas fa-check-circle mr-2"></i>Transaksi Hari Ini
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            <?php $__currentLoopData = $transaksiHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded p-3 border border-green-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-green-700">Kas Masuk</p>
                        <?php
                            // Bersihkan keterangan dari nama siswa jika ada
                            $keterangan = $transaksi->keterangan;
                            if ($transaksi->siswa) {
                                $keterangan = preg_replace('/ - ' . preg_quote($transaksi->siswa->nama_lengkap, '/') . '$/', '', $keterangan);
                                $keterangan = preg_replace('/^Iuran bulanan kas kelas - [^-]*$/', 'Kas masuk', $keterangan);
                            }
                        ?>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e(Str::limit($keterangan, 30)); ?></p>
                    </div>
                    <span class="text-green-700 font-bold">
                        Rp <?php echo e(number_format($transaksi->nominal, 0, ',', '.')); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-3 pt-3 border-t border-green-300">
            <p class="text-green-800 font-bold">
                Total Kas Masuk: Rp <?php echo e(number_format($transaksiHariIni->sum('nominal'), 0, ',', '.')); ?>

            </p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <div class="font-medium">
            <i class="fas fa-exclamation-circle mr-2"></i>Terjadi kesalahan:
        </div>
        <ul class="mt-2 ml-4 list-disc">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Form Input Kas Masuk -->
    <form action="<?php echo e(route('siswa.bendahara.simpan-kas-masuk')); ?>" method="POST" id="kasMasukForm">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="tanggal" value="<?php echo e($tanggal); ?>">
        
        <!-- Iuran Bulanan Siswa -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">
                    <i class="fas fa-users mr-2 text-blue-600"></i>Input Pembayaran Siswa
                </h3>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full lg:w-auto">
                    <label class="text-xs sm:text-sm font-medium text-gray-700">Nominal per siswa:</label>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative flex-1 sm:flex-none">
                            <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                            <input type="number"
                                   id="nominalBulk"
                                   min="1000"
                                   step="1000"
                                   placeholder=""
                                   class="pl-8 pr-3 py-2 w-full sm:w-32 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                        <button type="button"
                                onclick="applyBulkNominal()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs sm:text-sm transition duration-200 whitespace-nowrap">
                            Terapkan Semua
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <?php $__currentLoopData = $siswaKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswaItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900"><?php echo e($siswaItem->nama_lengkap); ?></p>
                            <p class="text-sm text-gray-500">NIS: <?php echo e($siswaItem->nis); ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                            <input type="number" 
                                   name="nominal[<?php echo e($siswaItem->id); ?>]" 
                                   id="nominal_<?php echo e($siswaItem->id); ?>"
                                   min="0" 
                                   step="1000"
                                   value="0"
                                   placeholder="0"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   onchange="updateSiswaStatus(<?php echo e($siswaItem->id); ?>)">
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Keterangan Umum -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Keterangan Umum</h3>
            <textarea name="keterangan_umum" 
                      rows="3"
                      placeholder="Contoh: Iuran kas bulan Januari 2025"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end space-x-4">
            <a href="<?php echo e(route('siswa.bendahara.kas-masuk')); ?>" 
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Simpan Kas Masuk
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Fungsi untuk menerapkan nominal bulk ke semua siswa
function applyBulkNominal() {
    const bulkValue = document.getElementById('nominalBulk').value;
    if (bulkValue && parseInt(bulkValue) >= 1000) {
        // Apply to all student inputs
        document.querySelectorAll('input[name*="nominal["]').forEach(inputElement => {
            inputElement.value = bulkValue;
            // Update visual status
            const siswaId = inputElement.id.replace('nominal_', '');
            updateSiswaStatus(siswaId);
        });
    } else {
        alert('Masukkan nominal minimal Rp 1.000');
    }
}

// Fungsi untuk update status visual siswa
function updateSiswaStatus(siswaId) {
    const nominalInput = document.getElementById(`nominal_${siswaId}`);
    if (!nominalInput) return; // Jika input tidak ditemukan, keluar
    
    const value = parseInt(nominalInput.value) || 0;
    const card = nominalInput.closest('.border');
    if (!card) return; // Jika card tidak ditemukan, keluar
    
    const iconContainer = card.querySelector('.w-8.h-8');
    if (!iconContainer) return; // Jika icon container tidak ditemukan, keluar
    
    if (value >= 1000) {
        card.classList.remove('bg-white');
        card.classList.add('bg-green-50', 'border-green-300');
        iconContainer.classList.remove('bg-gray-200');
        iconContainer.classList.add('bg-green-500');
        iconContainer.innerHTML = '<i class="fas fa-check text-white text-sm"></i>';
    } else {
        card.classList.remove('bg-green-50', 'border-green-300');
        card.classList.add('bg-white');
        iconContainer.classList.remove('bg-green-500');
        iconContainer.classList.add('bg-gray-200');
        iconContainer.innerHTML = '<i class="fas fa-user text-gray-500 text-sm"></i>';
    }
}

// Form validation
document.getElementById('kasMasukForm').addEventListener('submit', function(e) {
    // Cek apakah ada iuran siswa yang valid
    let hasValidSiswa = false;
    let validData = [];
    
    // Check iuran siswa
    const siswaInputs = document.querySelectorAll('input[name*="nominal["]');
    siswaInputs.forEach(inputElement => {
        const value = parseInt(inputElement.value) || 0;
        if (value >= 1000) {
            hasValidSiswa = true;
            validData.push({
                name: inputElement.name,
                value: value
            });
        }
    });
    
    // Debug: tampilkan data yang akan dikirim
    console.log('Data yang akan dikirim:', validData);
    console.log('Has valid siswa:', hasValidSiswa);
    
    if (!hasValidSiswa) {
        e.preventDefault();
        alert('Isi minimal satu iuran siswa dengan nominal ≥ Rp 1.000!');
        return false;
    }
    
    // Tampilkan konfirmasi
    const totalSiswa = validData.length;
    const totalNominal = validData.reduce((sum, item) => sum + item.value, 0);
    
    if (!confirm(`Simpan ${totalSiswa} transaksi dengan total Rp ${totalNominal.toLocaleString()}?`)) {
        e.preventDefault();
        return false;
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\bendahara\input-kas-masuk.blade.php ENDPATH**/ ?>