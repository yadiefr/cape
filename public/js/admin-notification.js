/**
 * Global Notification Component
 * Menampilkan notifikasi di panel admin
 */
class AdminNotification {
    constructor() {
        this.container = null;
        this.timeout = null;
        this.init();
    }

    init() {
        // Create container if it doesn't exist
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'fixed top-20 right-4 z-50 flex flex-col space-y-4 max-w-sm w-full';
            document.body.appendChild(this.container);
        }
    }

    show(message, type = 'info', duration = 5000) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `transform translate-x-full opacity-0 transition-all duration-300 ease-out`;
        
        // Set notification type
        let bgColor, borderColor, textColor, icon;
        switch (type) {
            case 'success':
                bgColor = 'bg-green-100';
                borderColor = 'border-green-500';
                textColor = 'text-green-700';
                icon = 'fas fa-check-circle';
                break;
            case 'error':
                bgColor = 'bg-red-100';
                borderColor = 'border-red-500';
                textColor = 'text-red-700';
                icon = 'fas fa-exclamation-circle';
                break;
            case 'warning':
                bgColor = 'bg-yellow-100';
                borderColor = 'border-yellow-500';
                textColor = 'text-yellow-700';
                icon = 'fas fa-exclamation-triangle';
                break;
            default:
                bgColor = 'bg-blue-100';
                borderColor = 'border-blue-500';
                textColor = 'text-blue-700';
                icon = 'fas fa-info-circle';
        }

        notification.innerHTML = `
            <div class="${bgColor} border-l-4 ${borderColor} ${textColor} p-4 rounded-md shadow-lg flex items-center">
                <div class="py-1"><i class="${icon} mr-2"></i></div>
                <div class="flex-1">${message}</div>
                <button class="ml-3 text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Add to container
        this.container.appendChild(notification);

        // Add close button functionality
        const closeBtn = notification.querySelector('button');
        closeBtn.addEventListener('click', () => {
            this.hide(notification);
        });

        // Animate in
        setTimeout(() => {
            notification.className = `transform translate-x-0 opacity-100 transition-all duration-300 ease-out`;
        }, 10);

        // Auto-hide after duration
        if (duration > 0) {
            setTimeout(() => {
                this.hide(notification);
            }, duration);
        }

        return notification;
    }

    hide(notification) {
        // Animate out
        notification.className = `transform translate-x-full opacity-0 transition-all duration-300 ease-out`;
        
        // Remove after animation
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
}

// Initialize global notification
window.adminNotification = new AdminNotification();

// Global function to show notifications
window.showAdminNotification = (message, type, duration) => {
    return window.adminNotification.show(message, type, duration);
};
