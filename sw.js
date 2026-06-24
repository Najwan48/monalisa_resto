self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', (e) => e.waitUntil(self.clients.claim()));
self.addEventListener('fetch', (e) => {
    const url = new URL(e.request.url);
    if (url.origin === self.location.origin) {
        e.respondWith(fetch(e.request, { cache: 'no-store' }));
    }
});
