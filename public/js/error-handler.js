// Global Error Handler for SMK PGRI CIKAMPEK Website
// Handles various JavaScript errors gracefully

(function() {
    'use strict';

    // Suppress known third-party errors
    window.addEventListener('error', function(event) {
        // Suppress Cloudflare Insights errors (commonly blocked by ad blockers)
        if (event.filename && (
            event.filename.includes('beacon.min.js') || 
            event.filename.includes('cloudflareinsights') ||
            event.filename.includes('gtag') ||
            event.filename.includes('analytics')
        )) {
            console.warn('Third-party analytics script blocked - site functionality not affected');
            event.preventDefault();
            return false;
        }

        // Suppress resource loading errors (fonts, images, etc.)
        if (event.target && (
            event.target.tagName === 'IMG' ||
            event.target.tagName === 'LINK' ||
            event.target.tagName === 'SCRIPT'
        )) {
            console.warn('Resource loading error:', event.target.src || event.target.href);
            event.preventDefault();
            return false;
        }

        // Log other errors for debugging
        if (event.error) {
            console.error('JavaScript Error:', {
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                error: event.error
            });
        }
    });

    // Handle unhandled promise rejections
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && typeof event.reason === 'string') {
            // Suppress known third-party promise rejections
            if (event.reason.includes('beacon.min.js') ||
                event.reason.includes('cloudflareinsights') ||
                event.reason.includes('ERR_BLOCKED_BY_CLIENT') ||
                event.reason.includes('ERR_INTERNET_DISCONNECTED')) {
                console.warn('Third-party service unavailable - site continues normally');
                event.preventDefault();
                return;
            }
        }

        // Log other promise rejections
        console.error('Unhandled Promise Rejection:', event.reason);
    });

    // Basic feature detection and polyfills
    if (!window.console) {
        window.console = {
            log: function() {},
            warn: function() {},
            error: function() {}
        };
    }

    // Ensure DOM is ready
    function domReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }

    // Initialize error handling when DOM is ready
    domReady(function() {
        // Handle broken images gracefully
        var images = document.querySelectorAll('img');
        images.forEach(function(img) {
            img.addEventListener('error', function() {
                if (!this.dataset.errorHandled) {
                    this.dataset.errorHandled = 'true';
                    // You can set a placeholder image here if needed
                    console.warn('Image failed to load:', this.src);
                }
            });
        });

        // Handle missing CSS gracefully
        var links = document.querySelectorAll('link[rel="stylesheet"]');
        links.forEach(function(link) {
            link.addEventListener('error', function() {
                console.warn('CSS file failed to load:', this.href);
            });
        });

        // Handle missing JavaScript gracefully
        var scripts = document.querySelectorAll('script[src]');
        scripts.forEach(function(script) {
            script.addEventListener('error', function() {
                console.warn('JavaScript file failed to load:', this.src);
            });
        });
    });

    // Global notification function for user-facing errors
    window.showNotification = function(message, type) {
        type = type || 'info';
        
        // Create notification element
        var notification = document.createElement('div');
        notification.className = 'error-notification error-notification-' + type;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: #333;
            color: white;
            border-radius: 5px;
            z-index: 10000;
            max-width: 300px;
            word-wrap: break-word;
            animation: slideIn 0.3s ease-out;
        `;

        // Add type-specific styles
        if (type === 'error') {
            notification.style.background = '#dc3545';
        } else if (type === 'success') {
            notification.style.background = '#28a745';
        } else if (type === 'warning') {
            notification.style.background = '#ffc107';
            notification.style.color = '#212529';
        }

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(function() {
            if (notification.parentNode) {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(function() {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
    };

    // Add CSS animations for notifications
    if (!document.getElementById('error-handler-styles')) {
        var style = document.createElement('style');
        style.id = 'error-handler-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    console.log('Global error handler initialized');
})();
