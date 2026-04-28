// Citora Service Worker - Web Push Notifications
const CACHE_VERSION = 'citora-sw-v1';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', (event) => {
    if (!event.data) {
        return;
    }

    let payload = {};
    try {
        payload = event.data.json();
    } catch (_) {
        payload = { title: 'Citora', body: event.data.text() };
    }

    const title = payload.title || 'Citora';
    const options = {
        body: payload.body || '',
        icon: payload.icon || '/images/logo-light.png',
        badge: payload.badge || '/images/logo-light.png',
        tag: payload.tag || 'citora-' + Date.now(),
        renotify: true,
        requireInteraction: payload.requireInteraction || false,
        data: { url: payload.url || '/admin' },
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = (event.notification.data && event.notification.data.url) || '/admin';

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (self.clients.openWindow) {
                return self.clients.openWindow(targetUrl);
            }
            return null;
        })
    );
});
