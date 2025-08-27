// AJAX Functions untuk Manajemen Bel Sekolah

// Inisialisasi audio generator
let bellAudioGenerator = null;

// Gunakan sistem bel global jika tersedia
// // console.log('Checking for global bell system...');
if (window.belAutoCheck) {
    // // console.log('Global bell system detected, will integrate with it');
}

// Audio Context polyfill
(function() {
    window.AudioContext = window.AudioContext || window.webkitAudioContext;
    if (!window.AudioContext) {
        console.warn('AudioContext not supported in this browser. Bell sounds may not work.');
    } else {
        // // console.log('AudioContext is supported in this browser.');
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    // // console.log('DOM Content Loaded - Initializing bell system');
    
    // Load audio generator script
    const script = document.createElement('script');
    script.src = '/sounds/bell-audio-generator.js';
    script.onload = function() {
        // // console.log('Bell Audio Generator script loaded successfully');
        try {
            bellAudioGenerator = new BellAudioGenerator();
            // // console.log('Bell Audio Generator initialized successfully');
        } catch (error) {
            console.error('Error initializing Bell Audio Generator:', error);
        }
    };
    script.onerror = function() {
        console.error('Bell Audio Generator failed to load');
    };
    document.head.appendChild(script);
    
    // Setup CSRF token untuk AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        // Set default headers untuk fetch
        window.csrfToken = token.getAttribute('content');
        // console.log('CSRF token loaded successfully');
    } else {
        console.error('CSRF token meta tag not found!');
    }
    
    // Initialize any audio to overcome browsers' autoplay restrictions
    document.addEventListener('click', function initAudio() {
        // console.log('User interaction detected - initializing audio');
        
        // Create and immediately remove a silent audio context to enable audio later
        if (window.AudioContext) {
            const tempContext = new AudioContext();
            if (tempContext.state === 'suspended') {
                tempContext.resume().then(() => {
                    // console.log('Audio context resumed on user interaction');
                });
            }
        }
        
        // Remove this listener after first click
        document.removeEventListener('click', initAudio);
    });
});

// Function untuk test specific bel
function testBelAPI(belId) {
    // console.log('Testing bel API for ID:', belId);
    
    // Test toggle first dengan web route
    fetch(`/admin/bel/${belId}/ajax-toggle-aktif`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // console.log('Toggle test response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text(); // Use text first to see raw response
    })
    .then(text => {
        // console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
            // console.log('Parsed response:', data);
            showNotification('Test Toggle berhasil!', 'success');
        } catch (e) {
            console.error('JSON parse error:', e);
            showNotification('Response bukan JSON valid: ' + text, 'error');
        }
    })
    .catch(error => {
        console.error('Toggle test error:', error);
        showNotification('Test Toggle gagal: ' + error.message, 'error');
    });
}

// Fungsi untuk toggle aktif/non-aktif bel
function toggleAktif(belId) {
    // console.log('Attempting to toggle bel ID:', belId);
    
    // Tampilkan loading pada tombol
    const button = document.getElementById(`toggle-btn-${belId}`);
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    // Kirim request AJAX ke web route
    fetch(`/admin/bel/${belId}/ajax-toggle-aktif`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // console.log('Response status:', response.status);
        // console.log('Response headers:', response.headers);
        
        if (response.status === 401) {
            throw new Error('Anda tidak memiliki akses. Silakan login kembali.');
        }
        
        if (response.status === 403) {
            throw new Error('Akses ditolak. Anda tidak memiliki permission.');
        }
        
        if (response.status === 419) {
            throw new Error('CSRF token expired. Silakan refresh halaman.');
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // console.log('Response data:', data);
        
        if (data.success) {
            // Update icon toggle
            const icon = data.aktif ? 'fas fa-toggle-on' : 'fas fa-toggle-off';
            button.innerHTML = `<i class="${icon}"></i>`;
            
            // Update status text dalam tabel
            const statusCell = button.closest('tr').querySelector('td:nth-child(4)');
            if (data.aktif) {
                statusCell.innerHTML = '<span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aktif</span>';
            } else {
                statusCell.innerHTML = '<span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Tidak Aktif</span>';
            }
            
            // Tampilkan notifikasi sukses
            showNotification(data.message, 'success');
        } else {
            // Kembalikan button ke kondisi semula
            button.innerHTML = originalHtml;
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Detailed error:', error);
        button.innerHTML = originalHtml;
        showNotification('Terjadi kesalahan saat mengubah status bel: ' + error.message, 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}

// Fungsi untuk membunyikan bel
function bunyikanBel(belId, namaBel, tipe = null, skipConfirmation = false) {
    if (!skipConfirmation && !confirm(`Apakah Anda yakin ingin membunyikan bel "${namaBel}"?`)) {
        return;
    }

    // console.log('Attempting to ring bel ID:', belId, 'Name:', namaBel, 'Type:', tipe, 'Auto:', skipConfirmation);
    
    // Highlight baris yang bersangkutan
    try {
        const row = document.getElementById(`row-${belId}`);
        if (row) {
            // Reset class lain dulu
            row.classList.remove('bg-yellow-50');
            // Tambahkan highlight
            row.classList.add('bg-green-50');
            // Hapus highlight setelah 5 detik
            setTimeout(() => {
                row.classList.remove('bg-green-50');
            }, 5000);
        }
    } catch (e) {
        console.error('Error highlighting row:', e);
    }

    // Periksa CSRF token
    if (!window.csrfToken) {
        console.error('CSRF token not found. Looking for meta tag...');
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.csrfToken = token.getAttribute('content');
            // console.log('CSRF token found in meta tag');
        } else {
            console.error('CSRF token meta tag not found!');
            showNotification('Error: CSRF token tidak ditemukan. Silakan refresh halaman.', 'error');
            return;
        }
    }

    // Log headers yang akan dikirim
    // console.log('Sending request with headers:', {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.csrfToken,
        'Accept': 'application/json'
    });

    // Kirim request AJAX ke web route
    fetch(`/admin/bel/${belId}/ajax-bunyikan`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // console.log('Ring bell response status:', response.status);
        // console.log('Response headers:', response.headers);
        
        if (response.status === 401) {
            throw new Error('Anda tidak memiliki akses. Silakan login kembali.');
        }
        
        if (response.status === 403) {
            throw new Error('Akses ditolak. Anda tidak memiliki permission.');
        }
        
        if (response.status === 419) {
            throw new Error('CSRF token expired. Silakan refresh halaman.');
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Tambahkan debugging untuk respons mentah
        return response.text().then(text => {
            // console.log('Raw response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON response:', e);
                throw new Error('Invalid JSON response: ' + text);
            }
        });
    })
    .then(data => {
        // console.log('Ring bell response data:', data);
        
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Play audio - gunakan tipe yang diterima dari parameter atau ekstrak dari nama
            const jenisBel = tipe || extractBellType(namaBel);
            
            // Cek apakah ada file audio yang valid
            if (data.audio_file) {
                // Deteksi apakah ini file JS atau file audio
                if (data.audio_file.endsWith('.js')) {
                    // console.log(`[BEL RING] Loading bell audio generator script: ${data.audio_file}`);
                    // Load and run JS file
                    const script = document.createElement('script');
                    script.src = data.audio_file;
                    script.onload = function() {
                        // console.log('[BEL RING] Default bell script loaded, running...');
                        if (typeof playDefaultBell === 'function') {
                            playDefaultBell().then(result => // console.log('[BEL RING]', result));
                        } else {
                            console.error('[BEL RING] playDefaultBell function not found');
                            playBellAudioImmediate(null, jenisBel, namaBel); // Fallback
                        }
                    };
                    script.onerror = function() {
                        console.error('[BEL RING] Failed to load bell script');
                        playBellAudioImmediate(null, jenisBel, namaBel); // Fallback
                    };
                    document.head.appendChild(script);
                } else {
                    // Normal audio file - use immediate playback
                    // console.log(`[BEL RING] Playing bell audio with type: ${jenisBel}, file: ${data.audio_file}`);
                    playBellAudioImmediate(data.audio_file, jenisBel, namaBel);
                }
            } else {
                // No audio file, use immediate fallback
                console.warn('[BEL RING] No audio file provided, using immediate fallback');
                playBellAudioImmediate(null, jenisBel, namaBel);
            }
        } else {
            console.warn('Server reported failure:', data.message);
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('[BEL RING] Ring bell error:', error);
        showNotification('Terjadi kesalahan saat membunyikan bel: ' + error.message, 'error');
        
        // Bahkan jika API gagal, tetap coba putar audio sebagai fallback darurat
        // console.log('[BEL RING] API failed, attempting emergency audio playback...');
        const jenisBel = tipe || extractBellType(namaBel);
        playBellAudioImmediate(null, jenisBel, namaBel);
    });
}

// Fungsi untuk menghapus bel
function hapusBel(belId, namaBel) {
    if (!confirm(`Apakah Anda yakin ingin menghapus jadwal bel "${namaBel}"?\nData yang dihapus tidak dapat dikembalikan.`)) {
        return;
    }

    // Kirim request AJAX ke web route
    fetch(`/admin/bel/${belId}/ajax-destroy`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hapus baris dari tabel dengan animasi
            const row = document.getElementById(`row-${belId}`);
            if (row) {
                row.style.opacity = '0.5';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.remove();
                }, 300);
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menghapus jadwal bel.', 'error');
    });
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'info') {
    // Hapus notifikasi yang sudah ada
    const existingNotification = document.getElementById('dynamic-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Buat elemen notifikasi baru
    const notification = document.createElement('div');
    notification.id = 'dynamic-notification';
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.style.maxWidth = '500px';

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
            icon = 'fas fa-times-circle';
            break;
        default:
            bgColor = 'bg-blue-100';
            borderColor = 'border-blue-500';
            textColor = 'text-blue-700';
            icon = 'fas fa-info-circle';
    }

    notification.className = `${bgColor} border-l-4 ${borderColor} ${textColor} p-4 rounded-md shadow-lg transition-all duration-300`;
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="py-1"><i class="${icon} mr-2"></i></div>
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    // Tambahkan ke halaman
    document.body.appendChild(notification);

    // Auto-hide setelah 5 detik
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Fungsi untuk memutar audio bel dengan segera - prioritas kecepatan dan keandalan
async function playBellAudioImmediate(audioFilePath, jenisBel = 'reguler', bellName = '') {
    // console.log(`[AUDIO IMMEDIATE] Playing bell audio: ${bellName}, Type: ${jenisBel}, File: ${audioFilePath}`);
    
    let audioPlayed = false;
    
    // METODE 1: Coba audio file jika tersedia
    if (audioFilePath && !audioFilePath.endsWith('.js')) {
        try {
            // console.log('[AUDIO IMMEDIATE] METHOD 1: Trying audio file');
            const audio = new Audio(audioFilePath + '?t=' + Date.now());
            audio.volume = 1.0;
            await audio.play();
            // console.log('[AUDIO IMMEDIATE] METHOD 1 SUCCESS: Audio file played');
            audioPlayed = true;
        } catch (error) {
            console.error('[AUDIO IMMEDIATE] METHOD 1 FAILED:', error);
        }
    }
    
    // METODE 2: Web Audio API beep jika file audio gagal
    if (!audioPlayed) {
        try {
            // console.log('[AUDIO IMMEDIATE] METHOD 2: Trying Web Audio API');
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            
            // Resume context jika suspended
            if (audioContext.state === 'suspended') {
                await audioContext.resume();
            }
            
            // Buat beep sequence berdasarkan jenis bel
            const bellSequences = {
                'reguler': [{ freq: 800, duration: 0.5 }],
                'istirahat': [{ freq: 600, duration: 0.3 }, { freq: 800, duration: 0.3 }],
                'ujian': [{ freq: 1000, duration: 0.8 }],
                'khusus': [{ freq: 400, duration: 0.2 }, { freq: 600, duration: 0.2 }, { freq: 800, duration: 0.2 }]
            };
            
            const sequence = bellSequences[jenisBel] || bellSequences['reguler'];
            
            for (let i = 0; i < sequence.length; i++) {
                const { freq, duration } = sequence[i];
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                const startTime = audioContext.currentTime + (i * (duration + 0.1));
                
                oscillator.frequency.value = freq;
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0, startTime);
                gainNode.gain.linearRampToValueAtTime(0.5, startTime + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
                
                oscillator.start(startTime);
                oscillator.stop(startTime + duration);
            }
            
            // console.log('[AUDIO IMMEDIATE] METHOD 2 SUCCESS: Web Audio sequence played');
            audioPlayed = true;
        } catch (error) {
            console.error('[AUDIO IMMEDIATE] METHOD 2 FAILED:', error);
        }
    }
    
    // METODE 3: Fallback base64 audio jika semua gagal
    if (!audioPlayed) {
        try {
            // console.log('[AUDIO IMMEDIATE] METHOD 3: Trying base64 fallback');
            const fallbackAudio = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qa7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA==");
            fallbackAudio.volume = 0.8;
            await fallbackAudio.play();
            // console.log('[AUDIO IMMEDIATE] METHOD 3 SUCCESS: Base64 audio played');
            audioPlayed = true;
        } catch (error) {
            console.error('[AUDIO IMMEDIATE] METHOD 3 FAILED:', error);
        }
    }
    
    if (audioPlayed) {
        // console.log(`[AUDIO IMMEDIATE] SUCCESS: Bell "${bellName}" audio played successfully`);
        return true;
    } else {
        console.error(`[AUDIO IMMEDIATE] FAILED: All audio methods failed for bell "${bellName}"`);
        return false;
    }
}

// Fungsi untuk extract jenis bel dari nama
function extractBellType(namaBel) {
    const nama = namaBel.toLowerCase();
    
    if (nama.includes('istirahat') || nama.includes('break')) {
        return 'istirahat';
    } else if (nama.includes('ujian') || nama.includes('exam') || nama.includes('test')) {
        return 'ujian';
    } else if (nama.includes('khusus') || nama.includes('special') || nama.includes('upacara')) {
        return 'khusus';
    } else {
        return 'reguler';
    }
}

// Fungsi untuk memutar audio bel
async function playBellAudio(audioFilePath, jenisBel = 'reguler') {
    // console.log('Playing bell audio:', { audioFilePath, jenisBel });
    
    // Gunakan semua metode yang tersedia untuk memastikan bel berbunyi
    let succeeded = false;
    
    // 1. Coba gunakan belSystem.playTestSound (API dari iframe) jika tersedia
    if (window.belSystem && typeof window.belSystem.playTestSound === 'function') {
        try {
            // console.log('METHOD 1: Using belSystem API');
            await window.belSystem.playTestSound(audioFilePath);
            // console.log('METHOD 1 SUCCESS: Audio played via belSystem API');
            succeeded = true;
        } catch (error) {
            console.error('METHOD 1 FAILED: Error using belSystem API:', error);
        }
    }
    
    // 2. Coba gunakan iframe langsung sebagai backup
    if (!succeeded) {
        try {
            const iframe = document.getElementById('bel-player-iframe');
            if (iframe && iframe.contentWindow && typeof iframe.contentWindow.playBellAudio === 'function') {
                // console.log('METHOD 2: Using direct iframe access');
                iframe.contentWindow.playBellAudio(audioFilePath);
                // console.log('METHOD 2 SUCCESS: Audio played via direct iframe');
                succeeded = true;
            }
        } catch (iframeError) {
            console.error('METHOD 2 FAILED: Error using direct iframe:', iframeError);
        }
    }
    
    // 3. Gunakan sistem bel inline legacy jika tersedia
    if (!succeeded && window.belSystem && typeof window.belSystem.playTestSound === 'function') {
        try {
            // console.log('METHOD 3: Using legacy inline system');
            await window.belSystem.playTestSound(audioFilePath);
            // console.log('METHOD 3 SUCCESS: Audio played via legacy system');
            succeeded = true;
        } catch (error) {
            console.error('METHOD 3 FAILED: Legacy system error:', error);
        }
    }
    
    // 4. Coba metode audio standar jika semua metode lainnya gagal
    try {
        // console.log('METHOD 4: Using standard Audio API');
        // Jika ada file audio dari server, coba putar file tersebut
        if (audioFilePath) {
            // console.log(`Attempting to play audio file from: ${audioFilePath}`);
            
            // Tambahkan cache buster untuk memastikan file audio dimuat ulang
            const audioCacheBuster = `${audioFilePath}?t=${Date.now()}`;
            const audio = new Audio(audioCacheBuster);
            
            // Add event listeners for debugging
            audio.addEventListener('canplaythrough', () => {
                // console.log('Audio is ready to play');
            });
            
            audio.addEventListener('error', (e) => {
                console.error('Audio loading error:', e);
                console.error('Audio error code:', audio.error ? audio.error.code : 'unknown');
            });
            
            audio.volume = 1.0; // Volume maksimum
            
            // Try to play the audio file
            try {
                // Pastikan resume audio context dulu
                if (window.AudioContext || window.webkitAudioContext) {
                    const tempContext = new (window.AudioContext || window.webkitAudioContext)();
                    if (tempContext.state === 'suspended') {
                        console.warn('AudioContext is suspended. Attempting to resume...');
                        await tempContext.resume();
                    }
                }
                
                // Coba putar dengan audio API standard
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    await playPromise;
                    // console.log('METHOD 4 SUCCESS: Audio file played successfully');
                    succeeded = true;
                } else {
                    console.warn('METHOD 4 WARNING: Play did not return a promise, may not be supported');
                }
            } catch (audioError) {
                console.error('METHOD 4 FAILED: Detailed error:', audioError);
            }
        } else {
            console.warn('METHOD 4 SKIPPED: No audio file path provided');
        }
        
        // 5. Gunakan audio generator sintesis jika masih belum berhasil
        if (!succeeded && bellAudioGenerator) {
            // console.log('METHOD 5: Using synthesized bell audio');
            try {
                if (!bellAudioGenerator.initialized) {
                    // console.log('Initializing bell audio generator...');
                    bellAudioGenerator.init();
                }
                bellAudioGenerator.playBellSequence(jenisBel);
                // console.log('METHOD 5 SUCCESS: Bell audio generator played successfully');
                succeeded = true;
            } catch (generatorError) {
                console.error('METHOD 5 FAILED: Bell audio generator error:', generatorError);
            }
        }
        
        // 6. Fallback terakhir: beep sederhana
        if (!succeeded) {
            // console.log('METHOD 6: Using simple beep fallback');
            try {
                playSimpleBeep();
                // console.log('METHOD 6 SUCCESS: Simple beep played');
                succeeded = true;
            } catch (beepError) {
                console.error('METHOD 6 FAILED: Simple beep error:', beepError);
            }
        }
        
        // 7. Emergency fallback dengan Audio Base64
        if (!succeeded) {
            try {
                // console.log('METHOD 7: Using emergency audio Base64');
                const snd = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA//uQZAUAB1WI0PZugAAAAAoQwAAAEk3nRd2qAAAAACiDgAAAAAAABCqEEQRLCgwpBGMlJkIz8jKhGvj4k6jzRnqasNKIeoh5gI7BJaC1A1AoNBjJgbyApVS4IDlZgDU5WUAxEKDNmmALHzZp0Fkz1FMTmGFl1FMEyodIavcCAUHDWrKAIA4aa2oCgILEBupZgHvAhEBcZ6joQBxS76AgccrFlczBvKLC0QI2cBoCFvfTDAo7eoOQInqDPBtvrDEZBNYN5xwNwxQRfw8ZQ5wQVLvO8OYU+mHvFLlDh05Mdg7BT6YrRPpCBznMB2r//xKJjyyOh+cImr2/4doscwD6neZjuZR4AgAABYAAAABy1xcdQtxYBYYZdifkUDgzzXaXn98Z0oi9ILU5mBjFANmRwlVJ3/6jYDAmxaiDG3/6xjQQCCKkRb/6kg/wW+kSJ5//rLobkLSiKmqP/0ikJuDaSaSf/6JiLYLEYnW/+kXg1WRVJL/9EmQ1YZIsv/6Qzwy5qk7/+tEU0nkls3/zIUMPKNX/6yZLf+kFgAfgGyLFAUwY//uQZAUABcd5UiNPVXAAAApAAAAAE0VZQKw9ISAAACgAAAAAVQIygIElVrFkBS+Jhi+EAuu+lKAkYUEIsmEAEoMeDmCETMvfSHTGkF5RWH7kz/ESHWPAq/kcCRhqBtMdokPdM7vil7RG98A2sc7zO6ZvTdM7pmOUAZTnJW+NXxqmd41dqJ6mLTXxrPpnV8fy81eXQ+venT/Wr53cNVuQ77hJJu1BRg8cULeXFZhZB+HlJ8s41DuKMbA/s2g+crzz+I0j8rxu1B0NEXvig8kXZ1uKE4D3ihy/D/Q73klTvCEWyGQg7Vi78iYhFCMLAgdFzAFpYUE9uT0+omEYvb7fHWSvMy5dQJNvOhHLtPnpZahIlZFLGJXc3ekZrEg5f/TZmS8YK4GwmAHHBiRIqB4YmZ+D3iPViYWTQnhsYRzxQ4xnrjrYhzHWxD2bhLRjgYkOEM8RuoLHjXPcWtuIug9Hh/oX4wZdOtLdIZAG0RHULtJ9Vp47GBTfszdQQAGJZChkA4MCYwDK7EYAEA9ZFdYQ+yX2gJYQHvhnDWdvJjD7xQzTBmufDZ5jiwmfDm8k0EQZ8ryyxt6TkWx9wH8AO8H5oFMEQnguwBGcR");
                snd.play();
                // console.log('METHOD 7 SUCCESS: Emergency audio played');
                succeeded = true;
            } catch (emergencyError) {
                console.error('METHOD 7 FAILED: Emergency audio error:', emergencyError);
            }
        }
        
        // Akhirnya, tampilkan notifikasi berdasarkan hasilnya
        if (succeeded) {
            showNotification('Bel berhasil dibunyikan', 'success');
        } else {
            showNotification('Gagal membunyikan bel. Periksa pengaturan audio browser Anda.', 'error');
            console.error('ALL METHODS FAILED: Could not play bell audio');
        }
    } catch (error) {
        console.error('Unexpected error in playBellAudio:', error);
        showNotification('Terjadi kesalahan saat membunyikan bel.', 'error');
    }
}

// Fallback beep sederhana - Enhanced for better reliability
function playSimpleBeep() {
    try {
        // console.log('Playing simple beep fallback');
        
        // Try to create audio context with fallbacks
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            if (audioContext) {
                // Resume context if needed
                if (audioContext.state === 'suspended') {
                    audioContext.resume().catch(err => console.warn('Error resuming audio context:', err));
                }
                
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                // Bell sound parameters - standard school bell
                oscillator.frequency.value = 800;
                oscillator.type = 'sine';
                
                // Envelope to prevent clicks and pops
                const now = audioContext.currentTime;
                gainNode.gain.setValueAtTime(0, now);
                gainNode.gain.linearRampToValueAtTime(0.3, now + 0.01);
                gainNode.gain.exponentialRampToValueAtTime(0.01, now + 0.5);
                
                oscillator.start(now);
                oscillator.stop(now + 0.5);
                
                // Play a second tone after a short pause
                setTimeout(() => {
                    try {
                        const oscillator2 = audioContext.createOscillator();
                        const gainNode2 = audioContext.createGain();
                        
                        oscillator2.connect(gainNode2);
                        gainNode2.connect(audioContext.destination);
                        
                        oscillator2.frequency.value = 800;
                        oscillator2.type = 'sine';
                        
                        const now2 = audioContext.currentTime;
                        gainNode2.gain.setValueAtTime(0, now2);
                        gainNode2.gain.linearRampToValueAtTime(0.3, now2 + 0.01);
                        gainNode2.gain.exponentialRampToValueAtTime(0.01, now2 + 0.5);
                        
                        oscillator2.start(now2);
                        oscillator2.stop(now2 + 0.5);
                    } catch (error) {
                        console.warn('Second beep failed:', error);
                    }
                }, 700);
                
                // console.log('Simple beep played successfully');
                return true;
            }
        } catch (audioError) {
            console.warn('AudioContext failed:', audioError);
        }
        
        // If AudioContext fails, try Audio API with base64 data
        const snd = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA==");
        snd.volume = 1.0;
        snd.play().catch(err => console.warn('Base64 audio failed:', err));
        // console.log('Base64 beep played as fallback');
        return true;
    } catch (error) {
        console.error('All fallback beep methods failed:', error);
        return false;
    }
}
