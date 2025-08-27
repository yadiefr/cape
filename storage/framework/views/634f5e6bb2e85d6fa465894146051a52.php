

<?php $__env->startSection('title', 'Rekap Absensi'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="px-3 py-4">
    <div class="bg-white rounded-lg shadow-lg p-3 sm:p-6">
        <div class="mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Rekap Absensi</h1>
            <p class="text-sm sm:text-base text-gray-600">Lihat rekapitulasi kehadiran siswa per kelas dan periode</p>
        </div>

        <div class="mb-6 sm:mb-8">
            <form action="<?php echo e(route('guru.absensi.rekap')); ?>" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 form-grid-mobile">
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm sm:text-base" required>
                            <option value="">Pilih Kelas</option>
                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>>
                                <?php echo e($k->nama_kelas); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm sm:text-base" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            <?php $__currentLoopData = $mapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m->id); ?>" <?php echo e(request('mapel_id') == $m->id ? 'selected' : ''); ?>>
                                <?php echo e($m->nama); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm sm:text-base"
                               value="<?php echo e(request('tanggal_awal', date('Y-m-d', strtotime('-1 month')))); ?>" required>
                    </div>
                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm sm:text-base"
                               value="<?php echo e(request('tanggal_akhir', date('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 rounded-lg transition-colors btn-mobile-full text-sm sm:text-base">
                        Tampilkan Data
                    </button>
                </div>
            </form>
        </div>

        <?php if(isset($siswa) && isset($periode) && isset($dataAbsensi) && count($periode) > 0): ?>
        <div class="mt-6 sm:mt-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                <h2 class="text-base sm:text-lg font-semibold">Rekap Absensi <?php echo e($kelas->where('id', request('kelas_id'))->first()->nama_kelas ?? 'Kelas yang Dipilih'); ?></h2>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="<?php echo e(route('guru.absensi.rekap.export', request()->query())); ?>" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center text-sm btn-mobile-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-mobile-sm">Export Excel</span>
                    </a>
                    <a href="<?php echo e(route('guru.absensi.rekap.cetak', request()->query())); ?>" target="_blank" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center text-sm btn-mobile-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-mobile-sm">Cetak Rekap</span>
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs sm:text-sm text-blue-700">
                                Menampilkan <strong><?php echo e(count($periode)); ?> hari</strong> absensi yang tercatat dalam periode <?php echo e(request('tanggal_awal') ? \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') : ''); ?> - <?php echo e(request('tanggal_akhir') ? \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') : ''); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <?php $__currentLoopData = $periode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th scope="col" class="px-2 sm:px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(date('d/m', strtotime($tgl))); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">H</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">I</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">S</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">A</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($i % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900"><?php echo e($i + 1); ?></td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900"><?php echo e($s->nis); ?></td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900"><?php echo e($s->nama_lengkap); ?></td>
                            
                            <?php $__currentLoopData = $periode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="px-2 sm:px-4 py-4 whitespace-nowrap text-center">
                                <?php if(isset($dataAbsensi[$s->id][$tgl])): ?>
                                    <?php if($dataAbsensi[$s->id][$tgl] == 'hadir'): ?>
                                        <span class="px-1.5 sm:px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">H</span>
                                    <?php elseif($dataAbsensi[$s->id][$tgl] == 'izin'): ?>
                                        <span class="px-1.5 sm:px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">I</span>
                                    <?php elseif($dataAbsensi[$s->id][$tgl] == 'sakit'): ?>
                                        <span class="px-1.5 sm:px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">S</span>
                                    <?php elseif($dataAbsensi[$s->id][$tgl] == 'alpha'): ?>
                                        <span class="px-1.5 sm:px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">A</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center text-xs sm:text-sm text-gray-900 font-medium">
                                <?php echo e(isset($rekapData[$s->id]['hadir']) ? $rekapData[$s->id]['hadir'] : 0); ?>

                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center text-xs sm:text-sm text-gray-900 font-medium">
                                <?php echo e(isset($rekapData[$s->id]['izin']) ? $rekapData[$s->id]['izin'] : 0); ?>

                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center text-xs sm:text-sm text-gray-900 font-medium">
                                <?php echo e(isset($rekapData[$s->id]['sakit']) ? $rekapData[$s->id]['sakit'] : 0); ?>

                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center text-xs sm:text-sm text-gray-900 font-medium">
                                <?php echo e(isset($rekapData[$s->id]['alpha']) ? $rekapData[$s->id]['alpha'] : 0); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php elseif(request()->has('kelas_id')): ?>
        <div class="mt-4 sm:mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-3 sm:p-4 card-mobile-spacing">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm text-yellow-700">
                        Tidak ada data absensi yang tercatat untuk periode yang dipilih. Pastikan guru sudah melakukan input absensi untuk kelas dan mata pelajaran ini.
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\absensi\rekap.blade.php ENDPATH**/ ?>