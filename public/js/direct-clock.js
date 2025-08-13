// This script updates the clock on the bell management page

// Immediately execute this function when the page loads
(function() {
    console.log('DIRECT CLOCK SCRIPT LOADED');
    
    // Function to update the clock
    function updateRealTimeClock() {
        const clockElement = document.getElementById('clock-time');
        const dayElement = document.getElementById('current-day');
        
        if (!clockElement || !dayElement) {
            console.error('Clock elements not found');
            return;
        }
        
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        
        // Update time display
        clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        
        // Update day display in Indonesian
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayName = dayNames[now.getDay()];
        dayElement.textContent = dayName;
        
        // Log for debugging
        if (now.getSeconds() === 0) {
            console.log(`Clock updated: ${hours}:${minutes}:${seconds}`);
        }
    }
    
    // Update the clock immediately
    updateRealTimeClock();
    
    // Then update every second
    setInterval(updateRealTimeClock, 1000);
    
    // Show notification that clock is running
    const debugInfo = document.getElementById('debug-info');
    const debugMessage = document.getElementById('debug-message');
    if (debugInfo && debugMessage) {
        debugMessage.textContent = 'Direct clock script loaded and running';
        debugInfo.classList.remove('hidden');
        
        setTimeout(() => {
            const now = new Date();
            debugMessage.textContent = `Current time: ${now.toLocaleTimeString()}`;
            
            setTimeout(() => {
                debugInfo.classList.add('hidden');
            }, 5000);
        }, 2000);
    }
})();
