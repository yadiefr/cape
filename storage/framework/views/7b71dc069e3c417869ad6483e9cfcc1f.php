

<?php $__env->startSection('title', 'Detail Siswa - ' . $siswa->nama_lengkap); ?>

<?php $__env->startSection('main-content'); ?>
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-user text-blue-600 mr-3"></i>
                    Detail Siswa
                </h1>
                <p class="text-gray-600"><?php echo e($siswa->nama_lengkap); ?></p>
            </div>
            <a href="<?php echo e(route('guru.wali-kelas.dashboard')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Left Column - Profile Card -->
        <div class="col-span-12 lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <!-- Profile Photo -->
                <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 flex justify-center">
                    <div class="h-40 w-40 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-md">
                        <img src="<?php echo e($siswa->foto ? asset('storage/' . $siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($siswa->nama_lengkap) . '&background=3b82f6&color=ffffff'); ?>" 
                             alt="Foto Siswa" 
                             class="h-full w-full object-cover">
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="p-6">
                    <h3 class="font-bold text-xl text-gray-800 text-center mb-2"><?php echo e($siswa->nama); ?></h3>
                    <p class="text-gray-500 text-center mb-1"><?php echo e($siswa->nama_lengkap); ?></p>
                    <p class="text-gray-500 text-center"><?php echo e($siswa->kelas->nama_kelas ?? 'Belum ditentukan'); ?></p>

                    <div class="my-4 flex justify-center">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            <?php echo e($siswa->status); ?>

                        </span>
                    </div>

                    <!-- Quick Info -->
                    <div class="border-t pt-4 mt-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-id-card text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-600">NIS: <?php echo e($siswa->nis); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-600">TTL: <?php echo e($siswa->tempat_lahir); ?>, <?php echo e($siswa->tanggal_lahir); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-600"><?php echo e($siswa->telepon ?? 'Belum ditentukan'); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-600"><?php echo e($siswa->jurusan->nama_jurusan ?? 'Belum ditentukan'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Content -->
        <div class="col-span-12 lg:col-span-9">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100">
                    <nav class="flex flex-wrap" aria-label="Tabs">
                        <button type="button" 
                                class="px-6 py-3 border-b-2 border-blue-500 text-blue-600 text-sm font-medium focus:outline-none" 
                                id="tab-biodata">
                            Biodata
                        </button>
                        <button type="button" 
                                class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 text-sm font-medium focus:outline-none" 
                                id="tab-akademik">
                            Akademik
                        </button>
                        <button type="button" 
                                class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 text-sm font-medium focus:outline-none" 
                                id="tab-ortu">
                            Orang Tua
                        </button>
                        <button type="button" 
                                class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 text-sm font-medium focus:outline-none" 
                                id="tab-keuangan">
                            Keuangan
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="p-6">
                    <!-- Biodata Tab Content -->
                    <div id="content-biodata" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-800 mb-4">Informasi Pribadi</h4>
                                <dl class="space-y-3">
                                    <div class="grid grid-cols-3 gap-4 py-3 px-4 bg-gray-50 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($siswa->nama_lengkap); ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-3 px-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Tempat Lahir</dt>
                                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($siswa->tempat_lahir); ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-3 px-4 bg-gray-50 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($siswa->tanggal_lahir); ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-3 px-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($siswa->jenis_kelamin); ?></dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Right Column -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-800 mb-4">Informasi Kontak</h4>
                                <dl class="space-y-3">
                                    <div class="grid grid-cols-3 gap-4 py-3 px-4 bg-gray-50 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($siswa->email); ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-3 px-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($siswa->telepon); ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-3 px-4 bg-gray-50 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($siswa->alamat); ?></dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Other tab contents -->
                    <div id="content-akademik" class="hidden">
                        <div class="space-y-6">
                            <!-- Academic Overview Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-blue-50">
                                            <i class="fas fa-graduation-cap text-blue-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Kelas</p>
                                            <p class="text-lg font-semibold text-gray-800"><?php echo e($siswa->kelas->nama_kelas ?? 'Belum ditentukan'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-green-50">
                                            <i class="fas fa-book text-green-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Jurusan</p>
                                            <p class="text-lg font-semibold text-gray-800"><?php echo e($siswa->jurusan->nama_jurusan ?? 'Belum ditentukan'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-purple-50">
                                            <i class="fas fa-calendar-alt text-purple-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Tahun Masuk</p>
                                            <p class="text-lg font-semibold text-gray-800"><?php echo e($siswa->tahun_masuk ?? 'Belum ditentukan'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-yellow-50">
                                            <i class="fas fa-user-graduate text-yellow-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Status</p>
                                            <p class="text-lg font-semibold text-gray-800"><?php echo e($siswa->status ?? 'Aktif'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Details -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6">
                                    <h4 class="text-lg font-medium text-gray-800 mb-4">Data Akademik</h4>
                                    
                                    <!-- Attendance Summary -->
                                    <div class="mb-6">
                                        <h5 class="text-sm font-medium text-gray-600 mb-3">Rekap Kehadiran Semester Ini</h5>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div class="bg-green-50 p-3 rounded-lg">
                                                <p class="text-sm text-green-600">Hadir</p>
                                                <p class="text-2xl font-bold text-green-700"><?php echo e($siswa->kehadiran_hadir ?? '0'); ?></p>
                                            </div>
                                            <div class="bg-yellow-50 p-3 rounded-lg">
                                                <p class="text-sm text-yellow-600">Izin</p>
                                                <p class="text-2xl font-bold text-yellow-700"><?php echo e($siswa->kehadiran_izin ?? '0'); ?></p>
                                            </div>
                                            <div class="bg-orange-50 p-3 rounded-lg">
                                                <p class="text-sm text-orange-600">Sakit</p>
                                                <p class="text-2xl font-bold text-orange-700"><?php echo e($siswa->kehadiran_sakit ?? '0'); ?></p>
                                            </div>
                                            <div class="bg-red-50 p-3 rounded-lg">
                                                <p class="text-sm text-red-600">Alpha</p>
                                                <p class="text-2xl font-bold text-red-700"><?php echo e($siswa->kehadiran_alpha ?? '0'); ?></p>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Extracurricular Activities -->
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-600 mb-3">Kegiatan Ekstrakurikuler</h5>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="space-y-4">
                                                <?php $__empty_1 = true; $__currentLoopData = $siswa->ekstrakurikuler ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-star text-blue-400"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800"><?php echo e($ekskul->nama_ekskul); ?></p>
                                                        <p class="text-sm text-gray-500"><?php echo e($ekskul->jabatan ?? 'Anggota'); ?></p>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <p class="text-sm text-gray-500 italic">Belum mengikuti kegiatan ekstrakurikuler</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="content-ortu" class="hidden">
                        <div class="space-y-6">
                            <!-- Data Ayah -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <i class="fas fa-user text-blue-500 mr-2"></i>
                                        <h4 class="text-lg font-medium text-gray-800">Data Ayah</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 block">Nama Lengkap</label>
                                                <p class="text-gray-800"><?php echo e($siswa->nama_ayah ?? 'Belum ditentukan'); ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 block">Pekerjaan</label>
                                                <p class="text-gray-800"><?php echo e($siswa->pekerjaan_ayah ?? 'Belum ditentukan'); ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 block">No. Telepon</label>
                                                <p class="text-gray-800"><?php echo e($siswa->no_telp_ayah ?? 'Belum ditentukan'); ?></p>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Ibu -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <i class="fas fa-user text-pink-500 mr-2"></i>
                                        <h4 class="text-lg font-medium text-gray-800">Data Ibu</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 block">Nama Lengkap</label>
                                                <p class="text-gray-800"><?php echo e($siswa->nama_ibu ?? 'Belum ditentukan'); ?></p>
                                            </div>
                                             <div>
                                                <label class="text-sm font-medium text-gray-500 block">Pekerjaan</label>
                                                <p class="text-gray-800"><?php echo e($siswa->pekerjaan_ibu ?? 'Belum ditentukan'); ?></p>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-500 block">No. Telepon</label>
                                                <p class="text-gray-800"><?php echo e($siswa->no_telp_ibu ?? 'Belum ditentukan'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keuangan Tab -->
                        <div id="content-keuangan" class="hidden">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800 border-b border-gray-200 pb-2 mb-4">Informasi Keuangan</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                            <h4 class="font-medium text-purple-700 text-sm">Total Tagihan</h4>
                                            <p class="text-2xl font-bold text-purple-900 mt-1">Rp <?php echo e(number_format($totalTagihan ?? 0,0,',','.')); ?></p>
                                            <p class="text-sm text-purple-600">Tahun Ajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y') + 1); ?></p>
                                        </div>
                                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                            <h4 class="font-medium text-green-700 text-sm">Total Telah Dibayar</h4>
                                            <p class="text-2xl font-bold text-green-900 mt-1">Rp <?php echo e(number_format($totalDibayar ?? 0,0,',','.')); ?></p>
                                            <p class="text-sm text-green-600">Tahun Ajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y') + 1); ?></p>
                                        </div>
                                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                            <h4 class="font-medium text-red-700 text-sm">Total Tunggakan</h4>
                                            <p class="text-2xl font-bold text-red-900 mt-1">Rp <?php echo e(number_format($totalTunggakan ?? 0,0,',','.')); ?></p>
                                            <p class="text-sm text-red-600">Tahun Ajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y') + 1); ?></p>
                                        </div>
                                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                            <h4 class="font-medium text-blue-700 text-sm">Status Pembayaran</h4>
                                            <p class="text-2xl font-bold text-blue-900 mt-1"><?php echo e($siswa->status_keuangan ?? 'Belum Lunas'); ?></p>
                                            <p class="text-sm text-blue-600">Update terakhir: <?php echo e($siswa->updated_at ? $siswa->updated_at->format('d/m/Y') : '-'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                    <h4 class="font-medium text-gray-800 mb-4">Rincian Tagihan</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dibayar</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php $__empty_1 = true; $__currentLoopData = $detailTagihan ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($tagihan['nama_tagihan']); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?php echo e(number_format($tagihan['nominal'],0,',','.')); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?php echo e(number_format($tagihan['total_dibayar'],0,',','.')); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?php echo e(number_format($tagihan['sisa'],0,',','.')); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <?php if($tagihan['status'] == 'Lunas'): ?>
                                                            <span class="px-3 py-1.5 rounded-full bg-green-100 text-green-800 text-sm font-medium">Lunas</span>
                                                        <?php elseif($tagihan['status'] == 'Sebagian'): ?>
                                                            <span class="px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">Sebagian</span>
                                                        <?php else: ?>
                                                            <span class="px-3 py-1.5 rounded-full bg-red-100 text-red-800 text-sm font-medium">Belum Lunas</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">Tidak ada tagihan</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                
                                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                    <h4 class="font-medium text-gray-800 mb-4">Riwayat Pembayaran</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php $__empty_1 = true; $__currentLoopData = $siswa->pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($pembayaran->tanggal); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($pembayaran->keterangan); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-700 font-semibold">Rp <?php echo e(number_format($pembayaran->jumlah,0,',','.')); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-3 py-1.5 rounded-full text-sm font-medium <?php echo e($pembayaran->status == 'Lunas' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>"><?php echo e($pembayaran->status); ?></span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-sm">Belum ada pembayaran</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Base styles */
.hidden {
    display: none !important;
}

/* Grid Layout */
@media (min-width: 1024px) {
    .col-span-12 {
        grid-column: span 12 / span 12;
    }
    
    .lg\:col-span-3 {
        grid-column: span 3 / span 3;
    }
    
    .lg\:col-span-9 {
        grid-column: span 9 / span 9;
    }

    .sticky {
        position: sticky;
        top: 1.5rem;
    }
}

/* Tab Styles */
.border-blue-500 {
    border-color: #3B82F6;
}

.text-blue-600 {
    color: #2563EB;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[id^="tab-"]');
    const tabContents = document.querySelectorAll('[id^="content-"]');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active states
            tabButtons.forEach(b => {
                b.classList.remove('border-blue-500', 'text-blue-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active state to clicked button
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');

            // Hide all contents
            tabContents.forEach(content => content.classList.add('hidden'));

            // Show corresponding content
            const contentId = 'content-' + button.id.split('-')[1];
            document.getElementById(contentId).classList.remove('hidden');
        });
    });
});
</script>
<?php $__env->stopSection(); ?> 

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\wali-kelas\detail-siswa.blade.php ENDPATH**/ ?>