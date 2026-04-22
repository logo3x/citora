<x-filament-panels::page>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/es.global.min.js"></script>

    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div id="citora-calendar" class="p-4" wire:ignore></div>
    </div>

    <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-gray-700 dark:text-gray-300">
        <div class="flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full" style="background:#F59E0B"></span>
            <span>Pendiente</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full" style="background:#2563EB"></span>
            <span>Confirmada</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full" style="background:#059669"></span>
            <span>Completada</span>
        </div>
    </div>

    <style>
        #citora-calendar { min-height: 650px; }
        .fc { font-family: 'Inter', sans-serif; }
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
