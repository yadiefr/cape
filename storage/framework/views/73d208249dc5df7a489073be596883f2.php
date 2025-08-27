<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'student',
    'size' => 'md', // sm, md, lg
    'class' => ''
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'student',
    'size' => 'md', // sm, md, lg
    'class' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizeClass = match($size) {
        'sm' => 'h-8 w-8',
        'md' => 'h-10 w-10', 
        'lg' => 'h-12 w-12',
        default => 'h-10 w-10'
    };
?>

<div class="flex-shrink-0 <?php echo e($sizeClass); ?> <?php echo e($class); ?>">
    <div class="<?php echo e($sizeClass); ?> rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200">
        <?php if($student->foto && Storage::disk('public')->exists($student->foto)): ?>
            <img src="<?php echo e(asset('storage/' . $student->foto)); ?>" 
                 alt="Foto <?php echo e($student->nama_lengkap); ?>" 
                 class="h-full w-full object-cover">
        <?php else: ?>
            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($student->nama_lengkap)); ?>&background=3b82f6&color=ffffff" 
                 alt="Foto <?php echo e($student->nama_lengkap); ?>" 
                 class="h-full w-full object-cover">
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\components\student-avatar.blade.php ENDPATH**/ ?>