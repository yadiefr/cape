

<?php $__env->startSection('title', 'Edit Berita'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Berita</h1>
    <form action="<?php echo e(route('admin.berita.update', $berita->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Judul Berita</label>
            <input type="text" name="judul" value="<?php echo e(old('judul', $berita->judul)); ?>" class="form-input w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Isi Berita</label>
            <textarea name="isi" rows="6" class="form-input w-full" required><?php echo e(old('isi', $berita->isi)); ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Lampiran (opsional)</label>
            <?php if($berita->lampiran): ?>
                <div class="mb-2">
                    <a href="<?php echo e(asset('storage/' . $berita->lampiran)); ?>" target="_blank" class="text-blue-600 underline">Lihat Lampiran Saat Ini</a>
                </div>
            <?php endif; ?>
            <input type="file" name="lampiran" class="form-input w-full">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Simpan Perubahan</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\berita\edit.blade.php ENDPATH**/ ?>