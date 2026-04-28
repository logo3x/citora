<x-filament-panels::page>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/js/citora-push.js"></script>

    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">

        <div id="push-status" class="mb-6 p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <p class="text-sm text-gray-600 dark:text-gray-300">Comprobando estado…</p>
        </div>

        <div class="space-y-4">
            <button
                type="button"
                id="btn-enable"
                class="hidden w-full px-4 py-3 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-semibold transition"
            >
                🔔 Activar notificaciones en este dispositivo
            </button>

            <button
                type="button"
                id="btn-disable"
                class="hidden w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 font-semibold transition"
            >
                Desactivar notificaciones en este dispositivo
            </button>

            <button
                type="button"
                id="btn-test"
                class="hidden w-full px-4 py-3 rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50 font-semibold transition"
            >
                Enviar notificación de prueba
            </button>
        </div>

        <div id="push-msg" class="hidden mt-4 p-3 rounded-lg text-sm"></div>

        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400 space-y-2">
            <p><strong>Cómo funciona:</strong></p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li>Activas las notificaciones una vez por dispositivo (Chrome, Edge, Firefox, Safari).</li>
                <li>Recibirás alertas instantáneas cuando llegue una cita nueva, se cancele o se reprograme — incluso con el navegador en segundo plano.</li>
                <li>Funciona como complemento al WhatsApp y al email — los 3 canales se mantienen activos.</li>
                <li>Para desactivar, vuelve aquí o ajusta los permisos del navegador.</li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const $status = document.getElementById('push-status');
            const $enable = document.getElementById('btn-enable');
            const $disable = document.getElementById('btn-disable');
            const $test = document.getElementById('btn-test');
            const $msg = document.getElementById('push-msg');

            function show(message, kind) {
                $msg.textContent = message;
                $msg.classList.remove('hidden', 'bg-green-50', 'text-green-800', 'bg-red-50', 'text-red-800', 'bg-blue-50', 'text-blue-800');
                if (kind === 'success') {
                    $msg.classList.add('bg-green-50', 'text-green-800');
                } else if (kind === 'error') {
                    $msg.classList.add('bg-red-50', 'text-red-800');
                } else {
                    $msg.classList.add('bg-blue-50', 'text-blue-800');
                }
            }

            async function refresh() {
                const s = await window.CitoraPush.status();

                if (!s.supported) {
                    $status.innerHTML = '<p class="text-sm text-red-600">⚠️ Tu navegador no soporta notificaciones push.</p>';
                    return;
                }

                if (s.permission === 'denied') {
                    $status.innerHTML = '<p class="text-sm text-red-600">⚠️ Notificaciones <strong>bloqueadas</strong> en este navegador. Ve a configuración del sitio para permitirlas.</p>';
                    return;
                }

                if (s.subscribed) {
                    $status.innerHTML = '<p class="text-sm text-green-700">✅ <strong>Notificaciones activas</strong> en este dispositivo.</p>';
                    $enable.classList.add('hidden');
                    $disable.classList.remove('hidden');
                    $test.classList.remove('hidden');
                } else {
                    $status.innerHTML = '<p class="text-sm text-gray-600">Las notificaciones <strong>no están activas</strong> en este dispositivo.</p>';
                    $enable.classList.remove('hidden');
                    $disable.classList.add('hidden');
                    $test.classList.add('hidden');
                }
            }

            $enable.addEventListener('click', async () => {
                $enable.disabled = true;
                $enable.textContent = 'Activando…';
                try {
                    await window.CitoraPush.enable();
                    show('🔔 ¡Notificaciones activadas correctamente!', 'success');
                    await refresh();
                } catch (e) {
                    show('Error: ' + e.message, 'error');
                } finally {
                    $enable.disabled = false;
                    $enable.textContent = '🔔 Activar notificaciones en este dispositivo';
                }
            });

            $disable.addEventListener('click', async () => {
                $disable.disabled = true;
                try {
                    await window.CitoraPush.disable();
                    show('Notificaciones desactivadas en este dispositivo.', 'info');
                    await refresh();
                } catch (e) {
                    show('Error: ' + e.message, 'error');
                } finally {
                    $disable.disabled = false;
                }
            });

            $test.addEventListener('click', async () => {
                $test.disabled = true;
                $test.textContent = 'Enviando…';
                try {
                    const r = await window.CitoraPush.test();
                    show('Enviadas a ' + (r.sent || 0) + ' dispositivo(s). Revisa la notificación del navegador.', 'success');
                } catch (e) {
                    show('Error: ' + e.message, 'error');
                } finally {
                    $test.disabled = false;
                    $test.textContent = 'Enviar notificación de prueba';
                }
            });

            refresh();
        });
    </script>
</x-filament-panels::page>
