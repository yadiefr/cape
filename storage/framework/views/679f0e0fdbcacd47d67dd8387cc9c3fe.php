

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">Notifikasi</h6>
                        </div>
                        <div class="col text-end">
                            <form action="<?php echo e(route('notifications.markAllAsRead')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Tandai Semua Dibaca
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="notification-item p-3 border-bottom <?php echo e($notification->is_read ? 'bg-light' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 <?php echo e($notification->is_read ? 'text-muted' : ''); ?>">
                                        <?php echo e($notification->message); ?>

                                    </p>
                                    <small class="text-muted">
                                        <?php echo e($notification->created_at->diffForHumans()); ?>

                                    </small>
                                </div>
                                <div class="d-flex">
                                    <?php if(!$notification->is_read): ?>
                                        <form action="<?php echo e(route('notifications.markAsRead', $notification->id)); ?>" 
                                              method="POST" 
                                              class="me-2">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Tandai Dibaca
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('notifications.destroy', $notification->id)); ?>" 
                                          method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada notifikasi</p>
                        </div>
                    <?php endif; ?>

                    <?php if($notifications->hasPages()): ?>
                        <div class="mt-4">
                            <?php echo e($notifications->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\notifications\index.blade.php ENDPATH**/ ?>