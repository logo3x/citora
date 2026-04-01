<x-layouts.booking title="Reprogramar cita">

    <div style="background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%);padding:24px 16px">
        <div style="max-width:600px;margin:0 auto">
            <a href="{{ route('customer.appointments') }}" style="color:#F59E0B;font-size:13px;text-decoration:none;font-weight:600">← Mis citas</a>
            <h1 style="color:white;font-weight:700;font-size:20px;margin-top:8px;font-family:Poppins,sans-serif">Reprogramar cita</h1>
        </div>
    </div>

    <div style="max-width:600px;margin:0 auto;padding:24px 16px">

        {{-- Current appointment info --}}
        <div style="background:white;border:1px solid #E7E5DF;border-radius:12px;padding:16px;margin-bottom:24px">
            <p style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;font-weight:600">Cita actual</p>
            <p style="font-weight:700;font-size:16px;color:#0F172A">{{ $appointment->service->name }}</p>
            <p style="font-size:13px;color:#6b7280;margin-top:4px">{{ $appointment->business->name }}</p>
            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:8px;font-size:13px;color:#374151">
                <span>📅 {{ Carbon\Carbon::parse($appointment->starts_at)->translatedFormat('l d \\d\\e F') }}</span>
                <span>🕐 {{ Carbon\Carbon::parse($appointment->starts_at)->format('g:i A') }}</span>
                @if($appointment->employee)
                    <span>👤 {{ $appointment->employee->name }}</span>
                @endif
            </div>
        </div>

        {{-- New date --}}
        <h3 style="font-weight:700;font-size:15px;color:#0F172A;margin-bottom:12px">Selecciona nueva fecha</h3>
        <div id="dates-container" style="display:flex;gap:8px;overflow-x:auto;padding-bottom:12px;margin-bottom:20px"></div>

        {{-- New time --}}
        <h3 style="font-weight:700;font-size:15px;color:#0F172A;margin-bottom:12px">Selecciona nueva hora</h3>
        <div id="slots-container" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:20px">
            <p style="grid-column:1/-1;text-align:center;color:#9ca3af;font-size:13px;padding:16px 0">Selecciona una fecha</p>
        </div>
        <div id="slots-loading" style="display:none;text-align:center;padding:16px 0;color:#D97706">Cargando...</div>

        <button id="btn-save" onclick="saveReschedule()" disabled
                style="width:100%;padding:14px;background:#D97706;color:white;font-weight:700;border-radius:10px;border:none;font-size:15px;cursor:pointer;opacity:0.4">
            Confirmar nueva fecha
        </button>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const APPOINTMENT_ID = {{ $appointment->id }};
        let selectedDate = null;
        let selectedTime = null;

        // Generate dates
        const container = document.getElementById('dates-container');
        const days = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
        const months = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        for (let i = 0; i < 14; i++) {
            const d = new Date();
            d.setDate(d.getDate() + i);
            const iso = d.toISOString().split('T')[0];
            const btn = document.createElement('button');
            btn.style.cssText = 'flex-shrink:0;width:60px;padding:10px 0;border-radius:10px;border:2px solid #E7E5DF;text-align:center;cursor:pointer;background:white';
            btn.innerHTML = `<span style="display:block;font-size:11px;color:#9ca3af">${days[d.getDay()]}</span><span style="display:block;font-size:18px;font-weight:700">${d.getDate()}</span><span style="display:block;font-size:11px;color:#9ca3af">${months[d.getMonth()]}</span>`;
            btn.onclick = () => selectDate(btn, iso);
            container.appendChild(btn);
        }

        function selectDate(el, date) {
            document.querySelectorAll('#dates-container button').forEach(b => { b.style.borderColor = '#E7E5DF'; b.style.background = 'white'; });
            el.style.borderColor = '#D97706';
            el.style.background = '#fffbeb';
            selectedDate = date;
            selectedTime = null;
            document.getElementById('btn-save').disabled = true;
            document.getElementById('btn-save').style.opacity = '0.4';
            loadSlots(date);
        }

        async function loadSlots(date) {
            const slotsEl = document.getElementById('slots-container');
            const loadingEl = document.getElementById('slots-loading');
            slotsEl.innerHTML = '';
            loadingEl.style.display = 'block';

            try {
                const res = await fetch(`/mis-citas/${APPOINTMENT_ID}/slots?date=${date}`);
                const slots = await res.json();
                loadingEl.style.display = 'none';

                const entries = Object.entries(slots);
                if (entries.length === 0) {
                    slotsEl.innerHTML = '<p style="grid-column:1/-1;text-align:center;color:#9ca3af;font-size:13px;padding:16px 0">No hay horarios disponibles</p>';
                    return;
                }

                entries.forEach(([time, label]) => {
                    const btn = document.createElement('button');
                    btn.style.cssText = 'padding:10px;border-radius:8px;border:1px solid #E7E5DF;font-size:13px;font-weight:500;cursor:pointer;background:white';
                    btn.textContent = label;
                    btn.onclick = () => {
                        document.querySelectorAll('#slots-container button').forEach(b => { b.style.background = 'white'; b.style.color = '#374151'; b.style.borderColor = '#E7E5DF'; });
                        btn.style.background = '#D97706';
                        btn.style.color = 'white';
                        btn.style.borderColor = '#D97706';
                        selectedTime = time;
                        document.getElementById('btn-save').disabled = false;
                        document.getElementById('btn-save').style.opacity = '1';
                    };
                    slotsEl.appendChild(btn);
                });
            } catch (e) {
                loadingEl.style.display = 'none';
                slotsEl.innerHTML = '<p style="grid-column:1/-1;text-align:center;color:#dc2626;font-size:13px;padding:16px 0">Error al cargar</p>';
            }
        }

        async function saveReschedule() {
            if (!selectedDate || !selectedTime) return;

            const btn = document.getElementById('btn-save');
            btn.disabled = true;
            btn.textContent = 'Guardando...';

            try {
                const res = await fetch(`/mis-citas/${APPOINTMENT_ID}/reprogramar`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ date: selectedDate, time: selectedTime })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Error');

                await Swal.fire({
                    icon: 'success',
                    title: '¡Cita reprogramada!',
                    html: `<p>${data.date}</p><p>${data.time}</p>`,
                    confirmButtonColor: '#D97706'
                });

                window.location.href = '/mis-citas';
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
                btn.disabled = false;
                btn.textContent = 'Confirmar nueva fecha';
            }
        }
    </script>

</x-layouts.booking>
