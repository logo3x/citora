// Citora · Tour guiado del panel admin
// Usa Driver.js (https://driverjs.com) cargado vía CDN
(function () {
    'use strict';

    function csrf() {
        const m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.content : '';
    }

    async function markComplete() {
        try {
            await fetch('/admin/tutorial/complete', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
        } catch (e) { /* ignore */ }
    }

    function buildSteps() {
        return [
            {
                popover: {
                    title: '👋 ¡Bienvenido a Citora!',
                    description:
                        'Te enseño en 90 segundos cómo usar tu panel.<br><br>' +
                        '<strong>Puedes saltar el tour</strong> con la X o seguir con "Siguiente".',
                    side: 'over', align: 'center',
                },
            },
            {
                element: 'aside, [class*="fi-sidebar"]',
                popover: {
                    title: '🧭 Menú lateral',
                    description: 'Aquí están todos los módulos. Vamos a recorrerlos.',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="/admin"][href*="dashboard"], a[href$="/admin"]',
                popover: {
                    title: '📊 Dashboard',
                    description:
                        'Tu vista general: citas de hoy, ingresos, próximas citas y gráficos. ' +
                        'Lo ves apenas inicias sesión.',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="calendario"]',
                popover: {
                    title: '📅 Calendario',
                    description:
                        'Tu agenda visual. Ve por día, semana o mes. Toca un slot vacío para crear cita rápido.',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="appointments"]',
                popover: {
                    title: '📝 Citas',
                    description:
                        'Lista completa con filtros, exportes a CSV y acciones rápidas (confirmar, completar, cancelar).',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="services"]',
                popover: {
                    title: '✂️ Servicios',
                    description:
                        '<strong>Importante:</strong> al crear un servicio, asígnale al menos UN empleado que pueda realizarlo. ' +
                        'Si no, no aparecerá disponible para reservas.',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="employees"]',
                popover: {
                    title: '👥 Empleados',
                    description:
                        'Tu equipo. Cada empleado tiene foto, horarios propios (opcional) y la lista de servicios que sabe hacer.',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="customers"]',
                popover: {
                    title: '🧑 Clientes',
                    description: 'Lista de quienes te han reservado. Aquí ves su historial y datos de contacto.',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="my-business"], a[href*="mi-negocio"]',
                popover: {
                    title: '🏢 Mi negocio',
                    description:
                        'Configuración del negocio: logo, banner, dirección, teléfono, horarios de atención y ' +
                        'políticas de cancelación. <strong>Aquí también puedes volver a ver este tutorial.</strong>',
                    side: 'right', align: 'start',
                },
            },
            {
                popover: {
                    title: '🔗 Tu link público',
                    description:
                        'Tu negocio ya tiene una URL pública del tipo <code>citora.com.co/tu-slug</code>. ' +
                        'Compártela en Instagram, WhatsApp o donde quieras. Tus clientes reservan ahí sin instalar nada.',
                    side: 'over', align: 'center',
                },
            },
            {
                popover: {
                    title: '🎉 ¡Listo!',
                    description:
                        'Ya conoces los módulos básicos. Si quieres volver a ver este tour, ' +
                        've a <strong>Mi negocio</strong> y haz click en "Ver tutorial de nuevo".<br><br>' +
                        '<strong>Tu primera tarea:</strong> ve a <em>Servicios</em>, edita uno y asígnale empleados.',
                    side: 'over', align: 'center',
                },
            },
        ];
    }

    function loadDriverIfNeeded() {
        if (window.driver) return Promise.resolve();
        return new Promise((resolve, reject) => {
            const css = document.createElement('link');
            css.rel = 'stylesheet';
            css.href = 'https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css';
            document.head.appendChild(css);

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js';
            script.onload = () => resolve();
            script.onerror = (e) => reject(new Error('Driver.js no se pudo cargar'));
            document.head.appendChild(script);
        });
    }

    async function startTour() {
        await loadDriverIfNeeded();
        const driverObj = window.driver.js.driver({
            showProgress: true,
            allowClose: true,
            popoverClass: 'citora-driver',
            nextBtnText: 'Siguiente →',
            prevBtnText: '← Anterior',
            doneBtnText: '✓ Listo',
            steps: buildSteps(),
            onDestroyed: () => { markComplete(); },
        });
        driverObj.drive();
    }

    function autoStartIfFirstTime() {
        if (!window.CitoraTutorial || window.CitoraTutorial.completed) return;
        // Pequeño retraso para que Filament termine de pintar el sidebar
        setTimeout(startTour, 800);
    }

    window.CitoraTour = {
        start: startTour,
        async reset() {
            try {
                await fetch('/admin/tutorial/reset', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                startTour();
            } catch (e) {
                alert('No se pudo reiniciar el tutorial: ' + e.message);
            }
        },
    };

    document.addEventListener('DOMContentLoaded', autoStartIfFirstTime);
})();
