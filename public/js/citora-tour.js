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

    function injectStyles() {
        if (document.getElementById('citora-tour-styles')) return;
        const style = document.createElement('style');
        style.id = 'citora-tour-styles';
        style.textContent = `
            .driver-popover.citora-tour {
                background: linear-gradient(180deg, #FFFBEB 0%, #FFFFFF 100%);
                border: 1px solid #FDE68A;
                border-radius: 16px;
                box-shadow: 0 20px 50px rgba(217, 119, 6, 0.18), 0 4px 12px rgba(0,0,0,0.06);
                padding: 4px;
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                max-width: 380px;
            }
            .driver-popover.citora-tour .driver-popover-arrow-side-right.driver-popover-arrow,
            .driver-popover.citora-tour .driver-popover-arrow-side-left.driver-popover-arrow,
            .driver-popover.citora-tour .driver-popover-arrow-side-top.driver-popover-arrow,
            .driver-popover.citora-tour .driver-popover-arrow-side-bottom.driver-popover-arrow {
                border-color: #FDE68A;
            }
            .driver-popover.citora-tour .driver-popover-title {
                font-family: 'Poppins', system-ui, sans-serif;
                font-size: 18px;
                font-weight: 700;
                color: #0F172A;
                margin-bottom: 8px;
                line-height: 1.3;
                padding: 16px 16px 0 16px;
            }
            .driver-popover.citora-tour .driver-popover-description {
                font-size: 14px;
                line-height: 1.55;
                color: #334155;
                padding: 0 16px 12px 16px;
            }
            .driver-popover.citora-tour .driver-popover-description strong {
                color: #B45309;
                font-weight: 700;
            }
            .driver-popover.citora-tour .driver-popover-description code {
                background: #FEF3C7;
                color: #B45309;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 12px;
                font-family: 'SF Mono', 'Cascadia Code', Consolas, monospace;
            }
            .driver-popover.citora-tour .driver-popover-footer {
                padding: 8px 16px 14px 16px;
                gap: 8px;
                border-top: 1px solid rgba(217, 119, 6, 0.12);
                margin-top: 4px;
                background: rgba(255, 251, 235, 0.5);
                border-radius: 0 0 14px 14px;
            }
            .driver-popover.citora-tour .driver-popover-progress-text {
                color: #92400E;
                font-weight: 600;
                font-size: 12px;
                background: rgba(254, 243, 199, 0.6);
                padding: 4px 10px;
                border-radius: 999px;
            }
            .driver-popover.citora-tour button.driver-popover-prev-btn,
            .driver-popover.citora-tour button.driver-popover-next-btn,
            .driver-popover.citora-tour button.driver-popover-close-btn {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 13px;
                border-radius: 8px;
                padding: 8px 14px;
                border: none;
                cursor: pointer;
                transition: all 0.15s ease;
            }
            .driver-popover.citora-tour button.driver-popover-next-btn {
                background: linear-gradient(135deg, #D97706, #F59E0B);
                color: white;
                text-shadow: 0 1px 2px rgba(0,0,0,0.1);
                box-shadow: 0 2px 8px rgba(217, 119, 6, 0.25);
            }
            .driver-popover.citora-tour button.driver-popover-next-btn:hover {
                background: linear-gradient(135deg, #B45309, #D97706);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
            }
            .driver-popover.citora-tour button.driver-popover-prev-btn {
                background: white;
                color: #475569;
                border: 1px solid #E2E8F0;
            }
            .driver-popover.citora-tour button.driver-popover-prev-btn:hover {
                background: #F8FAFC;
                color: #0F172A;
            }
            .driver-popover.citora-tour button.driver-popover-close-btn {
                color: #94A3B8 !important;
                background: transparent !important;
                font-size: 20px !important;
                padding: 4px 8px !important;
                position: absolute;
                top: 8px;
                right: 8px;
            }
            .driver-popover.citora-tour button.driver-popover-close-btn:hover {
                color: #DC2626 !important;
            }
            .driver-active-element {
                box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.4),
                            0 0 0 8px rgba(245, 158, 11, 0.15) !important;
                border-radius: 8px;
                transition: box-shadow 0.3s ease;
            }
            .driver-overlay {
                background: rgba(15, 23, 42, 0.7) !important;
            }
            @media (max-width: 600px) {
                .driver-popover.citora-tour { max-width: 92vw; }
                .driver-popover.citora-tour .driver-popover-title { font-size: 16px; }
                .driver-popover.citora-tour .driver-popover-description { font-size: 13px; }
            }
        `;
        document.head.appendChild(style);
    }

    function buildSteps() {
        return [
            {
                popover: {
                    title: '👋 ¡Holaaa! Bienvenido a Citora',
                    description:
                        'Soy tu guía de bienvenida 🤓 — en menos de 2 minutos te muestro todo lo que necesitas para arrancar como un crack.<br><br>' +
                        '¿Vamos? Le das al botón naranja <strong>"Siguiente"</strong>. Si te aburres, la <strong>X</strong> es tu amiga.',
                    side: 'over', align: 'center',
                },
            },
            {
                element: 'aside, [class*="fi-sidebar"]',
                popover: {
                    title: '🧭 Tu menú lateral',
                    description:
                        'Acá vive todo. Cada ícono es un módulo. Vamos a darles un vistazo rápido para que no te pierdas 👀',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="/admin"][href$="/admin"], a[href*="dashboard"]',
                popover: {
                    title: '📊 Dashboard · Tu cabina de mando',
                    description:
                        'Aquí ves el panorama completo de tu negocio: <strong>citas de hoy</strong>, <strong>ingresos del mes</strong>, próximas citas, gráficas bonitas.<br><br>' +
                        'Es la primera pantalla cada vez que entres. Tu café matutino con datos ☕',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="calendario"]',
                popover: {
                    title: '📅 Calendario · Tu agenda visual',
                    description:
                        'Ves tus citas como en Google Calendar pero más bonito 😎<br><br>' +
                        'Cambia entre <strong>día, semana, mes o lista</strong>. ¿Tocas un slot vacío? Boom: cita rápida.',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="appointments"]',
                popover: {
                    title: '📝 Citas · Lista completa',
                    description:
                        'Todas tus citas en una tabla con filtros por estado, empleado y servicio.<br><br>' +
                        '<strong>Bonus pro tip:</strong> arriba hay un botón para <strong>exportar a CSV</strong> y abrirlo en Excel — útil para fin de mes 📈',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="services"]',
                popover: {
                    title: '✂️ Servicios · ¿Qué ofreces?',
                    description:
                        'Crea aquí cada servicio que cobras: corte, barba, color, manicura, lo que sea.<br><br>' +
                        '⚠️ <strong>Mega importante:</strong> al crear el servicio, selecciona qué empleados lo pueden hacer. Si no, NADIE podrá reservarlo. Te lo decimos por experiencia 🙃',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="employees"]',
                popover: {
                    title: '👥 Empleados · Tu equipo',
                    description:
                        'Cada barbero, estilista o profesional va aquí.<br><br>' +
                        'Foto, horario propio (si trabaja distinto al negocio) y los servicios que sabe hacer. Dale amor a tu equipo 💪',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="customers"]',
                popover: {
                    title: '🧑 Clientes · Tu base de oro',
                    description:
                        'Quienes ya te reservaron quedan automáticamente aquí. Ves su historial de citas, teléfono y email.<br><br>' +
                        'Sí, es básicamente tu CRM mini 💎',
                    side: 'right', align: 'start',
                },
            },
            {
                element: 'a[href*="my-business"], a[href*="mi-negocio"]',
                popover: {
                    title: '🏢 Mi negocio · El cuartel general',
                    description:
                        'Aquí configuras: <strong>logo</strong>, <strong>banner</strong>, dirección, teléfono, horarios de atención y <strong>políticas de cancelación</strong>.<br><br>' +
                        '🎓 También está el botón para volver a ver este tutorial cuando se te olvide algo (todos olvidamos, no pasa nada).',
                    side: 'right', align: 'start',
                },
            },
            {
                popover: {
                    title: '🔗 Tu superpoder: el link público',
                    description:
                        'Tu negocio ya tiene un link mágico: <code>citora.com.co/tu-slug</code><br><br>' +
                        'Compártelo en <strong>Instagram</strong>, en tu bio de WhatsApp, en TikTok, donde sea. Tus clientes reservan ahí <strong>sin descargar nada</strong>.<br><br>' +
                        '🚀 Es el cambio que tu negocio necesitaba.',
                    side: 'over', align: 'center',
                },
            },
            {
                popover: {
                    title: '🎉 ¡Y eso es todo!',
                    description:
                        'Ya estás listo para arrancar. <br><br>' +
                        '<strong>Tu primera misión 🎯:</strong><br>' +
                        '1️⃣ Ve a <em>Servicios</em> → crea o edita uno → <strong>asígnale empleados</strong>.<br>' +
                        '2️⃣ Comparte tu link en una historia de Instagram.<br><br>' +
                        '¡Suerte y a llenar esa agenda! 💪✨',
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

    async function startTour(opts) {
        opts = opts || {};
        await loadDriverIfNeeded();
        injectStyles();
        const driverObj = window.driver.js.driver({
            showProgress: true,
            allowClose: true,
            popoverClass: 'citora-tour',
            nextBtnText: 'Siguiente →',
            prevBtnText: '← Atrás',
            doneBtnText: '¡Listo! 🎉',
            progressText: 'Paso {{current}} de {{total}}',
            steps: buildSteps(),
        });
        driverObj.drive();
    }

    function autoStartIfFirstTime() {
        if (!window.CitoraTutorial || window.CitoraTutorial.completed) return;

        try {
            if (sessionStorage.getItem('citora_tour_shown') === '1') return;
            sessionStorage.setItem('citora_tour_shown', '1');
        } catch (_) { /* ignore */ }

        markComplete();
        setTimeout(() => startTour({ auto: true }), 800);
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
                try { sessionStorage.removeItem('citora_tour_shown'); } catch (_) {}
                startTour();
            } catch (e) {
                alert('No se pudo reiniciar el tutorial: ' + e.message);
            }
        },
    };

    document.addEventListener('DOMContentLoaded', autoStartIfFirstTime);
})();
