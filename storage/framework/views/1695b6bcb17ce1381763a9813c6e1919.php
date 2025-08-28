

<?php $__env->startSection('title', 'Detail Jadwal Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye fa-fw"></i> Detail Jadwal Pelajaran
        </h1>
        <div>
            <a href="<?php echo e(route('admin.jadwal.edit', $jadwal->id)); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="btn btn-secondary btn-sm ml-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Main Info -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informasi Jadwal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%"><strong>Kelas:</strong></td>
                                    <td>
                                        <span class="badge badge-primary badge-lg"><?php echo e($jadwal->kelas->nama_kelas); ?></span>
                                        <br><small class="text-muted"><?php echo e($jadwal->kelas->jurusan->nama_jurusan ?? ''); ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Mata Pelajaran:</strong></td>
                                    <td>
                                        <span class="font-weight-bold text-primary"><?php echo e($jadwal->mataPelajaran->nama_pelajaran); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Guru:</strong></td>
                                    <td>
                                        <span class="font-weight-bold"><?php echo e($jadwal->guru->nama); ?></span>
                                        <?php if($jadwal->guru->nip): ?>
                                            <br><small class="text-muted">NIP: <?php echo e($jadwal->guru->nip); ?></small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Hari:</strong></td>
                                    <td>
                                        <span class="badge badge-info badge-lg"><?php echo e($jadwal->hari_formatted); ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%"><strong>Jam Ke:</strong></td>
                                    <td>
                                        <span class="badge badge-secondary badge-lg"><?php echo e($jadwal->jam_ke); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu:</strong></td>
                                    <td>
                                        <span class="font-weight-bold text-dark"><?php echo e($jadwal->jam_format); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ruang Kelas:</strong></td>
                                    <td>
                                        <?php if($jadwal->ruang_kelas): ?>
                                            <span class="badge badge-outline-secondary"><?php echo e($jadwal->ruang_kelas); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if($jadwal->is_active): ?>
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check"></i> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary badge-lg">
                                                <i class="fas fa-times"></i> Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($jadwal->keterangan): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h6 class="font-weight-bold">Keterangan:</h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> <?php echo e($jadwal->keterangan); ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-plus"></i> 
                                        <strong>Dibuat:</strong> <?php echo e($jadwal->created_at->format('d F Y, H:i')); ?> WIB
                                    </small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        <i class="fas fa-edit"></i> 
                                        <strong>Terakhir diubah:</strong> <?php echo e($jadwal->updated_at->format('d F Y, H:i')); ?> WIB
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i> Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?php echo e(route('admin.jadwal.edit', $jadwal->id)); ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Jadwal
                            </a>
                            
                            <?php if($jadwal->is_active): ?>
                                <form method="POST" action="<?php echo e(route('admin.jadwal.update', $jadwal->id)); ?>" class="d-inline ml-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="hidden" name="kelas_id" value="<?php echo e($jadwal->kelas_id); ?>">
                                    <input type="hidden" name="mata_pelajaran_id" value="<?php echo e($jadwal->mata_pelajaran_id); ?>">
                                    <input type="hidden" name="guru_id" value="<?php echo e($jadwal->guru_id); ?>">
                                    <input type="hidden" name="hari" value="<?php echo e($jadwal->hari); ?>">
                                    <input type="hidden" name="jam_ke" value="<?php echo e($jadwal->jam_ke); ?>">
                                    <input type="hidden" name="jam_mulai" value="<?php echo e(\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')); ?>">
                                    <input type="hidden" name="jam_selesai" value="<?php echo e(\Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')); ?>">
                                    <input type="hidden" name="ruang_kelas" value="<?php echo e($jadwal->ruang_kelas); ?>">
                                    <input type="hidden" name="keterangan" value="<?php echo e($jadwal->keterangan); ?>">
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Nonaktifkan jadwal ini?')">
                                        <i class="fas fa-pause"></i> Nonaktifkan
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('admin.jadwal.update', $jadwal->id)); ?>" class="d-inline ml-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="is_active" value="1">
                                    <input type="hidden" name="kelas_id" value="<?php echo e($jadwal->kelas_id); ?>">
                                    <input type="hidden" name="mata_pelajaran_id" value="<?php echo e($jadwal->mata_pelajaran_id); ?>">
                                    <input type="hidden" name="guru_id" value="<?php echo e($jadwal->guru_id); ?>">
                                    <input type="hidden" name="hari" value="<?php echo e($jadwal->hari); ?>">
                                    <input type="hidden" name="jam_ke" value="<?php echo e($jadwal->jam_ke); ?>">
                                    <input type="hidden" name="jam_mulai" value="<?php echo e(\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')); ?>">
                                    <input type="hidden" name="jam_selesai" value="<?php echo e(\Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')); ?>">
                                    <input type="hidden" name="ruang_kelas" value="<?php echo e($jadwal->ruang_kelas); ?>">
                                    <input type="hidden" name="keterangan" value="<?php echo e($jadwal->keterangan); ?>">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Aktifkan jadwal ini?')">
                                        <i class="fas fa-play"></i> Aktifkan
                                    </button>
                                </form>
                            <?php endif; ?>

                            <div class="btn-group ml-2" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-share"></i> Lainnya
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?php echo e(route('admin.jadwal.index', ['kelas_id' => $jadwal->kelas_id])); ?>">
                                        <i class="fas fa-list"></i> Lihat Jadwal Kelas Ini
                                    </a>
                                    <a class="dropdown-item" href="<?php echo e(route('admin.jadwal.index', ['hari' => $jadwal->hari])); ?>">
                                        <i class="fas fa-calendar-day"></i> Lihat Jadwal Hari <?php echo e($jadwal->hari_formatted); ?>

                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo e(route('admin.jadwal.create', ['kelas_id' => $jadwal->kelas_id, 'hari' => $jadwal->hari])); ?>">
                                        <i class="fas fa-plus"></i> Tambah Jadwal Serupa
                                    </a>
                                </div>
                            </div>

                            <form method="POST" action="<?php echo e(route('admin.jadwal.destroy', $jadwal->id)); ?>" class="d-inline float-right">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.')">
                                    <i class="fas fa-trash"></i> Hapus Jadwal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-clock"></i> Quick Info
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <h2 class="text-primary"><?php echo e($jadwal->hari_formatted); ?></h2>
                        <h4 class="text-secondary">Jam ke-<?php echo e($jadwal->jam_ke); ?></h4>
                        <h5 class="text-dark"><?php echo e($jadwal->jam_format); ?></h5>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="card border-left-primary">
                                <div class="card-body py-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Kelas</div>
                                    <div class="text-sm font-weight-bold"><?php echo e($jadwal->kelas->nama_kelas); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-left-success">
                                <div class="card-body py-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ruang</div>
                                    <div class="text-sm font-weight-bold"><?php echo e($jadwal->ruang_kelas ?? '-'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Schedules -->
            <?php
                $relatedSchedules = \App\Models\JadwalPelajaran::where('kelas_id', $jadwal->kelas_id)
                    ->where('hari', $jadwal->hari)
                    ->where('id', '!=', $jadwal->id)
                    ->with(['mataPelajaran', 'guru'])
                    ->orderBy('jam_ke')
                    ->take(5)
                    ->get();
            ?>

            <?php if($relatedSchedules->count() > 0): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-list"></i> Jadwal Lain (<?php echo e($jadwal->hari_formatted); ?>)
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $relatedSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                <div class="mr-3">
                                    <span class="badge badge-secondary"><?php echo e($related->jam_ke); ?></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-sm font-weight-bold"><?php echo e($related->mataPelajaran->nama_pelajaran); ?></div>
                                    <div class="text-xs text-muted"><?php echo e($related->guru->nama); ?></div>
                                    <div class="text-xs text-muted"><?php echo e($related->jam_format); ?></div>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('admin.jadwal.show', $related->id)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-center mt-2">
                            <a href="<?php echo e(route('admin.jadwal.index', ['kelas_id' => $jadwal->kelas_id, 'hari' => $jadwal->hari])); ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-list"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Navigation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-compass"></i> Navigasi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-alt"></i> Semua Jadwal
                        </a>
                        <a href="<?php echo e(route('admin.jadwal.create')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus"></i> Tambah Jadwal Baru
                        </a>
                        <a href="<?php echo e(route('admin.settings.jadwal.index')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog"></i> Pengaturan Jadwal
                        </a>
                        <a href="<?php echo e(route('admin.kelas.index')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-school"></i> Manajemen Kelas
                        </a>
                        <a href="<?php echo e(route('admin.guru.index')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-chalkboard-teacher"></i> Manajemen Guru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.badge-lg {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.card.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.card.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.list-group-item-action {
    padding: 0.75rem 1rem;
}

.list-group-item-action:hover {
    background-color: #f8f9fc;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\jadwal\show.blade.php ENDPATH**/ ?>