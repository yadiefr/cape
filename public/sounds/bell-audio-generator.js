// File audio placeholder - implementasi Web Audio API untuk membuat suara bel sintesis
class BellAudioGenerator {
    constructor() {
        this.audioContext = null;
        this.initialized = false;
    }

    // Initialize audio context (harus dipanggil setelah user interaction)
    init() {
        if (!this.initialized && (window.AudioContext || window.webkitAudioContext)) {
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            this.initialized = true;
        }
        return this.initialized;
    }

    // Generate bell sound using Web Audio API
    playBellSound(type = 'reguler', duration = 2000) {
        if (!this.init()) {
            console.error('Web Audio API not supported');
            return false;
        }

        const now = this.audioContext.currentTime;
        
        // Create oscillators for bell-like sound
        const oscillator1 = this.audioContext.createOscillator();
        const oscillator2 = this.audioContext.createOscillator();
        const oscillator3 = this.audioContext.createOscillator();
        
        // Create gain nodes for volume control
        const gainNode1 = this.audioContext.createGain();
        const gainNode2 = this.audioContext.createGain();
        const gainNode3 = this.audioContext.createGain();
        const masterGain = this.audioContext.createGain();

        // Set frequencies based on bell type
        let baseFreq;
        switch(type) {
            case 'istirahat':
                baseFreq = 440; // A4 - more relaxed
                break;
            case 'ujian':
                baseFreq = 523; // C5 - higher pitch for attention
                break;
            case 'khusus':
                baseFreq = 659; // E5 - distinct sound
                break;
            default: // reguler
                baseFreq = 349; // F4 - standard bell
        }

        // Set oscillator frequencies (bell harmonics)
        oscillator1.frequency.setValueAtTime(baseFreq, now);
        oscillator2.frequency.setValueAtTime(baseFreq * 1.5, now);
        oscillator3.frequency.setValueAtTime(baseFreq * 2, now);

        // Set oscillator types
        oscillator1.type = 'sine';
        oscillator2.type = 'sine';
        oscillator3.type = 'triangle';

        // Connect oscillators to gain nodes
        oscillator1.connect(gainNode1);
        oscillator2.connect(gainNode2);
        oscillator3.connect(gainNode3);

        // Connect gain nodes to master gain
        gainNode1.connect(masterGain);
        gainNode2.connect(masterGain);
        gainNode3.connect(masterGain);

        // Connect to audio context destination
        masterGain.connect(this.audioContext.destination);

        // Set initial volumes
        gainNode1.gain.setValueAtTime(0.3, now);
        gainNode2.gain.setValueAtTime(0.2, now);
        gainNode3.gain.setValueAtTime(0.1, now);
        masterGain.gain.setValueAtTime(0.3, now);

        // Create bell envelope (attack, decay, sustain, release)
        const attackTime = 0.01;
        const decayTime = 0.2;
        const sustainLevel = 0.3;
        const releaseTime = duration / 1000 - attackTime - decayTime;

        // Apply envelope to master gain
        masterGain.gain.setValueAtTime(0, now);
        masterGain.gain.linearRampToValueAtTime(0.3, now + attackTime);
        masterGain.gain.exponentialRampToValueAtTime(sustainLevel, now + attackTime + decayTime);
        masterGain.gain.setValueAtTime(sustainLevel, now + attackTime + decayTime + releaseTime);
        masterGain.gain.exponentialRampToValueAtTime(0.01, now + duration / 1000);

        // Start oscillators
        oscillator1.start(now);
        oscillator2.start(now);
        oscillator3.start(now);

        // Stop oscillators after duration
        oscillator1.stop(now + duration / 1000);
        oscillator2.stop(now + duration / 1000);
        oscillator3.stop(now + duration / 1000);

        return true;
    }

    // Play multiple chimes for different bell types
    playBellSequence(type = 'reguler') {
        const sequences = {
            'reguler': [0], // Single chime
            'istirahat': [0, 500], // Double chime
            'ujian': [0, 200, 400], // Triple chime - urgent
            'khusus': [0, 300, 600, 900] // Quadruple chime - special
        };

        const sequence = sequences[type] || sequences['reguler'];
        
        sequence.forEach((delay, index) => {
            setTimeout(() => {
                this.playBellSound(type, 1500);
            }, delay);
        });
    }
}

// Export for global use
window.BellAudioGenerator = BellAudioGenerator;
