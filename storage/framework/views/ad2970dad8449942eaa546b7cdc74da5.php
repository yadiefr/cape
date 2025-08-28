<!-- 
    SIMPLE BELL SYSTEM WITH HIDDEN IFRAME
    File ini berisi iframe tersembunyi yang memuat bel-player.html
    yang akan menjalankan sistem bel di latar belakang.
-->
<div style="display:none">
    <iframe id="bel-player-iframe" src="<?php echo e(route('bel-player')); ?>" style="width:0;height:0;border:0;border:none;position:absolute;"></iframe>
</div>

<script>
// Simple script to communicate with iframe
(function() {
    // Status variables
    let belPlayerStatus = {
        running: false,
        lastCheck: null,
        lastBell: null
    };
    
    let iframeLoaded = false;
    
    // Check if iframe is loaded
    document.getElementById('bel-player-iframe').onload = function() {
        iframeLoaded = true;
        // console.log('[BEL SYSTEM] Iframe bell player loaded');
        
        // Force a check after iframe is loaded (with a short delay)
        setTimeout(function() {
            try {
                const iframe = document.getElementById('bel-player-iframe');
                if (iframe && iframe.contentWindow && typeof iframe.contentWindow.checkCurrentBell === 'function') {
                    // console.log('[BEL SYSTEM] Running initial check after iframe loaded');
                    iframe.contentWindow.checkCurrentBell();
                }
            } catch (e) {
                console.error('[BEL SYSTEM] Error running initial check:', e);
            }
        }, 2000);
    };
    
    // Listen for messages from iframe
    window.addEventListener('message', function(event) {
        // Verify origin for security
        if (event.origin !== window.location.origin) return;
        
        // Process bel player status messages
        if (event.data && event.data.type === 'bel-player-status') {
            belPlayerStatus = event.data.status;
            // console.log('[BEL SYSTEM] Status update received:', belPlayerStatus);
        }
    });
    
    // Expose API for external use
    window.belSystemStatus = {
        isIframeLoaded: function() {
            return iframeLoaded;
        },
        isRunning: function() {
            return belPlayerStatus.running;
        },
        getLastCheckTime: function() {
            return belPlayerStatus.lastCheck;
        },
        getLastBellTime: function() {
            return belPlayerStatus.lastBell;
        },
        checkNow: function() {
            try {
                const iframe = document.getElementById('bel-player-iframe');
                if (iframe && iframe.contentWindow && typeof iframe.contentWindow.checkCurrentBell === 'function') {
                    // console.log('[BEL SYSTEM] Manual check triggered');
                    iframe.contentWindow.checkCurrentBell();
                    return true;
                }
                return false;
            } catch (e) {
                console.error('[BEL SYSTEM] Error in manual check:', e);
                return false;
            }
        },
        playTestSound: function(audioUrl) {
            try {
                const iframe = document.getElementById('bel-player-iframe');
                if (iframe && iframe.contentWindow && typeof iframe.contentWindow.playBellAudio === 'function') {
                    // console.log('[BEL SYSTEM] Playing test sound via iframe');
                    iframe.contentWindow.playBellAudio(audioUrl);
                    return true;
                }
                return false;
            } catch (e) {
                console.error('[BEL SYSTEM] Error playing test sound:', e);
                return false;
            }
        },
        getFormattedStatus: function() {
            const formatTime = (isoString) => {
                if (!isoString) return 'Tidak ada';
                const date = new Date(isoString);
                return `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
            };
            
            return {
                running: belPlayerStatus.running ? 'Aktif' : 'Tidak Aktif',
                lastCheck: formatTime(belPlayerStatus.lastCheck),
                lastBell: formatTime(belPlayerStatus.lastBell),
                iframeLoaded: iframeLoaded
            };
        }
    };
    
    // Log that the system is initialized
    // console.log('[BEL SYSTEM] Iframe bell system initialized');
    
    // Expose system as window.belSystem for backward compatibility
    window.belSystem = window.belSystemStatus;
})();
</script>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\partials\bel-system-iframe.blade.php ENDPATH**/ ?>