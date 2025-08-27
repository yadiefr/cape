/**
 * Admin SPA Navigation
 * Implementasi navigasi Single Page Application (SPA) untuk halaman admin
 * Membuat navigasi tanpa refresh halaman saat mengklik menu sidebar
 * FITUR SPA DINONAKTIFKAN UNTUK MOBILE DEVICES
 */

// Cache halaman yang sudah dimuat
const pageCache = {};

// State navigasi
let isNavigating = false;
let currentUrl = window.location.href;

// Element utama
let mainContentContainer = null;
let loadingIndicator = null;

// Deteksi mobile device
function isMobileDevice() {
    // Force desktop mode jika diaktifkan
    if (window.MOBILE_DETECTION_CONFIG && window.MOBILE_DETECTION_CONFIG.forceDesktopMode) {
        return false;
    }
    
    // Deteksi mobile yang lebih akurat dengan multiple checks
    const screenWidth = window.screen.width;
    const windowWidth = window.innerWidth;
    const userAgent = navigator.userAgent.toLowerCase();
    const config = window.MOBILE_DETECTION_CONFIG || {};
    
    // Check User Agent untuk device mobile asli
    const isMobileUA = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent);
    
    // Check apakah ada touch support
    const isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);
    
    // Check screen orientation support (mobile feature)
    const hasOrientationSupport = screen.orientation !== undefined || screen.mozOrientation !== undefined || screen.msOrientation !== undefined;
    
    // Deteksi Developer Tools (sering menyebabkan false positive mobile)
    const isDevToolsOpen = (windowWidth < screenWidth * 0.8) || (window.outerHeight - window.innerHeight > 100);
    
    // Check untuk tablet dalam mode landscape yang lebar
    const isTabletLandscape = isTouchDevice && windowWidth >= 768 && windowWidth < (config.minDesktopWidth || 1024);
    
    // Check untuk desktop kecil atau browser developer tools
    const isDesktopSmall = windowWidth < (config.minDesktopWidth || 1024) && screenWidth >= (config.minScreenWidth || 1366);
    
    // Final decision dengan prioritas:
    // 1. Jika screen width desktop (>= 1366) = DESKTOP
    // 2. Jika user agent desktop dan tidak ada orientasi support = DESKTOP  
    // 3. Jika window kecil tapi screen besar = DESKTOP (dev tools)
    // 4. Jika dev tools terbuka = DESKTOP
    // 5. Sisanya = MOBILE
    
    let finalDecision;
    if (screenWidth >= (config.minScreenWidth || 1366)) {
        finalDecision = false; // Desktop dengan screen besar
    } else if (!isMobileUA && !hasOrientationSupport && screenWidth >= (config.minDesktopWidth || 1024)) {
        finalDecision = false; // Desktop dengan screen medium
    } else if (isDesktopSmall || isDevToolsOpen) {
        finalDecision = false; // Desktop dengan browser window kecil atau dev tools open
    } else if (isTabletLandscape) {
        finalDecision = false; // Tablet landscape diperlakukan sebagai desktop
    } else {
        finalDecision = isMobileUA || (windowWidth < 768 && isTouchDevice);
    }
    
    // Debug info (only in development or when enabled)
    const shouldShowDebug = (window.location.hostname.includes('localhost') ||
                           window.location.hostname.includes('127.0.0.1') ||
                           config.enableDebugMode);

    // Disable debug logging to prevent console spam
    // if (shouldShowDebug) {
    //     console.log('🔍 Mobile Detection Debug:', {
    //         screenWidth,
    //         windowWidth,
    //         isMobileUA,
    //         isTouchDevice,
    //         hasOrientationSupport,
    //         isTabletLandscape,
    //         isDesktopSmall,
    //         isDevToolsOpen,
    //         hostname: window.location.hostname,
    //         userAgent: userAgent.substr(0, 100) + '...',
    //         finalDecision,
    //         config,
    //         recommendation: finalDecision ? 'MOBILE MODE' : 'DESKTOP MODE'
    //     });
    // }
    
    return finalDecision;
}

// Global flag untuk force disable SPA pada mobile
window.FORCE_DISABLE_SPA_ON_MOBILE = true; // Enabled - SPA disabled on mobile for better performance

// Configuration untuk mobile detection
window.MOBILE_DETECTION_CONFIG = {
    forceDesktopMode: false, // Set true untuk force desktop mode
    enableDebugMode: false,  // Set true untuk enable debug logging
    minDesktopWidth: 1024,   // Minimum width untuk desktop
    minScreenWidth: 1366     // Minimum screen width untuk desktop detection
};

// Inisialisasi setelah DOM ready
document.addEventListener('DOMContentLoaded', function() {
    
    // Expose control functions ke window untuk debugging
    window.mobileDetectionControls = {
        forceDesktop: function() {
            window.MOBILE_DETECTION_CONFIG.forceDesktopMode = true;
            // console.log('✅ Desktop mode dipaksa aktif. Refresh halaman untuk melihat perubahan.');
        },
        enableMobileDetection: function() {
            window.MOBILE_DETECTION_CONFIG.forceDesktopMode = false;
            // console.log('✅ Mobile detection diaktifkan kembali. Refresh halaman untuk melihat perubahan.');
        },
        enableDebug: function() {
            window.MOBILE_DETECTION_CONFIG.enableDebugMode = true;
            // console.log('✅ Debug mode diaktifkan. Refresh halaman untuk melihat debug info.');
        },
        disableDebug: function() {
            window.MOBILE_DETECTION_CONFIG.enableDebugMode = false;
            // console.log('✅ Debug mode dinonaktifkan.');
        },
        checkDevice: function() {
            const isMobile = isMobileDevice();
            // console.log('📱 Current device detection:', isMobile ? 'MOBILE' : 'DESKTOP');
            return isMobile;
        },
        showHelp: function() {
            // console.log(`
            // 🔧 Mobile Detection Controls:
            // - mobileDetectionControls.forceDesktop()     : Paksa mode desktop
            // - mobileDetectionControls.enableMobileDetection() : Aktifkan deteksi mobile
            // - mobileDetectionControls.enableDebug()      : Aktifkan debug mode
            // - mobileDetectionControls.disableDebug()     : Matikan debug mode
            // - mobileDetectionControls.checkDevice()      : Cek deteksi device saat ini
            // - mobileDetectionControls.showHelp()         : Tampilkan bantuan ini
            // `);
        }
    };
    
    // Skip SPA initialization untuk mobile devices
    if (window.FORCE_DISABLE_SPA_ON_MOBILE && isMobileDevice()) {
        // console.log('🚫 SPA dinonaktifkan untuk mobile device');
        // console.log('💡 Gunakan mobileDetectionControls.forceDesktop() untuk memaksa mode desktop');
        return;
    }
    
    // Tangkap container konten utama
    mainContentContainer = document.querySelector('.main-content-container');
    
    // Buat loading indicator
    createLoadingIndicator();
    
    // Tangkap semua link navigasi sidebar
    initSidebarLinks();
    
    // Inisialisasi history API
    initHistoryApi();
});

// Buat loading indicator
function createLoadingIndicator() {
    loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'fixed top-0 left-0 h-1 bg-blue-600 z-50 transition-all duration-300 w-0';
    loadingIndicator.style.boxShadow = '0 2px 10px rgba(59, 130, 246, 0.5)';
    document.body.appendChild(loadingIndicator);
}

// Tangkap semua link navigasi sidebar
function initSidebarLinks() {
    // Skip untuk mobile devices
    if (window.FORCE_DISABLE_SPA_ON_MOBILE && isMobileDevice()) {
        return;
    }
    
    const sidebarLinks = document.querySelectorAll('aside a[href]');
    
    sidebarLinks.forEach(link => {
        // Skip if already processed or external link
        if (link.getAttribute('data-spa-link') === 'true' || isExternalLink(link.href)) {
            return;
        }
        
        // Skip links that should not use SPA
        if (link.getAttribute('data-no-spa') === 'true') {
            return;
        }
        
        // Mark as processed
        link.setAttribute('data-spa-link', 'true');
        
        // Add click event listener
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.href;
            
            // Skip if already on this page
            if (url === currentUrl) {
                return;
            }
            
            // Navigate to the page
            navigateTo(url);
            
            // Update active state in sidebar (will be handled by CSS)
            updateActiveSidebarItem(url);
            
            // On mobile, close sidebar after navigation
            if (window.innerWidth < 1024) {
                const toggleBtn = document.querySelector('[x-data] button[x-ref="sidebarToggle"]');
                if (toggleBtn) {
                    toggleBtn.click();
                }
            }
        });
    });
}

// Initialize History API
function initHistoryApi() {
    // Skip untuk mobile devices
    if (window.FORCE_DISABLE_SPA_ON_MOBILE && isMobileDevice()) {
        return;
    }
    
    // Handle browser back/forward navigation
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.url) {
            navigateTo(event.state.url, false); // Don't push state again
            updateActiveSidebarItem(event.state.url);
        }
    });
    
    // Initialize current state
    window.history.replaceState({ url: window.location.href }, '', window.location.href);
}

// Monitor window resize untuk disable SPA jika menjadi mobile
window.addEventListener('resize', function() {
    if (window.FORCE_DISABLE_SPA_ON_MOBILE && isMobileDevice()) {
        // Jika resize ke mobile, nonaktifkan event listeners SPA
        const spaLinks = document.querySelectorAll('aside a[data-spa-link="true"]');
        spaLinks.forEach(link => {
            // Remove SPA marker sehingga link bekerja normal
            link.removeAttribute('data-spa-link');
        });
        // console.log('SPA dinonaktifkan karena resize ke mobile viewport');
    }
});

// Navigate to URL
async function navigateTo(url, pushState = true) {
    // Skip untuk mobile devices - lakukan navigasi normal
    if (window.FORCE_DISABLE_SPA_ON_MOBILE && isMobileDevice()) {
        // console.log('SPA navigasi dinonaktifkan untuk mobile, melakukan navigasi normal ke:', url);
        window.location.href = url;
        return;
    }
    
    if (isNavigating) return;
    
    // Check if we're navigating to ujian section from admin layout or vice versa
    if (isLayoutChangeNeeded(url)) {
        // If navigation requires layout change, perform hard refresh
        window.location.href = url;
        return;
    }
    
    // Set navigating flag
    isNavigating = true;
    
    // Show loading indicator
    showLoading();
    
    try {
        let content;
        
        // Check if page is cached
        if (pageCache[url]) {
            content = pageCache[url];
        } else {
            // Fetch page content
            content = await fetchPageContent(url);
            
            // Cache the content
            pageCache[url] = content;
        }
        
        // Update content
        updateContent(content);
        
        // Update current URL
        currentUrl = url;
        
        // Update browser history
        if (pushState) {
            window.history.pushState({ url }, '', url);
        }
        
        // Update title
        updateDocumentTitle(content);
        
        // Run scripts in the new content
        executeScripts(content);
        
    } catch (error) {
        // console.error('Navigation error:', error);
        showErrorMessage('Gagal memuat halaman. Silakan coba lagi.');
        
        // Optional: Hard redirect on error
        // window.location.href = url;
    } finally {
        // Hide loading indicator
        hideLoading();
        
        // Reset navigating flag
        isNavigating = false;
    }
}

// Fetch page content from server
async function fetchPageContent(url) {
    const response = await fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html, */*'
        }
    });
    
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    return await response.text();
}

// Update main content with new HTML
function updateContent(html) {
    // Extract only the content part
    const contentMatch = html.match(/<div[^>]*class="[^"]*main-content-container[^"]*"[^>]*>([\s\S]*?)<\/div>\s*<\/main>/i);
    
    if (contentMatch && contentMatch[1]) {
        const contentHtml = contentMatch[1];
        mainContentContainer.innerHTML = contentHtml;
    } else {
        // Fallback - use the whole response
        mainContentContainer.innerHTML = html;
    }
    
    // Scroll to top
    window.scrollTo(0, 0);
}

// Execute scripts in the new content
function executeScripts(html) {
    // Extract script tags
    const scriptRegex = /<script(?:\s[^>]*)?>([^<]*(?:<(?!\/script>)[^<]*)*)<\/script>/gi;
    let match;
    
    while ((match = scriptRegex.exec(html)) !== null) {
        // Get script content
        const scriptContent = match[1];
        
        // Skip empty scripts
        if (!scriptContent.trim()) continue;
        
        // Execute the script
        try {
            eval(scriptContent);
        } catch (error) {
            // console.error('Error executing script:', error);
        }
    }
    
    // Re-initialize third-party libraries and plugins
    reinitializePlugins();
}

// Reinitialize plugins and libraries
function reinitializePlugins() {
    // Alpine.js - reinitialize if needed
    if (window.Alpine) {
        document.querySelectorAll('[x-data]').forEach(el => {
            if (!el._x_dataStack) {
                // Alpine.js will initialize this automatically
            }
        });
    }
    
    // Re-init other plugins here if needed
}

// Update document title based on new page
function updateDocumentTitle(html) {
    const titleMatch = html.match(/<title>([^<]*)<\/title>/i);
    
    if (titleMatch && titleMatch[1]) {
        document.title = titleMatch[1];
    }
}

// Update active state in sidebar
function updateActiveSidebarItem(url) {
    const sidebarLinks = document.querySelectorAll('aside a[href]');
    
    // Remove active class from all links
    sidebarLinks.forEach(link => {
        link.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-blue-600', 'text-white', 'font-medium', 'shadow-sm');
        link.classList.add('hover:bg-gray-50', 'transition-all', 'duration-300', 'group');
    });
    
    // Add active class to current link
    sidebarLinks.forEach(link => {
        if (link.href === url || url.startsWith(link.href)) {
            link.classList.remove('hover:bg-gray-50', 'transition-all', 'duration-300', 'group');
            link.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-blue-600', 'text-white', 'font-medium', 'shadow-sm');
        }
    });
}

// Show loading indicator
function showLoading() {
    if (loadingIndicator) {
        loadingIndicator.style.width = '30%';
        setTimeout(() => {
            if (isNavigating) {
                loadingIndicator.style.width = '70%';
            }
        }, 300);
    }
}

// Hide loading indicator
function hideLoading() {
    if (loadingIndicator) {
        loadingIndicator.style.width = '100%';
        setTimeout(() => {
            loadingIndicator.style.opacity = '0';
            setTimeout(() => {
                loadingIndicator.style.width = '0';
                loadingIndicator.style.opacity = '1';
            }, 300);
        }, 200);
    }
}

// Show error message
function showErrorMessage(message) {
    // Check if notification function exists
    if (typeof showAdminNotification === 'function') {
        showAdminNotification(message, 'error');
    } else if (typeof showNotification === 'function') {
        showNotification(message, 'error');
    } else {
        alert(message);
    }
}

// Check if a URL is external
function isExternalLink(url) {
    if (!url) return false;
    
    // Get current domain
    const currentDomain = window.location.hostname;
    
    // Create URL object
    try {
        const urlObj = new URL(url);
        return urlObj.hostname !== currentDomain;
    } catch (e) {
        return false;
    }
}

// Check if navigation requires a layout change (between admin and ujian layouts)
function isLayoutChangeNeeded(url) {
    try {
        // Current URL pattern
        const currentUrlIsUjian = window.location.pathname.includes('/admin/ujian/');
        
        // Target URL pattern
        const targetUrlIsUjian = url.includes('/admin/ujian/');
        
        // Special case: going to or from ujian dashboard
        if (
            (currentUrlIsUjian !== targetUrlIsUjian) || 
            (url.includes('/admin/ujian') && url.split('/').filter(Boolean).length === 2)
        ) {
            // console.log('Layout change needed: navigating between admin and ujian layouts');
            return true;
        }
        
        return false;
    } catch (e) {
        // console.error('Error checking layout change:', e);
        return false;
    }
}

/**
 * CATATAN PENTING: SPA DINONAKTIFKAN UNTUK MOBILE DEVICES
 * 
 * Fitur SPA (Single Page Application) telah dinonaktifkan untuk mobile devices
 * untuk meningkatkan kompatibilitas dan mengurangi kompleksitas navigasi.
 * 
 * Pada mobile devices, navigasi akan menggunakan metode refresh halaman normal
 * yang lebih stabil dan tidak menimbulkan konflik dengan komponen responsif.
 * 
 * Untuk mengaktifkan kembali SPA pada mobile, ubah nilai:
 * window.FORCE_DISABLE_SPA_ON_MOBILE = false;
 */
