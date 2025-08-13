// Default bell sound
var context = new (window.AudioContext || window.webkitAudioContext)();

// Play default bell sound
function playDefaultBell() {
  // Create oscillator
  var osc = context.createOscillator();
  var gain = context.createGain();
  
  // Connect nodes
  osc.connect(gain);
  gain.connect(context.destination);
  
  // Set parameters
  osc.type = 'sine';
  osc.frequency.value = 800;
  gain.gain.value = 0.5;
  
  // Schedule envelope
  var now = context.currentTime;
  gain.gain.setValueAtTime(0, now);
  gain.gain.linearRampToValueAtTime(0.5, now + 0.1);
  gain.gain.exponentialRampToValueAtTime(0.001, now + 1.0);
  
  // Start and stop
  osc.start(now);
  osc.stop(now + 1.0);
  
  console.log("Default bell sound played");
  
  // Return a success message after the sound is finished
  return new Promise(resolve => {
    setTimeout(() => resolve("Default bell sound played successfully"), 1100);
  });
}
