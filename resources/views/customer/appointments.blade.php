<x-layouts.booking title="Mis citas">

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%);padding:24px 16px">
        <div style="max-width:700px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:12px">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="" referrerpolicy="no-referrer" style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.2)">
                @endif
                <div>
                    <p style="color:white;font-weight:700;font-size:18px;font-family:Poppins,sans-serif">Mis citas</p>
                    <p style="color:rgba(255,255,255,0.6);font-size:13px">{{ $user->name }}</p>
                </div>
            </div>
            <a href="/" style="color:#F59E0B;font-size:13px;text-decoration:none;font-weight:600">← Explorar negocios</a>
        </div>
    </div>

    <div style="max-width:700px;margin:0 auto;padding:24px 16px">

        {{-- Upcoming --}}
        <h2 style="font-size:16px;font-weight:700;color:#0F172A;margin-bottom:16px;font-family:Poppins,sans-serif">
            Próximas citas
            @if($upcoming->count() > 0)
                <span style="font-size:12px;font-weight:500;color:#6b7280;margin-left:6px">({{ $upcoming->count() }})</span>
            @endif
        </h2>

        @forelse($upcoming as $appointment)
            <div style="background:white;border:1px solid #E7E5DF;border-radius:12px;padding:16px;margin-bottom:12px" id="appointment-{{ $appointment->id }}">
                <div style="display:flex;gap:12px;align-items:flex-start">
                    @if($appointment->business->getFirstMediaUrl('logo'))
                        <img src="{{ $appointment->business->getFirstMediaUrl('logo') }}" alt="" style="width:44px;height:44px;border-radius:10px;object-fit:cover;border:1px solid #E7E5DF">
                    @endif
                    <div style="flex:1;min-width:0">
                        <p style="font-weight:700;font-size:15px;color:#0F172A">{{ $appointment->service->name }}</p>
                        <p style="font-size:13px;color:#6b7280">{{ $appointment->business->name }}</p>
                        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:8px;font-size:13px;color:#374151">
                            <span>📅 {{ Carbon\Carbon::parse($appointment->starts_at)->translatedFormat('l d \\d\\e F') }}</span>
                            <span>🕐 {{ Carbon\Carbon::parse($appointment->starts_at)->format('g:i A') }}</span>
                            @if($appointment->employee)
                                <span>👤 {{ $appointment->employee->name }}</span>
                            @endif
                        </div>
                        <div style="margin-top:6px">
                            <span style="display:inline-block;padding:2px 10px;border-radius:9999px;font-size:11px;font-weight:600;
                                {{ $appointment->status === App\Enums\AppointmentStatus::Confirmed ? 'background:#d1fae5;color:#065f46' : 'background:#fef3c7;color:#92400e' }}">
                                {{ $appointment->status === App\Enums\AppointmentStatus::Confirmed ? '✅ Confirmada' : '⏳ Pendiente' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid #E7E5DF">
                    <a href="{{ route('customer.reschedule', $appointment) }}"
                       style="flex:1;text-align:center;padding:8px;border:1px solid #E7E5DF;border-radius:8px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;background:white"
                       onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                        🔄 Reprogramar
                    </a>
                    <button onclick="cancelAppointment({{ $appointment->id }})"
                            style="flex:1;text-align:center;padding:8px;border:1px solid #fecaca;border-radius:8px;font-size:13px;font-weight:600;color:#dc2626;background:white;cursor:pointer"
                            onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                        ✕ Cancelar
                    </button>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:40px 20px;color:#9ca3af">
                <p style="font-size:36px;margin-bottom:8px">📅</p>
                <p style="font-weight:600;color:#6b7280">No tienes citas próximas</p>
                <a href="/" style="display:inline-block;margin-top:12px;padding:8px 20px;background:#D97706;color:white;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none">Explorar negocios</a>
            </div>
        @endforelse

        {{-- Past --}}
        @if($past->count() > 0)
            <h2 style="font-size:16px;font-weight:700;color:#0F172A;margin:32px 0 16px;font-family:Poppins,sans-serif">
                Historial
                <span style="font-size:12px;font-weight:500;color:#6b7280;margin-left:6px">({{ $past->count() }})</span>
            </h2>

            @foreach($past as $appointment)
                <div style="background:white;border:1px solid #E7E5DF;border-radius:12px;padding:14px;margin-bottom:8px;opacity:{{ $appointment->status === App\Enums\AppointmentStatus::Cancelled ? '0.6' : '1' }}">
                    <div style="display:flex;gap:10px;align-items:center">
                        @if($appointment->business->getFirstMediaUrl('logo'))
                            <img src="{{ $appointment->business->getFirstMediaUrl('logo') }}" alt="" style="width:36px;height:36px;border-radius:8px;object-fit:cover;border:1px solid #E7E5DF">
                        @endif
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                                <p style="font-weight:600;font-size:14px;color:#0F172A">{{ $appointment->service->name }}</p>
                                <span style="display:inline-block;padding:2px 8px;border-radius:9999px;font-size:10px;font-weight:600;
                                    {{ match($appointment->status) {
                                        App\Enums\AppointmentStatus::Completed => 'background:#d1fae5;color:#065f46',
                                        App\Enums\AppointmentStatus::Cancelled => 'background:#fee2e2;color:#991b1b',
                                        default => 'background:#e5e7eb;color:#374151'
                                    } }}">
                                    {{ match($appointment->status) {
                                        App\Enums\AppointmentStatus::Completed => '✅ Completada',
                                        App\Enums\AppointmentStatus::Cancelled => '✕ Cancelada',
                                        default => $appointment->status->value
                                    } }}
                                </span>
                            </div>
                            <p style="font-size:12px;color:#6b7280;margin-top:2px">
                                {{ $appointment->business->name }} · {{ Carbon\Carbon::parse($appointment->starts_at)->translatedFormat('d M Y') }} · {{ Carbon\Carbon::parse($appointment->starts_at)->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Footer --}}
    <div style="text-align:center;padding:24px 16px;font-size:12px;color:#9ca3af;border-top:1px solid #E7E5DF;margin-top:24px">
        Powered by <a href="/" style="color:#D97706;text-decoration:none;font-weight:500">Citora</a>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        async function cancelAppointment(id) {
            const result = await Swal.fire({
                title: '¿Cancelar esta cita?',
                text: 'Esta acción no se puede deshacer. Se notificará al negocio.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No'
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`/mis-citas/${id}/cancelar`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });

                const data = await res.json();

                if (!res.ok) throw new Error(data.error || 'Error');

                await Swal.fire({ icon: 'success', title: 'Cita cancelada', text: data.message, confirmButtonColor: '#D97706' });
                window.location.reload();
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        }
    </script>

</x-layouts.booking>
