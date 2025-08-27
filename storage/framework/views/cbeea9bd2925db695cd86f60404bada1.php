<?php $__env->startSection('title', 'Detail Admin User'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2 text-primary"></i>
                Detail Admin User
            </h1>
            <p class="text-muted mb-0">Informasi lengkap <?php echo e($adminUser->name); ?></p>
        </div>
        <div class="btn-group">
            <a href="<?php echo e(route('admin.admin-users.edit', $adminUser)); ?>" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="<?php echo e(route('admin.admin-users.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="<?php echo e($adminUser->photo_url); ?>" alt="<?php echo e($adminUser->name); ?>" 
                         class="rounded-circle mb-3" width="120" height="120">
                    <h4 class="mb-1"><?php echo e($adminUser->name); ?></h4>
                    <p class="text-muted mb-2"><?php echo e($adminUser->email); ?></p>
                    <span class="badge bg-primary fs-6 mb-3"><?php echo e($adminUser->role_display); ?></span>
                    <br>
                    <span class="badge bg-<?php echo e($adminUser->status == 'aktif' ? 'success' : 'danger'); ?> fs-6">
                        <?php echo e(ucfirst($adminUser->status)); ?>

                    </span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.admin-users.edit', $adminUser)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        
                        <form action="<?php echo e(route('admin.admin-users.reset-password', $adminUser)); ?>" method="POST" 
                              onsubmit="return confirm('Reset password ke default?')">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>
                        </form>
                        
                        <?php if($adminUser->id !== auth()->id()): ?>
                        <button type="button" class="btn btn-<?php echo e($adminUser->status == 'aktif' ? 'danger' : 'success'); ?>" 
                                onclick="toggleStatus(<?php echo e($adminUser->id); ?>)">
                            <i class="fas fa-toggle-<?php echo e($adminUser->status == 'aktif' ? 'off' : 'on'); ?> me-2"></i>
                            <?php echo e($adminUser->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan'); ?>

                        </button>
                        
                        <button type="button" class="btn btn-danger" onclick="deleteUser(<?php echo e($adminUser->id); ?>)">
                            <i class="fas fa-trash me-2"></i>Hapus User
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle me-2"></i>Informasi Personal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->name); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->email); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIP</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->nip ?? '-'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">No. Telepon</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->phone ?? '-'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <p class="form-control-plaintext">
                                    <?php echo e($adminUser->birth_date ? $adminUser->birth_date->format('d F Y') : '-'); ?>

                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <p class="form-control-plaintext">
                                    <?php if($adminUser->gender == 'L'): ?>
                                        Laki-laki
                                    <?php elseif($adminUser->gender == 'P'): ?>
                                        Perempuan
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->address ?? '-'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>Informasi Sistem
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6"><?php echo e($adminUser->role_display); ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-<?php echo e($adminUser->status == 'aktif' ? 'success' : 'danger'); ?> fs-6">
                                        <?php echo e(ucfirst($adminUser->status)); ?>

                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Dibuat</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->created_at->format('d F Y, H:i')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terakhir Diupdate</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->updated_at->format('d F Y, H:i')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Login</label>
                                <p class="form-control-plaintext">
                                    <?php if($adminUser->last_login_at): ?>
                                        <?php echo e($adminUser->last_login_at->format('d F Y, H:i')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Belum pernah login</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Login IP</label>
                                <p class="form-control-plaintext"><?php echo e($adminUser->last_login_ip ?? '-'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt me-2"></i>Permissions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                            $permissions = $adminUser->permissions;
                        ?>
                        <?php if(in_array('*', $permissions)): ?>
                            <div class="col-12">
                                <span class="badge bg-danger fs-6">FULL ACCESS - Semua Permission</span>
                            </div>
                        <?php else: ?>
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-info"><?php echo e($permission); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus admin user <strong><?php echo e($adminUser->name); ?></strong>?
                <br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleStatus(userId) {
    if (confirm('Ubah status user ini?')) {
        fetch(`<?php echo e(url('admin/admin-users')); ?>/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function deleteUser(userId) {
    const form = document.getElementById('deleteForm');
    form.action = `<?php echo e(url('admin/admin-users')); ?>/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\admin-users\show.blade.php ENDPATH**/ ?>