// Enhanced Default Bell Sound Generator with multiple tones
(function() {
    console.log('[DEFAULT BELL] Enhanced bell script loaded');
    
    // Create a more robust audio context
    let audioContext;
    
    try {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        console.log('[DEFAULT BELL] AudioContext created, state:', audioContext.state);
    } catch (e) {
        console.error('[DEFAULT BELL] Failed to create AudioContext:', e);
        return;
    }
    
    // Resume context if suspended
    if (audioContext.state === 'suspended') {
        audioContext.resume().then(() => {
            console.log('[DEFAULT BELL] AudioContext resumed');
        }).catch(err => {
            console.error('[DEFAULT BELL] Failed to resume AudioContext:', err);
        });
    }
    
    // Enhanced bell sound function
    window.playDefaultBell = function() {
        return new Promise((resolve, reject) => {
            try {
                console.log('[DEFAULT BELL] Starting enhanced bell sound...');
                
                // Check and resume audio context
                if (audioContext.state === 'suspended') {
                    audioContext.resume().then(() => playBellSequence()).catch(reject);
                } else {
                    playBellSequence();
                }
                
                function playBellSequence() {
                    const now = audioContext.currentTime;
                    
                    // Bell tone sequence for a more realistic bell sound
                    const tones = [
                        { freq: 800, start: 0, duration: 0.8, volume: 0.6 },
                        { freq: 600, start: 0.1, duration: 0.6, volume: 0.4 },
                        { freq: 1000, start: 0.2, duration: 0.4, volume: 0.3 }
                    ];
                    
                    tones.forEach(tone => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        
                        oscillator.type = 'sine';
                        oscillator.frequency.value = tone.freq;
                        
                        const startTime = now + tone.start;
                        const endTime = startTime + tone.duration;
                        
                        // Envelope for natural sound
                        gainNode.gain.setValueAtTime(0, startTime);
                        gainNode.gain.linearRampToValueAtTime(tone.volume, startTime + 0.05);
                        gainNode.gain.exponentialRampToValueAtTime(0.001, endTime);
                        
                        oscillator.start(startTime);
                        oscillator.stop(endTime);
                    });
                    
                    console.log('[DEFAULT BELL] Bell sequence scheduled');
                    
                    // Resolve after the longest tone finishes
                    setTimeout(() => {
                        console.log('[DEFAULT BELL] Enhanced bell sound completed');
                        resolve('Enhanced default bell sound played successfully');
                    }, 1000);
                }
                
            } catch (error) {
                console.error('[DEFAULT BELL] Error playing bell:', error);
                reject(error);
            }
        });
    };
    
    // Also create a simple test function
    window.testBellSound = function() {
        console.log('[DEFAULT BELL] Testing bell sound...');
        return window.playDefaultBell();
    };
    
    console.log('[DEFAULT BELL] Enhanced bell functions ready');
})();
