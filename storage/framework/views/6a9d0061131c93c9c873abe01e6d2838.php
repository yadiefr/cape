<?php $__env->startSection('title', 'Detail Siswa - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<!-- Page Content -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex flex-col space-y-6">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden">
            <div class="absolute right-0 -top-12 h-40 w-40 bg-blue-100 opacity-50 rounded-full"></div>
            <div class="absolute -right-8 top-20 h-20 w-20 bg-blue-200 opacity-30 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user text-blue-600 mr-3"></i>
                            Detail Siswa
                        </h1>
                        <p class="text-gray-600 mt-1">Melihat informasi lengkap siswa</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="<?php echo e(route('admin.siswa.edit', $siswa->id)); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Data
                        </a>
                        <a href="<?php echo e(route('admin.siswa.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Profile -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Column - Photo and Basic Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 flex justify-center">
                        <div class="h-40 w-40 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-md">
                            <img id="photo-preview" src="<?php echo e($siswa->foto ? asset('storage/' . $siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($siswa->nama_lengkap) . '&background=3b82f6&color=ffffff'); ?>" alt="Foto Siswa" class="h-full w-full object-cover">
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl text-gray-800 text-center mb-2"><?php echo e($siswa->nama); ?></h3>
                        <p class="text-gray-500 text-center"><?php echo e($siswa->nama_lengkap); ?></p>
                        <p class="text-gray-500 text-center"><?php echo e($siswa->kelas->nama_kelas ?? 'Belum ditentukan'); ?></p>

                        <div class="my-4 flex justify-center">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                <?php echo e($siswa->status); ?>

                            </span>
                        </div>
                        
                        <div class="border-t pt-4 mt-4">
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <i class="fas fa-id-card text-gray-500 w-6"></i>
                                    <span class="ml-3 text-gray-600">NIS: <?php echo e($siswa->nis ?? 'Belum ditentukan'); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-baby text-gray-500 w-6"></i>
                                    <span class="ml-3 text-gray-600">TTL: 
                                        <?php if($siswa->tempat_lahir && $siswa->tanggal_lahir): ?>
                                            <?php echo e($siswa->tempat_lahir); ?>, <?php echo e(\Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y')); ?>

                                        <?php else: ?>
                                            Belum ditentukan
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-birthday-cake text-gray-500 w-6"></i>
                                    <span class="ml-3 text-gray-600">Usia: 
                                        <?php if($siswa->tanggal_lahir): ?>
                                            <?php echo e(\Carbon\Carbon::parse($siswa->tanggal_lahir)->age); ?> tahun
                                        <?php else: ?>
                                            Belum ditentukan
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-500 w-6"></i>
                                    <span class="ml-3 text-gray-600"><?php echo e($siswa->telepon ?? 'Belum ditentukan'); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-graduation-cap text-gray-500 w-6"></i>
                                    <span class="ml-3 text-gray-600"><?php echo e($siswa->jurusan->nama_jurusan ?? 'Belum ditentukan'); ?></span>
                                </div>

                                <div class="flex items-center">
                                    <i class="fas fa-school text-gray-500 w-6"></i>
                                    <span class="ml-3 text-gray-600"><?php echo e($siswa->kelas->nama_kelas ?? 'Belum ditentukan'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                    <div class="p-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-medium text-gray-700">Tindakan Cepat</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <a href="<?php echo e(route('admin.nilai.index', ['siswa' => 1])); ?>" class="flex items-center p-2 rounded-md hover:bg-blue-50 transition-all group">
                                <i class="fas fa-chart-line text-blue-500 w-6 group-hover:text-blue-600"></i>
                                <span class="ml-2 text-gray-700 group-hover:text-blue-600">Lihat Nilai</span>
                            </a>
                            <a href="<?php echo e(route('admin.absensi.index', ['siswa' => 1])); ?>" class="flex items-center p-2 rounded-md hover:bg-blue-50 transition-all group">
                                <i class="fas fa-clipboard-check text-blue-500 w-6 group-hover:text-blue-600"></i>
                                <span class="ml-2 text-gray-700 group-hover:text-blue-600">Lihat Kehadiran</span>
                            </a>
                            <a href="<?php echo e(route('admin.jadwal.index', ['kelas' => 3])); ?>" class="flex items-center p-2 rounded-md hover:bg-blue-50 transition-all group">
                                <i class="fas fa-calendar-alt text-blue-500 w-6 group-hover:text-blue-600"></i>
                                <span class="ml-2 text-gray-700 group-hover:text-blue-600">Lihat Jadwal</span>
                            </a>
                            <button class="flex items-center p-2 rounded-md hover:bg-red-50 transition-all group w-full text-left" onclick="if(confirm('Apakah Anda yakin ingin mereset password siswa ini?')) alert('Password berhasil direset!');">
                                <i class="fas fa-key text-red-500 w-6 group-hover:text-red-600"></i>
                                <span class="ml-2 text-gray-700 group-hover:text-red-600">Reset Password</span>
                            </button>
                            <button class="flex items-center p-2 rounded-md hover:bg-yellow-50 transition-all group w-full text-left" onclick="if(confirm('Apakah Anda yakin ingin mengirim email pengingat tagihan ke orang tua siswa ini?')) alert('Email pengingat berhasil dikirim!');">
                                <i class="fas fa-envelope-open-text text-yellow-500 w-6 group-hover:text-yellow-600"></i>
                                <span class="ml-2 text-gray-700 group-hover:text-yellow-600">Kirim Pemberitahuan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Detailed Information -->
            <div class="lg:col-span-3">
                <!-- Tab Navigation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="flex border-b border-gray-100">
                        <button type="button" class="px-6 py-3 border-b-2 border-blue-500 font-medium text-sm text-blue-600 focus:outline-none" id="tab-biodata">
                            Biodata
                        </button>
                        <button type="button" class="px-6 py-3 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" id="tab-akademik">
                            Akademik
                        </button>
                        <button type="button" class="px-6 py-3 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" id="tab-ortu">
                            Orang Tua
                        </button>
                        <button type="button" class="px-6 py-3 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" id="tab-keuangan">
                            Keuangan
                        </button>
                        <button type="button" class="px-6 py-3 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" id="tab-galeri">
                            Galeri
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Biodata Tab -->
                        <div id="content-biodata" class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800 border-b border-gray-200 pb-2 mb-4">Data Pribadi</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <dl>
                                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->nama_lengkap); ?></dd>
                                            </div>
                                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">Tempat Lahir</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->tempat_lahir); ?></dd>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    <?php if($siswa->tanggal_lahir): ?>
                                                        <?php echo e(\Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y')); ?>

                                                    <?php else: ?>
                                                        <span class="text-gray-400 italic">Belum ditentukan</span>
                                                    <?php endif; ?>
                                                </dd>
                                            </div>
                                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->jenis_kelamin); ?></dd>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">Agama</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->agama); ?></dd>
                                            </div>
                                        </dl>
                                    </div>
                                    
                                    <div>
                                        <dl>
                                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">NIS</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->nis); ?></dd>
                                            </div>
                                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">NISN</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->nisn); ?></dd>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->email); ?></dd>
                                            </div>
                                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->telepon); ?></dd>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 rounded-lg mb-2">
                                                <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($siswa->alamat); ?></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Akademik Tab -->
                        <div id="content-akademik" class="hidden">
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <h3 class="text-lg font-medium text-gray-800 border-b border-gray-200 pb-2 mb-4">Data Akademik</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Kelas</p>
                                        <p class="text-base text-gray-900"><?php echo e($siswa->kelas->nama_kelas ?? 'Belum ditentukan'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Jurusan</p>
                                        <p class="text-base text-gray-900"><?php echo e($siswa->jurusan->nama_jurusan ?? 'Belum ditentukan'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Tahun Masuk</p>
                                        <p class="text-base text-gray-900"><?php echo e($siswa->tahun_masuk ?? 'Belum ditentukan'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Status</p>
                                        <p class="text-base text-gray-900"><?php echo e($siswa->status ?? 'Aktif'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Orang Tua Tab -->
                        <div id="content-ortu" class="hidden">
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <h3 class="text-lg font-medium text-gray-800 border-b border-gray-200 pb-2 mb-4">Data Orang Tua</h3>
                                <!-- Data Ayah -->
                                <div class="mb-6">
                                    <h4 class="text-md font-medium text-gray-700 mb-3">Data Ayah</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Nama Ayah</p>
                                            <p class="text-base text-gray-900"><?php echo e($siswa->nama_ayah ?? ''); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Pekerjaan Ayah</p>
                                            <p class="text-base text-gray-900"><?php echo e($siswa->pekerjaan_ayah ?? ''); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">No. Telepon Ayah</p>
                                            <p class="text-base text-gray-900"><?php echo e($siswa->no_telp_ayah ?? ''); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Data Ibu -->
                                <div>
                                    <h4 class="text-md font-medium text-gray-700 mb-3">Data Ibu</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Nama Ibu</p>
                                            <p class="text-base text-gray-900"><?php echo e($siswa->nama_ibu ?? ''); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Pekerjaan Ibu</p>
                                            <p class="text-base text-gray-900"><?php echo e($siswa->pekerjaan_ibu ?? ''); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">No. Telepon Ibu</p>
                                            <p class="text-base text-gray-900"><?php echo e($siswa->no_telp_ibu ?? ''); ?></p>
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
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                                        <div class="bg-purple-50 p-3 sm:p-4 rounded-lg border border-purple-200">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-2">
                                                    <h4 class="font-medium text-purple-700 text-xs sm:text-sm">Total Tagihan</h4>
                                                    <p class="text-base sm:text-xl font-bold text-purple-900 mt-0.5">Rp <?php echo e(number_format($siswa->total_tagihan ?? 0,0,',','.')); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-green-50 p-3 sm:p-4 rounded-lg border border-green-200">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-2">
                                                    <h4 class="font-medium text-green-700 text-xs sm:text-sm">Total Dibayar</h4>
                                                    <p class="text-base sm:text-xl font-bold text-green-900 mt-0.5">Rp <?php echo e(number_format($siswa->total_telah_dibayar ?? $siswa->pembayaran->sum('jumlah'),0,',','.')); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-red-50 p-3 sm:p-4 rounded-lg border border-red-200">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-2">
                                                    <h4 class="font-medium text-red-700 text-xs sm:text-sm">Tunggakan</h4>
                                                    <p class="text-base sm:text-xl font-bold text-red-900 mt-0.5">Rp <?php echo e(number_format($siswa->tunggakan ?? 0,0,',','.')); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 p-3 sm:p-4 rounded-lg border border-blue-200">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <?php if(($siswa->status_keuangan ?? 'Belum Lunas') === 'Lunas'): ?>
                                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="ml-2">
                                                    <h4 class="font-medium text-blue-700 text-xs sm:text-sm">Status</h4>
                                                    <p class="text-base sm:text-xl font-bold text-blue-900 mt-0.5"><?php echo e($siswa->status_keuangan ?? 'Belum Lunas'); ?></p>
                                                </div>
                                            </div>
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
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Jam</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php $__empty_1 = true; $__currentLoopData = $siswa->pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        <div><?php echo e(\Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y')); ?></div>
                                                        <small class="text-gray-500"><?php echo e(\Carbon\Carbon::parse($pembayaran->tanggal)->format('H:i:s')); ?></small>
                                                    </td>
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

                        <!-- Galeri Tab -->
                        <div id="content-galeri" class="hidden">
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-800">Galeri Siswa</h3>
                                    <a href="<?php echo e(route('admin.galeri.index')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-all flex items-center">
                                        <i class="fas fa-images mr-2"></i> Kelola Galeri Utama
                                    </a>
                                </div>
                                <?php if(isset($siswa->galeri) && count($siswa->galeri) > 0): ?>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        <?php $__currentLoopData = $siswa->galeri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="relative group border rounded-lg overflow-hidden bg-gray-50">
                                                <img src="<?php echo e(asset('storage/' . $foto->path)); ?>" alt="Foto Galeri" class="w-full h-40 object-cover">
                                                <div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                                    <a href="<?php echo e(asset('storage/' . $foto->path)); ?>" target="_blank" class="text-white bg-blue-600 px-3 py-1 rounded shadow hover:bg-blue-700 text-xs font-medium mr-2"><i class="fas fa-search"></i> Lihat</a>
                                                    <form action="<?php echo e(route('admin.galeri.destroy', $foto->id)); ?>" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-white bg-red-600 px-3 py-1 rounded shadow hover:bg-red-700 text-xs font-medium"><i class="fas fa-trash"></i> Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-gray-500 py-12">
                                        <i class="fas fa-image text-4xl mb-4"></i>
                                        <p>Belum ada foto galeri untuk siswa ini.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabIds = ['tab-biodata', 'tab-akademik', 'tab-ortu', 'tab-keuangan', 'tab-galeri'];
        const contentIds = ['content-biodata', 'content-akademik', 'content-ortu', 'content-keuangan', 'content-galeri'];

        // Helper: aktifkan tab dan konten sesuai index
        function activateTab(idx) {
            tabIds.forEach((tid, i) => {
                const tab = document.getElementById(tid);
                const content = document.getElementById(contentIds[i]);
                if (tab && content) {
                    if (i === idx) {
                        tab.classList.add('border-blue-500', 'text-blue-600');
                        tab.classList.remove('border-transparent', 'text-gray-500');
                        content.classList.remove('hidden');
                        content.classList.add('block');
                    } else {
                        tab.classList.remove('border-blue-500', 'text-blue-600');
                        tab.classList.add('border-transparent', 'text-gray-500');
                        content.classList.add('hidden');
                        content.classList.remove('block');
                    }
                }
            });
        }

        // Inisialisasi: tab pertama aktif
        activateTab(0);

        // Event listener untuk semua tab
        tabIds.forEach((tid, idx) => {
            const tab = document.getElementById(tid);
            if (tab) {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTab(idx);
                });
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\siswa\show.blade.php ENDPATH**/ ?>