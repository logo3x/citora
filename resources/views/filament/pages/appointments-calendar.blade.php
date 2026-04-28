<x-filament-panels::page>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/es.global.min.js"></script>

    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div id="citora-calendar" class="p-4" wire:ignore></div>
    </div>

    <div class="citora-legend">
        <span class="citora-legend-title">Estados de cita:</span>
        <span class="citora-legend-item">
            <span class="citora-legend-dot" style="background:#F59E0B"></span>
            <span>Pendiente</span>
        </span>
        <span class="citora-legend-item">
            <span class="citora-legend-dot" style="background:#2563EB"></span>
            <span>Confirmada</span>
        </span>
        <span class="citora-legend-item">
            <span class="citora-legend-dot" style="background:#059669"></span>
            <span>Completada</span>
        </span>
        <span class="citora-legend-item">
            <span class="citora-legend-dot" style="background:#9CA3AF"></span>
            <span>Cancelada</span>
        </span>
        <span class="citora-legend-item">
            <span class="citora-legend-dot" style="background:#6B7280"></span>
            <span>No llegó</span>
        </span>
        <span class="citora-legend-item">
            <span class="citora-legend-dot" style="background:#F97316"></span>
            <span>Llegó tarde</span>
        </span>
    </div>

    <style>
        #citora-calendar { min-height: 650px; }
        .fc { font-family: 'Inter', sans-serif; }
        .citora-legend {
            margin-top: 16px;
            padding: 14px 18px;
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 12px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 14px 20px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #334155;
        }
        .dark .citora-legend {
            background: rgba(217, 119, 6, 0.06);
            border-color: rgba(245, 158, 11, 0.2);
            color: #E5E7EB;
        }
        .citora-legend-title {
            font-weight: 700;
            color: #92400E;
            margin-right: 4px;
        }
        .dark .citora-legend-title { color: #FCD34D; }
        .citora-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .citora-legend-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.6),
                        0 1px 3px rgba(0,0,0,0.15);
            flex-shrink: 0;
        }
        .fc-button-primary {
            background: #D97706 !important;
            border-color: #D97706 !important;
        }
        .fc-button-primary:hover,
        .fc-button-primary:not(:disabled):active,
        .fc-button-primary:not(:disabled).fc-button-active {
            background: #B45309 !important;
            border-color: #B45309 !important;
        }
        .fc-today-button { text-transform: capitalize; }
        .fc-event { cursor: pointer; font-size: 0.78rem; }
        .fc-event-title { font-weight: 600; }
        .dark .fc-col-header-cell-cushion,
        .dark .fc-daygrid-day-number,
        .dark .fc-list-day-text,
        .dark .fc-list-day-side-text,
        .dark .fc-toolbar-title,
        .dark .fc-timegrid-axis-cushion,
        .dark .fc-timegrid-slot-label-cushion {
            color: #E5E7EB;
        }
        .dark .fc-theme-standard td,
        .dark .fc-theme-standard th,
        .dark .fc-theme-standard .fc-scrollgrid {
            border-color: #374151;
        }
        .dark .fc-day-today { background: rgba(245, 158, 11, 0.08) !important; }
    </style>

    @script
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initCalendar();
        });

        initCalendar();

        function initCalendar() {
            const el = document.getElementById('citora-calendar');
            if (! el || el.dataset.initialized) return;
            el.dataset.initialized = '1';

            const isMobile = window.matchMedia('(max-width: 768px)').matches;

            const calendar = new FullCalendar.Calendar(el, {
                initialView: isMobile ? 'timeGridDay' : 'dayGridMonth',
                locale: 'es',
                firstDay: 1,
                headerToolbar: isMobile
                    ? { left: 'prev,next', center: 'title', right: 'timeGridDay,listWeek' }
                    : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek' },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista',
                },
                slotMinTime: '06:00:00',
                slotMaxTime: '23:00:00',
                allDaySlot: false,
                nowIndicator: true,
                height: 'auto',
                dayMaxEventRows: 3,
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short',
                },
                events: async (info, successCallback, failureCallback) => {
                    try {
                        const events = await $wire.getEvents(info.startStr, info.endStr);
                        successCallback(events);
                    } catch (e) {
                        console.error('Error cargando eventos', e);
                        failureCallback(e);
                    }
                },
                eventClick: (info) => {
                    const url = info.event.extendedProps.editUrl;
                    if (url) window.location.href = url;
                },
                eventDidMount: (info) => {
                    const emp = info.event.extendedProps.employee;
                    if (emp) {
                        info.el.setAttribute('title', `Con ${emp}`);
                    }
                },
            });

            calendar.render();
        }
    </script>
    @endscript
</x-filament-panels::page>
