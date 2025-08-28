

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/ppdb-single-page.css')); ?>">
<style>
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #ede9fe 100%);
        min-height: 100vh;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->yieldContent('pendaftaran-content'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\ppdb\layout.blade.php ENDPATH**/ ?>