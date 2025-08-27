

<?php $__env->startSection('title', 'Cek Status Pendaftaran - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="ppdb-card">
    <div class="ppdb-section-header">
        <h2 class="text-center mb-0">Formulir Cek Status Pendaftaran</h2>
    </div>
            <div class="ppdb-title mt-4">Cek Status Pendaftaran</div>
            <p class="ppdb-subtitle">Masukkan nomor pendaftaran dan NISN untuk melihat status pendaftaran Anda</p>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger mb-4" role="alert">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                        </div>
                        <div>
                            <?php echo e(session('error')); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>            
            
            <form action="<?php echo e(route('pendaftaran.check.status')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label for="nomor_pendaftaran" class="form-label">Nomor Pendaftaran</label>                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color: #4facfe; color: white; width: 45px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-id-badge"></i></span>
                        <input type="text" id="nomor_pendaftaran" name="nomor_pendaftaran" 
                            class="form-control <?php $__errorArgs = ['nomor_pendaftaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            placeholder="Contoh: PPDB-2025-0001" 
                            value="<?php echo e(old('nomor_pendaftaran')); ?>"
                            required>
                        <?php $__errorArgs = ['nomor_pendaftaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>                
                <div class="mb-4">
                    <label for="nisn" class="form-label">NISN</label>                    
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color: #4facfe; color: white; width: 45px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user-circle"></i></span>
                        <input type="text" id="nisn" name="nisn" 
                            class="form-control <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            placeholder="Masukkan 10 digit NISN"
                            value="<?php echo e(old('nisn')); ?>"
                            required
                            pattern="[0-9]{10}"
                            title="NISN harus terdiri dari 10 digit angka">
                        <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>                
                <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                    <button type="submit" class="btn btn-ppdb flex-grow-1">
                        <i class="fas fa-search me-2"></i>Cek Status
                    </button>
                    <a href="<?php echo e(url('/')); ?>" class="btn btn-secondary-ppdb flex-grow-1">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </form>
            
            <!-- Help Section -->
            <div class="help-section mt-4">
                <h3 class="mb-4">Butuh Bantuan?</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="help-item">
                            <div class="help-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="help-text">
                                <p>Jika Anda lupa nomor pendaftaran, silakan hubungi panitia PPDB di:</p>
                                <strong>0812-3456-7890</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="help-item">
                            <div class="help-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="help-text">
                                <p>Atau kirim email ke:</p>
                                <strong>ppdb@smkpgricikampek.sch.id</strong>
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nomorPendaftaran = document.getElementById('nomor_pendaftaran');
    const nisn = document.getElementById('nisn');

    // Format nomor pendaftaran while typing
    nomorPendaftaran.addEventListener('input', function(e) {
        let value = e.target.value;
        
        // Only allow letters, numbers, and dashes
        value = value.replace(/[^0-9A-Za-z-]/g, '');
        
        e.target.value = value;
    });

    // Validate NISN while typing
    nisn.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate nomor pendaftaran format
        if (!nomorPendaftaran.value.match(/^PPDB-\d{4}-\d{4}$/)) {
            showError(nomorPendaftaran, 'Format nomor pendaftaran tidak valid. Gunakan format: PPDB-YYYY-XXXX');
            isValid = false;
        } else {
            clearError(nomorPendaftaran);
        }

        // Validate NISN format
        if (!nisn.value.match(/^[0-9]{10}$/)) {
            showError(nisn, 'NISN harus terdiri dari 10 digit angka');
            isValid = false;
        } else {
            clearError(nisn);
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    function showError(field, message) {
        field.classList.add('is-invalid');
        
        // Find the closest input-group parent
        const inputGroup = field.closest('.input-group');
        
        // Check if there's an existing error message
        let errorDiv = inputGroup.nextElementSibling;
        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block';
            inputGroup.parentNode.insertBefore(errorDiv, inputGroup.nextSibling);
        }
        
        errorDiv.textContent = message;
    }

    function clearError(field) {
        field.classList.remove('is-invalid');
        
        // Find the closest input-group parent
        const inputGroup = field.closest('.input-group');
        
        // Find error message if it exists
        const errorDiv = inputGroup.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app-ppdb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\ppdb\check.blade.php ENDPATH**/ ?>