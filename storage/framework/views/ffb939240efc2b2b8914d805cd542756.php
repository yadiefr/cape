<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bell Player</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>
    <!-- Hidden bell player iframe -->
    <div id="bell-status" style="display: none;">Bell System Loading...</div>

    <script>
    // BELL PLAYER IFRAME SYSTEM
    // This runs in an iframe and handles automatic bell checking
    
    // console.log('[BEL PLAYER] Bell player iframe loaded');
    
    // Configuration
    const BEL_CONFIG = {
        apiEndpoint: '/api/bel/check-current-time',
        checkInterval: 20000, // 20 seconds
        debug: true
    };
    
    // Status
    let isCheckingBell = false;
    let lastBellPlayed = null;
    
    // Function to check current bell via API
    async function checkCurrentBell() {
        if (isCheckingBell) {
            // console.log('[BEL PLAYER] Check already in progress, skipping');
            return;
        }
        
        isCheckingBell = true;
        // console.log('[BEL PLAYER] Checking for current bell...');
        
        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Make API call
            const response = await fetch(BEL_CONFIG.apiEndpoint, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken })
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            // console.log('[BEL PLAYER] API Response:', data);
            
            // Update status display
            document.getElementById('bell-status').textContent = 
                `Last check: ${new Date().toLocaleTimeString()} - ${data.message || 'No message'}`;
            
            // If bell should ring
            if (data.shouldRing && data.bell) {
                // console.log('[BEL PLAYER] Bell should ring:', data.bell.nama);
                
                // Prevent duplicate rings
                const bellKey = `${data.bell.id}-${data.server_time.time}`;
                if (lastBellPlayed === bellKey) {
                    // console.log('[BEL PLAYER] Bell already played for this time, skipping');
                    return;
                }
                
                lastBellPlayed = bellKey;
                
                // Play bell audio
                await playBellAudio(data.bell);
                
                // Show notification
                showBellNotification(data.bell.nama);
                
                // Send message to parent window
                window.parent.postMessage({
                    type: 'bell-ring',
                    bell: data.bell,
                    time: data.server_time
                }, window.location.origin);
            }
            
        } catch (error) {
            console.error('[BEL PLAYER] Error checking bell:', error);
        } finally {
            isCheckingBell = false;
        }
    }
    
    // Function to play bell audio
    async function playBellAudio(bell) {
        // console.log('[BEL PLAYER] Playing bell audio for:', bell.nama);
        
        try {
            // If bell has audio file, play it
            if (bell.audio_file) {
                // console.log('[BEL PLAYER] Playing audio file:', bell.audio_file);
                
                const audio = new Audio(bell.audio_file);
                audio.volume = 1.0;
                
                return new Promise((resolve, reject) => {
                    audio.addEventListener('ended', () => {
                        // console.log('[BEL PLAYER] Audio playback completed');
                        resolve();
                    });
                    
                    audio.addEventListener('error', (e) => {
                        console.error('[BEL PLAYER] Audio playback error:', e);
                        // Try fallback beep
                        playFallbackBeep().then(resolve);
                    });
                    
                    // Try to play
                    const playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.catch((e) => {
                            console.warn('[BEL PLAYER] Audio play failed, trying fallback:', e);
                            playFallbackBeep().then(resolve);
                        });
                    }
                });
                
            } else {
                // No audio file, use fallback beep
                // console.log('[BEL PLAYER] No audio file, using fallback beep');
                await playFallbackBeep();
            }
            
        } catch (error) {
            console.error('[BEL PLAYER] Error playing bell audio:', error);
            // Always try fallback beep as last resort
            await playFallbackBeep();
        }
    }
    
    // Fallback beep using Web Audio API
    async function playFallbackBeep() {
        return new Promise((resolve) => {
            try {
                // console.log('[BEL PLAYER] Playing fallback beep');
                
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 1.0);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 1.0);
                
                setTimeout(resolve, 1100);
                
            } catch (error) {
                console.error('[BEL PLAYER] Fallback beep failed:', error);
                resolve();
            }
        });
    }
    
    // Show notification
    function showBellNotification(bellName) {
        // console.log('[BEL PLAYER] Showing notification for:', bellName);
        
        // Try browser notification
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Bel Sekolah', {
                body: `Bel "${bellName}" telah dibunyikan`,
                icon: '/favicon.ico'
            });
        }
    }
    
    // Initialize system
    function initializeBellSystem() {
        // console.log('[BEL PLAYER] Initializing bell system...');
        
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission().then(permission => {
                // console.log('[BEL PLAYER] Notification permission:', permission);
            });
        }
        
        // Start periodic checking
        setInterval(checkCurrentBell, BEL_CONFIG.checkInterval);
        
        // Run initial check after a short delay
        setTimeout(checkCurrentBell, 2000);
        
        // console.log('[BEL PLAYER] Bell system initialized');
        
        // Send status to parent
        window.parent.postMessage({
            type: 'bel-player-status',
            status: {
                running: true,
                lastCheck: new Date().toISOString(),
                lastBell: null
            }
        }, window.location.origin);
    }
    
    // Wait for DOM to load, then initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeBellSystem);
    } else {
        initializeBellSystem();
    }
    
    // Expose checkCurrentBell function for manual testing
    window.checkCurrentBell = checkCurrentBell;
    
    </script>
</body>
</html>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views/partials/bel-player.blade.php ENDPATH**/ ?>