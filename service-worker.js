// SATU DATU SANGGUL - Service Worker
// Cache version
const CACHE_VERSION = 'v1.0.0';
const CACHE_NAME = `datu-sanggul-${CACHE_VERSION}`;

// Assets to cache immediately on install
const PRECACHE_ASSETS = [
    '/',
    '/index.html',
    '/manifest.json',
    // Add CDN resources that should be cached
    'https://cdn.tailwindcss.com',
    'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js'
];

// Cache on demand
const CACHE_ON_DEMAND = [
    // Add assets that should be cached when first requested
];

// Install event - precache essential assets
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[Service Worker] Pre-caching app shell');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => {
                // Force the waiting service worker to become the active service worker
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('[Service Worker] Pre-caching failed:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activating...');

    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        // Delete old caches
                        if (cacheName !== CACHE_NAME && cacheName.startsWith('datu-sanggul-')) {
                            console.log('[Service Worker] Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                // Claim all clients immediately
                return self.clients.claim();
            })
    );
});

// Fetch event - serve from cache, fall back to network
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip API calls - always get fresh data
    if (url.pathname.startsWith('/api/')) {
        return;
    }

    // Skip external CDN requests - let browser handle caching
    if (url.hostname !== self.location.hostname &&
        !url.hostname.includes('cdn.tailwindcss.com') &&
        !url.hostname.includes('jsdelivr.net')) {
        return;
    }

    // Network-first strategy for HTML pages
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone response before caching
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseToCache);
                    });
                    return response;
                })
                .catch(() => {
                    // If network fails, try cache
                    return caches.match(request);
                })
        );
        return;
    }

    // Cache-first strategy for static assets
    event.respondWith(
        caches.match(request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    // Return cached response
                    return cachedResponse;
                }

                // Not in cache, fetch from network
                return fetch(request)
                    .then((response) => {
                        // Check if response is valid
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }

                        // Clone response before caching
                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseToCache);
                        });

                        return response;
                    })
                    .catch((error) => {
                        console.error('[Service Worker] Fetch failed:', error);
                    });
            })
    );
});

// Background sync for offline actions (optional)
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-data') {
        event.waitUntil(syncData());
    }
});

// Push notification support (optional)
self.addEventListener('push', (event) => {
    if (event.data) {
        const data = event.data.json();
        self.registration.showNotification(data.title, {
            body: data.body,
            icon: '/assets/img/icon-192.png',
            badge: '/assets/img/icon-72.png',
            tag: 'datu-sanggul-notification',
            vibrate: [200, 100, 200]
        });
    }
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        self.clients.matchAll({ type: 'window' }).then((clientList) => {
            // If already open, focus it
            for (const client of clientList) {
                if (client.url === '/' && 'focus' in client) {
                    return client.focus();
                }
            }
            // Otherwise, open a new window
            if (self.clients.openWindow) {
                return self.clients.openWindow('/');
            }
        })
    );
});

// Helper function for background sync
function syncData() {
    return new Promise((resolve, reject) => {
        // Implement sync logic here
        // For example: sync offline form submissions
        resolve();
    });
}
