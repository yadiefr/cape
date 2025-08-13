// PPDB Form Steps JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Form steps functionality
    const form = document.getElementById('ppdb-form');
    const sections = document.querySelectorAll('.ppdb-form-section');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    
    // Initialize
    let currentActive = 1;
    
    // Show only first section on load
    showSection(1);
    
    // Next button event listeners
    nextButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Form validation for current section can be added here
            const sectionNumber = parseInt(this.getAttribute('data-section'));
            const nextSection = sectionNumber + 1;
            
            if (validateSection(sectionNumber)) {
                showSection(nextSection);
                currentActive = nextSection;
                updateStepIndicator(nextSection);
                window.scrollTo(0, 0);
            }
        });
    });
    
    // Previous button event listeners
    prevButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const sectionNumber = parseInt(this.getAttribute('data-section'));
            const prevSection = sectionNumber - 1;
            
            showSection(prevSection);
            currentActive = prevSection;
            updateStepIndicator(prevSection);
            window.scrollTo(0, 0);
        });
    });
    
    // Function to show a specific section
    function showSection(sectionNumber) {
        sections.forEach(section => {
            section.classList.add('d-none');
            const dataSectionNumber = parseInt(section.getAttribute('data-section'));
            if (dataSectionNumber === sectionNumber) {
                section.classList.remove('d-none');
            }
        });
    }
    
    // Function to update the step indicator
    function updateStepIndicator(activeStep) {
        const stepItems = document.querySelectorAll('.ppdb-step-item');
        const stepLines = document.querySelectorAll('.ppdb-step-line');
        
        stepItems.forEach((item, index) => {
            const stepNumber = index + 1;
            if (stepNumber <= activeStep) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
        
        stepLines.forEach((line, index) => {
            const lineNumber = index + 1;
            if (lineNumber < activeStep) {
                line.classList.add('active');
            } else {
                line.classList.remove('active');
            }
        });
    }
    
    // Basic form validation for each section - simplified for testing
    function validateSection(sectionNumber) {
        return true; // Allow all input without validation for testing
    }

    // Listen for input to remove validation errors
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('is-invalid')) {
            e.target.classList.remove('is-invalid');
        }
    });

    // Form submission validation - simplified for testing
    form.addEventListener('submit', function(e) {
        return true; // Allow form submission without validation for testing
    });
});
