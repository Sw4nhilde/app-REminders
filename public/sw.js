/*
===============================================================================
📱 PWA - SERVICE WORKER
===============================================================================
Service Worker untuk Progressive Web App functionality.

Fitur:
1. Static Asset Caching (CSS, JS, images)
2. Offline Fallback Page
3. Runtime Caching Strategies:
   - Navigation: Network-first with offline fallback
   - Static assets: Cache-first
   - API calls: Network-first with cache fallback

4. Cache Management:
   - Auto cleanup old caches
   - Version-based cache invalidation

5. Background Sync (future)
6. Push Notifications (future)

File ini di-register di layouts/app.blade.php
===============================================================================
*/

const CACHE = 'reminderapps-v2';
const ASSETS = ['/', '/dashboard', '/offline.html'];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE).then(cache => cache.addAll(ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(key => key !== CACHE)
          .map(key => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

self.addEventListener('fetch', event => {
  const req = event.request;

  // Only handle same-origin GET requests
  if (req.method !== 'GET') return;
  const url = new URL(req.url);
  if (url.origin !== self.location.origin) return;

  // Navigation requests: network-first, fallback to offline page
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req)
        .then(res => {
          // Cache the successful response for future
          const copy = res.clone();
          caches.open(CACHE).then(c => c.put(req, copy)).catch(() => {});
          return res;
        })
        .catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // Static assets: cache-first
  const destination = req.destination;
  const cacheFirstTypes = ['style', 'script', 'image', 'font'];
  if (cacheFirstTypes.includes(destination) || /\.(css|js|png|jpg|jpeg|svg|webp|woff2?)$/i.test(url.pathname)) {
    event.respondWith(
      caches.match(req).then(cached => {
        if (cached) return cached;
        return fetch(req).then(res => {
          const copy = res.clone();
          caches.open(CACHE).then(c => c.put(req, copy)).catch(() => {});
          return res;
        });
      })
    );
    return;
  }

  // Default: network-first with cache fallback
  event.respondWith(
    fetch(req)
      .then(res => {
        const copy = res.clone();
        caches.open(CACHE).then(c => c.put(req, copy)).catch(() => {});
        return res;
      })
      .catch(() => caches.match(req))
  );
});
