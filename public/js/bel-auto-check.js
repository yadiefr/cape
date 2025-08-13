/**
 * Bel Auto Check - Sistem pengecekan bel otomatis global
 * File ini dimuat di semua halaman aplikasi untuk memastikan bel berbunyi 
 * tepat waktu terlepas dari halaman mana yang sedang dibuka.
 * 
 * DEBUGGING VERSION - Menggunakan localStorage untuk debug dan tracking
 */

// Inisialisasi debugging
const DEBUG = true;
const DEBUG_PREFIX = 'BEL_DEBUG_';

// Fungsi logging dengan penyimpanan di localStorage
function debugLog(message, data = null) {
    if (!DEBUG) return;
    
    const timestamp = new Date().toISOString();
    const logMessage = `[${timestamp}] ${message}`;
    
    console.log(logMessage, data || '');
    
    // Simpan log ke localStorage (terbatas 50 entri)
    const logs = JSON.parse(localStorage.getItem(`${DEBUG_PREFIX}LOGS`) || '[]');
    logs.unshift({
        time: timestamp,
        msg: message,
        data: data ? JSON.stringify(data).substring(0, 200) : null,
        page: window.location.pathname
    });
    
    // Batasi jumlah log
    if (logs.length > 50) {
        logs.length = 50;
    }
    
    localStorage.setItem(`${DEBUG_PREFIX}LOGS`, JSON.stringify(logs));
}

// Rekam informasi halaman saat ini
debugLog('Script loaded', { url: window.location.href, title: document.title });

// Variable global untuk status bel otomatis
let belOtomatisAktif = true;

// Dapatkan CSRF token dari meta tag Laravel
const getCsrfToken = () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    debugLog('CSRF Token retrieved', { hasToken: !!token });
    return token;
};

// Inisialisasi konteks audio untuk mengatasi pembatasan autoplay browser
(function() {
    window.AudioContext = window.AudioContext || window.webkitAudioContext;
    if (!window.AudioContext) {
        console.warn('AudioContext tidak didukung di browser ini. Suara bel mungkin tidak berfungsi.');
    }
})();

// Fungsi untuk memuat dan memainkan bel
async function loadAndPlayBell() {
    debugLog('Memeriksa jadwal bel', { time: new Date().toLocaleTimeString() });
    
    // Status untuk menandai bahwa fungsi ini sedang berjalan
    window.belCheckInProgress = true;
    localStorage.setItem(`${DEBUG_PREFIX}LAST_CHECK`, new Date().toISOString());
    
    // Ambil data bel yang aktif di waktu saat ini
    try {
        // Tambahkan timestamp untuk menghindari caching
        const timestamp = new Date().getTime();
        const url = `/api/bel/check-current-time?_=${timestamp}`;
        
        debugLog(`Fetching bell data`, { url });
        
        const csrfToken = getCsrfToken();
        
        // Gunakan XMLHttpRequest untuk kompatibilitas yang lebih baik
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        }
        
        xhr.withCredentials = true; // Sertakan cookie
        
        // Buat Promise untuk menangani respons
        const response = await new Promise((resolve, reject) => {
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        resolve(data);
                    } catch (e) {
                        reject(new Error(`Failed to parse response: ${e.message}`));
                    }
                } else {
                    reject(new Error(`HTTP error! status: ${xhr.status}`));
                }
            };
            
            xhr.onerror = function() {
                reject(new Error('Network error occurred'));
            };
            
            xhr.ontimeout = function() {
                reject(new Error('Request timed out'));
            };
            
            // Kirim permintaan
            xhr.send();
        });
        
        debugLog('Respons API bel diterima', response);
        
        // Simpan respons terakhir untuk debugging
        localStorage.setItem(`${DEBUG_PREFIX}LAST_RESPONSE`, JSON.stringify(response));

        // Jika ada bel yang harus dibunyikan
        if (response.shouldRing && response.bell) {
            debugLog('Membunyikan bel', { 
                nama: response.bell.nama, 
                waktu: response.bell.waktu,
                audio: response.bell.audio_file 
            });

            // Catat di localStorage untuk tracking
            localStorage.setItem(`${DEBUG_PREFIX}LAST_BELL_PLAYED`, JSON.stringify({
                time: new Date().toISOString(),
                bell: response.bell,
                page: window.location.pathname
            }));

            // Tampilkan notifikasi
            showBellNotification(response.bell.nama);

            // Mainkan file audio jika ada
            if (response.bell.audio_file) {
                debugLog('Memutar file audio', { file: response.bell.audio_file });
                await playBellAudio(response.bell.audio_file, response.bell.tipe);
            } else {
                debugLog('Tidak ada file audio, menggunakan beep sederhana');
                playSimpleBeep();
            }
            
            // Tambahkan log saat selesai
            debugLog('Pemutaran bel selesai');
        } else {
            debugLog('Tidak ada bel yang perlu dibunyikan pada waktu ini');
        }
    } catch (error) {
        debugLog('Error checking bell schedule', { error: error.message });
        console.error('Error checking bell schedule:', error);
    } finally {
        // Tandai bahwa pemeriksaan bel sudah selesai
        window.belCheckInProgress = false;
    }
}

// Mainkan audio bel dengan multiple fallback strategy
async function playBellAudio(audioFilePath, jenisBel = 'reguler') {
    debugLog('Memulai pemutaran audio bel', { audioFilePath, jenisBel });
    
    try {
        // Jika ada file audio, coba putar
        if (audioFilePath) {
            debugLog(`Memuat file suara bel`, { file: audioFilePath });
            
            // Simpan info untuk debugging
            localStorage.setItem(`${DEBUG_PREFIX}PLAYING_AUDIO`, audioFilePath);
            
            // Pendekatan 1: Native Audio API
            try {
                debugLog('Mencoba memutar dengan Native Audio API');
                const audio = new Audio();
                
                // Tingkatkan keberhasilan dengan event listener
                audio.addEventListener('canplaythrough', () => debugLog('Audio ready to play'));
                audio.addEventListener('playing', () => debugLog('Audio mulai diputar'));
                audio.addEventListener('ended', () => debugLog('Audio selesai diputar'));
                audio.addEventListener('error', (e) => debugLog('Audio error', { code: e.target.error?.code }));
                
                // Atur properti audio
                audio.autoplay = false; // Jangan auto-play dulu
                audio.preload = 'auto';
                audio.volume = 1.0; // Volume maksimal
                audio.src = audioFilePath + '?t=' + new Date().getTime(); // Cache busting
                
                // Load dulu
                await new Promise((resolve) => {
                    audio.load();
                    setTimeout(resolve, 800); // Berikan waktu untuk memuat
                });
                
                // Coba putar
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    await playPromise;
                    debugLog('Audio bel berhasil dimainkan dengan Native Audio API');
                    return;
                }
            } catch (audioError) {
                debugLog('Native Audio API failed', { error: audioError.message });
            }
            
            // Pendekatan 2: Web Audio API
            try {
                debugLog('Mencoba memutar dengan Web Audio API');
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                
                // Pastikan context aktif
                if (audioContext.state === 'suspended') {
                    await audioContext.resume();
                }
                
                // Load audio file as ArrayBuffer
                const response = await fetch(audioFilePath + '?t=' + new Date().getTime());
                const arrayBuffer = await response.arrayBuffer();
                
                // Decode audio data
                const audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
                
                // Buat source node dan putar
                const source = audioContext.createBufferSource();
                source.buffer = audioBuffer;
                source.connect(audioContext.destination);
                source.start(0);
                
                // Wait for playback to complete
                await new Promise(resolve => {
                    source.onended = resolve;
                    // Fallback jika onended tidak terpanggil
                    setTimeout(resolve, audioBuffer.duration * 1000 + 500);
                });
                
                debugLog('Audio bel berhasil dimainkan dengan Web Audio API');
                return;
            } catch (webAudioError) {
                debugLog('Web Audio API failed', { error: webAudioError.message });
            }
            
            // Pendekatan 3: Iframe fallback
            try {
                debugLog('Mencoba memutar dengan iframe fallback');
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                
                // Tambahkan iframe ke dokumen
                document.body.appendChild(iframe);
                
                // Akses contentWindow dan buat audio element di iframe
                const iframeDoc = iframe.contentWindow.document;
                iframeDoc.open();
                iframeDoc.write(`
                    <!DOCTYPE html>
                    <html>
                    <body>
                        <audio autoplay id="bell-audio">
                            <source src="${audioFilePath}?t=${new Date().getTime()}" type="audio/mpeg">
                        </audio>
                        <script>
                            document.getElementById('bell-audio').play();
                        </script>
                    </body>
                    </html>
                `);
                iframeDoc.close();
                
                // Tunggu beberapa detik lalu hapus iframe
                await new Promise(resolve => setTimeout(resolve, 5000));
                if (document.body.contains(iframe)) {
                    document.body.removeChild(iframe);
                }
                
                debugLog('Audio bel seharusnya dimainkan dengan iframe fallback');
                return;
            } catch (iframeError) {
                debugLog('Iframe fallback failed', { error: iframeError.message });
            }
        }
        
        // Fallback terakhir: beep sederhana
        debugLog('Menggunakan beep sederhana sebagai fallback terakhir');
        await playSimpleBeep();
        
    } catch (error) {
        debugLog('Semua metode audio gagal', { error: error.message });
        // Satu lagi fallback yang lebih sederhana
        await playSimpleBeep();
    } finally {
        localStorage.removeItem(`${DEBUG_PREFIX}PLAYING_AUDIO`);
    }
}

// Fallback beep sederhana
async function playSimpleBeep() {
    debugLog('Playing simple beep fallback');
    
    try {
        // Menggunakan Web Audio API untuk beep
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        // Pastikan context active
        if (audioContext.state === 'suspended') {
            await audioContext.resume();
        }
        
        // Buat beep dengan pola bel sekolah yang lebih kompleks
        await playBeepPattern(audioContext, [
            { freq: 880, duration: 300, gain: 0.4 },
            { freq: 0, duration: 100, gain: 0 },
            { freq: 880, duration: 300, gain: 0.4 },
            { freq: 0, duration: 100, gain: 0 },
            { freq: 880, duration: 600, gain: 0.4 },
        ]);
        
        debugLog('Complex bell pattern played');
    } catch (error) {
        debugLog('Complex beep pattern failed', { error: error.message });
        
        // Ultimate fallback: alert sederhana
        try {
            // Gunakan beep yang lebih sederhana
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.frequency.value = 800;
            osc.type = 'sine';
            
            gain.gain.value = 0.3;
            
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.8);
            
            debugLog('Simple beep played as ultimate fallback');
        } catch (e) {
            debugLog('All audio methods failed');
            
            // Jika dalam mode debug, tampilkan alert
            if (DEBUG) {
                alert('⚠️ BEL SEKOLAH! Audio tidak dapat diputar.');
            }
        }
    }
}

// Fungsi untuk memainkan pola beep
function playBeepPattern(audioContext, pattern) {
    return new Promise((resolve) => {
        let time = audioContext.currentTime;
        
        pattern.forEach(tone => {
            if (tone.freq > 0) {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = tone.freq;
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0, time);
                gainNode.gain.linearRampToValueAtTime(tone.gain, time + 0.01);
                gainNode.gain.exponentialRampToValueAtTime(0.01, time + (tone.duration / 1000));
                
                oscillator.start(time);
                oscillator.stop(time + (tone.duration / 1000) + 0.1);
            }
            
            time += (tone.duration / 1000);
        });
        
        // Resolve setelah semua beep selesai
        setTimeout(resolve, pattern.reduce((acc, tone) => acc + tone.duration, 0) + 200);
    });
}

// Tampilkan notifikasi bel
function showBellNotification(bellName) {
    debugLog('Showing bell notification', { bellName });
    
    // Catat notifikasi untuk debugging
    localStorage.setItem(`${DEBUG_PREFIX}LAST_NOTIFICATION`, JSON.stringify({
        time: new Date().toISOString(),
        bellName,
        page: window.location.pathname
    }));
    
    // Jika browser mendukung notifikasi
    if ('Notification' in window) {
        // Periksa apakah izin notifikasi telah diberikan
        if (Notification.permission === 'granted') {
            const notification = new Notification('Bel Sekolah', {
                body: `Bel "${bellName}" telah dibunyikan`,
                icon: '/images/bell-icon.png',
                badge: '/images/bell-icon.png',
                timestamp: Date.now(),
                vibrate: [200, 100, 200]
            });
            
            notification.onclick = function() {
                window.focus();
                this.close();
            };
            
            debugLog('System notification shown');
        } else if (Notification.permission !== 'denied') {
            // Jika izin belum ditentukan, minta izin
            Notification.requestPermission().then(permission => {
                debugLog('Notification permission response', { permission });
                if (permission === 'granted') {
                    // Coba lagi setelah mendapat izin
                    showBellNotification(bellName);
                }
            });
        }
    } else {
        debugLog('System notifications not supported');
    }
    
    // Selalu tampilkan notifikasi dalam aplikasi
    showInAppNotification(bellName);
}

// Tampilkan notifikasi di dalam aplikasi
function showInAppNotification(bellName) {
    debugLog('Showing in-app notification', { bellName });
    
    try {
        // Hapus notifikasi yang ada jika ada
        const existingNotification = document.getElementById('bell-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Buat elemen notifikasi yang lebih mencolok
        const notification = document.createElement('div');
        notification.id = 'bell-notification';
        
        // Gaya dasar
        const baseStyle = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 320px;
            max-width: 500px;
            background: rgba(0, 128, 0, 0.9);
            color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
            font-family: Arial, sans-serif;
            animation: bellNotification 0.5s ease-in-out;
        `;
        
        // Tambahkan animasi
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bellNotification {
                0% { transform: translateY(-100px); opacity: 0; }
                100% { transform: translateY(0); opacity: 1; }
            }
            @keyframes bellRinging {
                0% { transform: rotate(-5deg); }
                25% { transform: rotate(5deg); }
                50% { transform: rotate(-5deg); }
                75% { transform: rotate(5deg); }
                100% { transform: rotate(0); }
            }
        `;
        document.head.appendChild(style);
        
        // Atur gaya
        notification.style.cssText = baseStyle;
        
        // Isi dengan konten yang lebih menarik
        notification.innerHTML = `
            <div style="display: flex; align-items: center;">
                <div style="margin-right: 15px; animation: bellRinging 0.5s infinite;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
                    </svg>
                </div>
                <div style="flex: 1; font-weight: bold; font-size: 16px;">
                    Bel Sekolah
                    <div style="font-weight: normal; font-size: 14px; margin-top: 5px;">
                        Bel "${bellName}" telah dibunyikan
                    </div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; color: white; cursor: pointer; font-size: 18px;">
                    &times;
                </button>
            </div>
        `;

        // Tambahkan ke halaman
        document.body.appendChild(notification);

        // Buat suara notifikasi sendiri untuk memperkuat kehadiran notifikasi
        try {
            const notifContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = notifContext.createOscillator();
            const gainNode = notifContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(notifContext.destination);
            
            oscillator.frequency.value = 1200;
            gainNode.gain.value = 0.1; // Volume kecil agar tidak mengganggu bel utama
            
            oscillator.start(notifContext.currentTime);
            oscillator.stop(notifContext.currentTime + 0.2);
        } catch (e) {
            // Abaikan jika gagal
        }

        // Auto-hide setelah 8 detik
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 500);
            }
        }, 8000);
    } catch (error) {
        debugLog('Error showing in-app notification', { error: error.message });
    }
}

// Inisialisasi Sistem Bel Otomatis Global
(function() {
    // Catat waktu ketika skrip pertama kali dimuat
    const scriptLoadTime = new Date().toISOString();
    localStorage.setItem(`${DEBUG_PREFIX}SCRIPT_LOADED`, scriptLoadTime);
    localStorage.setItem(`${DEBUG_PREFIX}SCRIPT_LOADED_URL`, window.location.href);
    
    // Tambahkan elemen untuk konfirmasi visual di sudut kanan bawah
    function addDebugIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'bel-auto-debug-indicator';
        indicator.style.cssText = `
            position: fixed;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: #00ff00;
            padding: 5px;
            border-radius: 3px;
            font-size: 10px;
            z-index: 9999;
            cursor: pointer;
        `;
        indicator.innerHTML = 'BEL-AUTO AKTIF';
        indicator.title = 'Sistem Bel Otomatis aktif dan berjalan';
        
        indicator.addEventListener('click', () => {
            alert('Debug Bel Otomatis:\n' + 
                  '- Terakhir dicek: ' + (localStorage.getItem(`${DEBUG_PREFIX}LAST_CHECK`) || 'belum') + '\n' +
                  '- Terakhir bel: ' + (localStorage.getItem(`${DEBUG_PREFIX}LAST_BELL_PLAYED`) || 'belum ada'));
        });
        
        document.body.appendChild(indicator);
        return indicator;
    }
    
    // Inisialisasi ketika DOM sudah dimuat
    document.addEventListener('DOMContentLoaded', function() {
        debugLog('DOM loaded - initializing automatic bell system');
        
        // Tambahkan indikator visual jika dalam mode debug
        if (DEBUG) {
            setTimeout(addDebugIndicator, 1000);
        }
        
        // Inisialisasi audio untuk mengatasi pembatasan autoplay browser
        document.addEventListener('click', function initAudio() {
            debugLog('User interaction detected - initializing audio context');
            
            try {
                if (window.AudioContext || window.webkitAudioContext) {
                    const tempContext = new (window.AudioContext || window.webkitAudioContext)();
                    if (tempContext.state === 'suspended') {
                        tempContext.resume().then(() => {
                            debugLog('AudioContext resumed on user interaction');
                        });
                    }
                }
            } catch (e) {
                debugLog('Error initializing AudioContext', { error: e.message });
            }
            
            document.removeEventListener('click', initAudio);
        });
        
        // Periksa bel saat halaman dimuat (dengan penundaan)
        debugLog('Scheduling initial bell check');
        const initialCheckDelay = 2000; // 2 detik
        const initialCheck = setTimeout(() => {
            debugLog('Running initial bell check');
            loadAndPlayBell();
        }, initialCheckDelay);
        
        // Periksa bel secara berkala (setiap 30 detik)
        debugLog('Setting up bell check interval');
        const intervalTime = 30000; // 30 detik
        
        // Gunakan setInterval untuk pengecekan bel reguler
        const bellCheckInterval = setInterval(() => {
            // Verifikasi bahwa fitur aktif
            if (belOtomatisAktif) {
                // Hindari overlapping jika pengecekan sebelumnya masih berjalan
                if (!window.belCheckInProgress) {
                    debugLog('Running scheduled bell check');
                    loadAndPlayBell();
                } else {
                    debugLog('Previous bell check still in progress, skipping this iteration');
                }
            } else {
                debugLog('Automatic bell check is disabled');
            }
        }, intervalTime);
        
        // Gunakan Service Worker untuk pengecekan bel lebih handal di latar belakang
        if ('serviceWorker' in navigator) {
            try {
                // Daftarkan service worker untuk membantu sistem bel
                navigator.serviceWorker.register('/bel-service-worker.js')
                    .then(registration => {
                        debugLog('Service Worker registered', { 
                            scope: registration.scope 
                        });
                        
                        // Kirim pesan ke service worker bahwa halaman telah dimuat
                        if (registration.active) {
                            registration.active.postMessage({
                                action: 'pageLoaded',
                                url: window.location.href,
                                time: new Date().toISOString()
                            });
                        }
                    })
                    .catch(error => {
                        debugLog('Service Worker registration failed', { error: error.message });
                    });
                    
                // Tambahkan listener untuk pesan dari service worker
                navigator.serviceWorker.addEventListener('message', event => {
                    debugLog('Message from Service Worker', event.data);
                    
                    // Tanggapi pesan dari service worker
                    if (event.data.action === 'checkBell') {
                        loadAndPlayBell();
                    }
                });
                
            } catch (e) {
                debugLog('Error with Service Worker', { error: e.message });
            }
        }
        
        // Catat interval ID di localStorage untuk debugging
        localStorage.setItem(`${DEBUG_PREFIX}INTERVAL_ACTIVE`, 'true');
        
        // Pastikan interval dibersihkan saat halaman unload
        window.addEventListener('beforeunload', () => {
            clearInterval(bellCheckInterval);
            clearTimeout(initialCheck);
            localStorage.setItem(`${DEBUG_PREFIX}INTERVAL_ACTIVE`, 'false');
        });
        
        // Catat status bahwa sistem sudah diinisialisasi
        localStorage.setItem(`${DEBUG_PREFIX}INITIALIZED`, 'true');
    });
})();

// Export variabel global
window.belAutoCheck = {
    // Pengaturan
    setActive: function(active) {
        belOtomatisAktif = active;
        debugLog(`Bel otomatis ${active ? 'diaktifkan' : 'dinonaktifkan'}`);
        localStorage.setItem(`${DEBUG_PREFIX}ACTIVE`, active ? 'true' : 'false');
        return belOtomatisAktif;
    },
    isActive: function() {
        return belOtomatisAktif;
    },
    
    // Aksi manual
    checkNow: function() {
        debugLog('Manual check triggered');
        return loadAndPlayBell();
    },
    playTestBeep: function() {
        debugLog('Test beep triggered');
        return playSimpleBeep();
    },
    
    // Debugging
    debug: {
        getLogs: function() {
            return JSON.parse(localStorage.getItem(`${DEBUG_PREFIX}LOGS`) || '[]');
        },
        clearLogs: function() {
            localStorage.removeItem(`${DEBUG_PREFIX}LOGS`);
        },
        showStatus: function() {
            const statusInfo = {
                active: belOtomatisAktif,
                scriptLoaded: localStorage.getItem(`${DEBUG_PREFIX}SCRIPT_LOADED`),
                lastCheck: localStorage.getItem(`${DEBUG_PREFIX}LAST_CHECK`),
                lastBell: localStorage.getItem(`${DEBUG_PREFIX}LAST_BELL_PLAYED`),
                lastResponse: localStorage.getItem(`${DEBUG_PREFIX}LAST_RESPONSE`)
            };
            
            console.table(statusInfo);
            return statusInfo;
        },
        forcePlay: function(url) {
            debugLog('Force playing audio', { url });
            return playBellAudio(url || '/storage/sounds/bell-default.mp3');
        }
    }
};
