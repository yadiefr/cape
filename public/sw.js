// Service Worker untuk PWA
const CACHE_NAME = 'smk-pgri-v1';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/images/icon-192x192.png',
  '/images/icon-512x512.png'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Return cached version or fetch from network
        return response || fetch(event.request);
      }
    )
  );
});
