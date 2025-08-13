/**
 * MOBILE SIDEBAR DEBUGGING SCRIPT
 * 
 * Jalankan script ini di browser console untuk debugging sidebar mobile
 * Copy dan paste ke Developer Console (F12)
 */

console.log('🔧 Mobile Sidebar Debug Script Loaded');

// Test Functions
window.mobileSidebarDebug = {
    
    // Check current state
    checkState: function() {
        const sidebar = document.querySelector('aside');
        const backdrop = document.querySelector('.sidebar-backdrop');
        const body = document.body;
        
        console.log('📊 Current Sidebar State:');
        console.log('========================');
        console.log('Window Width:', window.innerWidth);
        console.log('Screen Width:', window.screen.width);
        console.log('Is Mobile (< 1024):', window.innerWidth < 1024);
        console.log('Alpine Data Available:', !!window.Alpine);
        
        if (sidebar) {
            console.log('Sidebar Element:', sidebar);
            console.log('Sidebar Classes:', sidebar.className);
            console.log('Sidebar Transform:', sidebar.style.transform);
            console.log('Sidebar Z-Index:', getComputedStyle(sidebar).zIndex);
        } else {
            console.error('❌ Sidebar element not found!');
        }
        
        if (backdrop) {
            console.log('Backdrop Element:', backdrop);
            console.log('Backdrop Classes:', backdrop.className);
            console.log('Backdrop Display:', getComputedStyle(backdrop).display);
        } else {
            console.error('❌ Backdrop element not found!');
        }
        
        console.log('Body Classes:', body.className);
        console.log('Body Overflow:', getComputedStyle(body).overflow);
        console.log('Body Position:', getComputedStyle(body).position);
    },
    
    // Force open sidebar
    forceOpen: function() {
        console.log('🔓 Force Opening Sidebar...');
        const sidebar = document.querySelector('aside');
        if (sidebar) {
            sidebar.classList.remove('-translate-x-full', 'sidebar-slide-out');
            sidebar.classList.add('translate-x-0', 'sidebar-slide-in');
            sidebar.style.transform = 'translate3d(0, 0, 0)';
            sidebar.style.webkitTransform = 'translate3d(0, 0, 0)';
            
            // Show backdrop
            const backdrop = document.querySelector('.sidebar-backdrop');
            if (backdrop) {
                backdrop.classList.remove('opacity-0', 'hidden');
                backdrop.classList.add('opacity-100', 'block');
            }
            
            console.log('✅ Sidebar forced open');
        }
    },
    
    // Force close sidebar
    forceClose: function() {
        console.log('🔒 Force Closing Sidebar...');
        const sidebar = document.querySelector('aside');
        if (sidebar) {
            sidebar.classList.remove('translate-x-0', 'sidebar-slide-in');
            sidebar.classList.add('-translate-x-full', 'sidebar-slide-out');
            sidebar.style.transform = 'translate3d(-16rem, 0, 0)';
            sidebar.style.webkitTransform = 'translate3d(-16rem, 0, 0)';
            
            // Hide backdrop
            const backdrop = document.querySelector('.sidebar-backdrop');
            if (backdrop) {
                backdrop.classList.remove('opacity-100', 'block');
                backdrop.classList.add('opacity-0', 'hidden');
            }
            
            console.log('✅ Sidebar forced closed');
        }
    },
    
    // Test toggle function
    testToggle: function() {
        console.log('🔄 Testing Toggle Function...');
        try {
            // Get Alpine data
            const element = document.querySelector('[x-data="sidebarData"]');
            if (element && element._x_dataStack) {
                const alpineData = element._x_dataStack[0];
                console.log('Alpine Data:', alpineData);
                
                if (typeof alpineData.toggleSidebar === 'function') {
                    console.log('⚡ Calling toggleSidebar()...');
                    alpineData.toggleSidebar();
                } else {
                    console.error('❌ toggleSidebar function not found');
                }
            } else {
                console.error('❌ Alpine data not found');
            }
        } catch (error) {
            console.error('❌ Error testing toggle:', error);
        }
    },
    
    // Check CSS classes
    checkCSS: function() {
        console.log('🎨 Checking CSS Classes...');
        const sidebar = document.querySelector('aside');
        if (sidebar) {
            const styles = getComputedStyle(sidebar);
            console.log('Transform:', styles.transform);
            console.log('Transition:', styles.transition);
            console.log('Z-Index:', styles.zIndex);
            console.log('Position:', styles.position);
            console.log('Width:', styles.width);
            console.log('Height:', styles.height);
            console.log('Top:', styles.top);
            console.log('Left:', styles.left);
        }
    },
    
    // Check for conflicts
    checkConflicts: function() {
        console.log('⚠️ Checking for Conflicts...');
        
        // Check if there are multiple sidebar elements
        const sidebars = document.querySelectorAll('aside');
        console.log('Number of <aside> elements:', sidebars.length);
        
        // Check for overlapping z-indexes
        const highZElements = Array.from(document.querySelectorAll('*')).filter(el => {
            const zIndex = parseInt(getComputedStyle(el).zIndex);
            return zIndex > 30;
        });
        console.log('Elements with z-index > 30:', highZElements);
        
        // Check for CSS that might override
        const sidebar = document.querySelector('aside');
        if (sidebar) {
            const hasImportantTransform = sidebar.style.cssText.includes('!important');
            console.log('Has !important in inline styles:', hasImportantTransform);
        }
    },
    
    // Fix common issues
    fix: function() {
        console.log('🔧 Attempting to fix common issues...');
        
        // Clear conflicting styles
        const sidebar = document.querySelector('aside');
        if (sidebar) {
            sidebar.style.transform = '';
            sidebar.style.webkitTransform = '';
            sidebar.className = 'fixed top-0 left-0 z-40 w-64 bg-white shadow-lg h-full pt-16 lg:pt-0 lg:top-16 lg:h-[calc(100%-4rem)] lg:sidebar-desktop -translate-x-full';
            console.log('✅ Reset sidebar styles and classes');
        }
        
        // Reset body
        document.body.classList.remove('overflow-hidden');
        document.body.style.position = '';
        document.body.style.width = '';
        document.body.style.top = '';
        console.log('✅ Reset body styles');
        
        // Hide backdrop
        const backdrop = document.querySelector('.sidebar-backdrop');
        if (backdrop) {
            backdrop.classList.remove('opacity-100', 'block');
            backdrop.classList.add('opacity-0', 'hidden');
            console.log('✅ Reset backdrop');
        }
    },
    
    // Show help
    help: function() {
        console.log(`
🔧 Mobile Sidebar Debug Commands:
================================
mobileSidebarDebug.checkState()    - Check current state
mobileSidebarDebug.forceOpen()     - Force open sidebar
mobileSidebarDebug.forceClose()    - Force close sidebar
mobileSidebarDebug.testToggle()    - Test Alpine.js toggle function
mobileSidebarDebug.checkCSS()      - Check computed CSS
mobileSidebarDebug.checkConflicts() - Check for conflicts
mobileSidebarDebug.fix()           - Attempt to fix issues
mobileSidebarDebug.help()          - Show this help

Quick Test Sequence:
==================
1. mobileSidebarDebug.checkState()
2. mobileSidebarDebug.forceOpen()
3. mobileSidebarDebug.forceClose()
4. mobileSidebarDebug.testToggle()
        `);
    }
};

// Auto-check on load
mobileSidebarDebug.checkState();
mobileSidebarDebug.help();

console.log('✅ Debug tools loaded. Type mobileSidebarDebug.help() for commands.');
