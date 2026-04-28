// Citora Web Push subscription helper
// Exposes window.CitoraPush with: status(), enable(), disable(), test()
(function () {
    'use strict';

    const VAPID_KEY_URL = '/push/vapid-key';
    const SUBSCRIBE_URL = '/push/subscribe';
    const UNSUBSCRIBE_URL = '/push/unsubscribe';
    const TEST_URL = '/push/test';
    const SW_PATH = '/sw.js';

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

    async function postJson(url, body) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            credentials: 'same-origin',
            body: JSON.stringify(body),
        });
        if (!res.ok) {
            throw new Error('HTTP ' + res.status);
        }
        return res.json();
    }

    async function getRegistration() {
        if (!('serviceWorker' in navigator)) {
            throw new Error('Tu navegador no soporta Service Workers.');
        }
        let reg = await navigator.serviceWorker.getRegistration(SW_PATH);
        if (!reg) {
            reg = await navigator.serviceWorker.register(SW_PATH, { scope: '/' });
        }
        await navigator.serviceWorker.ready;
        return reg;
    }

    const CitoraPush = {
        async supported() {
            return 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
        },

        async status() {
            if (!(await this.supported())) {
                return { supported: false, permission: 'unsupported', subscribed: false };
            }
            const reg = await navigator.serviceWorker.getRegistration(SW_PATH);
            const sub = reg ? await reg.pushManager.getSubscription() : null;
            return {
                supported: true,
                permission: Notification.permission,
                subscribed: !!sub,
            };
        },

        async enable() {
            if (!(await this.supported())) {
                throw new Error('Tu navegador no soporta notificaciones push.');
            }

            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                throw new Error('Debes permitir las notificaciones en el navegador.');
            }

            const reg = await getRegistration();

            const keyResp = await fetch(VAPID_KEY_URL, { credentials: 'same-origin' });
            const { key } = await keyResp.json();
            if (!key) {
                throw new Error('Servidor sin VAPID key configurada.');
            }

            const sub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(key),
            });

            const subJson = sub.toJSON();
            await postJson(SUBSCRIBE_URL, {
                endpoint: subJson.endpoint,
                keys: subJson.keys,
                contentEncoding: 'aesgcm',
            });

            return true;
        },

        async disable() {
            const reg = await navigator.serviceWorker.getRegistration(SW_PATH);
            if (!reg) {
                return false;
            }
            const sub = await reg.pushManager.getSubscription();
            if (!sub) {
                return false;
            }

            await postJson(UNSUBSCRIBE_URL, { endpoint: sub.endpoint });
            await sub.unsubscribe();
            return true;
        },

        async test() {
            const res = await postJson(TEST_URL, {});
            return res;
        },
    };

    window.CitoraPush = CitoraPush;
})();
