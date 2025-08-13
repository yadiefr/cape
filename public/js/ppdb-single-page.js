// PPDB Single Page Form Validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ppdb-form');
    
    if (form) {
        // Real-time validation for NISN
        const nisnField = document.getElementById('nisn');
        if (nisnField) {
            nisnField.addEventListener('input', function() {
                validateNISN(this);
            });
        }

        // Real-time validation for email
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.addEventListener('input', function() {
                validateEmail(this);
            });
        }

        // Real-time validation for phone numbers
        const phoneFields = document.querySelectorAll('input[type="tel"]');
        phoneFields.forEach(field => {
            field.addEventListener('input', function() {
                validatePhone(this);
            });
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                
                // Submit form
                form.submit();
            }
        });
    }
    
    // Validation functions
    function validateNISN(field) {
        const value = field.value.trim();

        // Required field can't be empty
        if (field.required && value === '') {
            showError(field, 'NISN wajib diisi');
            return false;
        }

        clearError(field);
        return true;
    }
    
    function validateEmail(field) {
        const value = field.value.trim();
        // If field is optional and empty, consider it valid
        if (!field.required && value === '') {
            clearError(field);
            return true;
        }
        
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        
        if (!isValid && value !== '') {
            showError(field, 'Format email tidak valid');
        } else {
            clearError(field);
        }
        
        return isValid;
    }
    
    function validatePhone(field) {
        const value = field.value.trim();
        // If field is optional and empty, consider it valid
        if (!field.required && value === '') {
            clearError(field);
            return true;
        }
        
        const isValid = /^[0-9]{10,13}$/.test(value);
        
        if (!isValid && value !== '') {
            showError(field, 'Nomor telepon harus terdiri dari 10-13 digit angka');
        } else {
            clearError(field);
        }
        
        return isValid;
    }
    
    function validateForm() {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showError(field, 'Field ini wajib diisi');
                isValid = false;
            } else {
                clearError(field);
            }
        });
        
        // Validate specific fields
        if (nisnField && !validateNISN(nisnField)) {
            isValid = false;
        }
        
        if (emailField && emailField.value && !validateEmail(emailField)) {
            isValid = false;
        }
        
        phoneFields.forEach(field => {
            if (field.value && !validatePhone(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    function showError(field, message) {
        field.classList.add('is-invalid');
        field.classList.add('border-red-500');
        
        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback text-red-500 text-sm mt-1';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
    
    function clearError(field) {
        field.classList.remove('is-invalid');
        field.classList.remove('border-red-500');
        
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
});
