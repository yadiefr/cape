// Newsletter Popup Functionality
document.addEventListener('DOMContentLoaded', function() {
    const newsletterPopup = document.getElementById('newsletterPopup');
    const closePopup = document.getElementById('newsletterClose');
    const newsletterForm = document.getElementById('newsletterForm');
    const emailInput = document.getElementById('newsletterEmail');
    
    // Function to show popup
    function showNewsletterPopup() {
        // Only show if user hasn't closed it before or subscribed
        if (!localStorage.getItem('newsletter_closed') && !localStorage.getItem('newsletter_subscribed')) {
            newsletterPopup.classList.add('show');
        }
    }
    
    // Close popup when clicking close button
    if(closePopup) {
        closePopup.addEventListener('click', function() {
            newsletterPopup.classList.remove('show');
            // Save that user has closed the popup (don't show again in this session)
            localStorage.setItem('newsletter_closed', 'true');
            
            // Clear after 7 days
            setTimeout(() => {
                localStorage.removeItem('newsletter_closed');
            }, 7 * 24 * 60 * 60 * 1000);
        });
    }
      // Handle form submission
    if(newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if(emailInput.value.trim() === '') {
                showFormError('Silakan masukkan alamat email Anda');
                return;
            }
            
            if(!isValidEmail(emailInput.value)) {
                showFormError('Silakan masukkan alamat email yang valid');
                return;
            }
            
            // Hide form and show success message
            newsletterForm.style.display = 'none';
            document.getElementById('newsletterSuccess').style.display = 'block';
            
            // Mark as subscribed in local storage
            localStorage.setItem('newsletter_subscribed', 'true');
            
            // Close popup after 3 seconds
            setTimeout(() => {
                newsletterPopup.classList.remove('show');
            }, 3000);
        });
    }
      // Show form error message
    function showFormError(message) {
        // Create error element if it doesn't exist
        let errorEl = document.querySelector('.newsletter-error');
        if(!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'newsletter-error';
            errorEl.style.color = '#e53e3e';
            errorEl.style.fontSize = '14px';
            errorEl.style.marginTop = '10px';
            errorEl.style.marginBottom = '10px';
            newsletterForm.insertBefore(errorEl, document.querySelector('.newsletter-submit').parentNode);
        }
        
        errorEl.textContent = message;
        errorEl.style.display = 'block';
        
        // Hide after 3 seconds
        setTimeout(() => {
            errorEl.style.display = 'none';
        }, 3000);
    }
    
    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Show popup after 15 seconds of page visit
    setTimeout(showNewsletterPopup, 15000);
    
    // Exit intent detection (show popup when user is about to leave)
    document.addEventListener('mouseleave', function(e) {
        // If mouse leaves to the top of the page
        if(e.clientY < 10) {
            showNewsletterPopup();
        }
    });
});
