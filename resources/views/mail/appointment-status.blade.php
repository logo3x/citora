@php
    $colors = [
        'created' => ['bg' => '#D1FAE5', 'border' => '#059669', 'text' => '#065F46', 'icon' => '✅', 'title' => 'Cita confirmada'],
        'cancelled' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B', 'icon' => '❌', 'title' => 'Cita cancelada'],
        'rescheduled' => ['bg' => '#DBEAFE', 'border' => '#2563EB', 'text' => '#1E40AF', 'icon' => '🔄', 'title' => 'Cita reprogramada'],
        'completed' => ['bg' => '#FEF3C7', 'border' => '#D97706', 'text' => '#92400E', 'icon' => '🎉', 'title' => 'Cita completada'],
        'reminder_24h' => ['bg' => '#FEF3C7', 'border' => '#D97706', 'text' => '#92400E', 'icon' => '⏰', 'title' => 'Recordatorio de tu cita'],
        'reminder_1h' => ['bg' => '#FEF3C7', 'border' => '#D97706', 'text' => '#92400E', 'icon' => '🔔', 'title' => 'Tu cita es en 1 hora'],
    ];
    $color = $colors[$event] ?? $colors['created'];
    $business = $appointment->business;
    $service = $appointment->service;
    $employee = $appointment->employee;
    $customer = $appointment->customer;
    $shareLink = $extra['share_link'] ?? null;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $color['title'] }} — Citora</title>
</head>
<body style="margin:0;padding:0;background:#FAFAF8;font-family:Arial,Helvetica,sans-serif;color:#111;line-height:1.5">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#FAFAF8;padding:24px 12px">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E7E5DF">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#0F172A,#1E293B);padding:24px;text-align:center">
                            <div style="color:#F59E0B;font-size:22px;font-weight:800;letter-spacing:-0.02em">Citora</div>
                            <div style="color:#9CA3AF;font-size:13px;margin-top:4px">Gestión inteligente de citas</div>
                        </td>
                    </tr>

                    {{-- Status pill --}}
                    <tr>
                        <td style="padding:28px 24px 0">
                            <div style="display:inline-block;padding:6px 14px;background:{{ $color['bg'] }};color:{{ $color['text'] }};border-radius:999px;font-size:13px;font-weight:700;border:1px solid {{ $color['border'] }}">
                                {{ $color['icon'] }} {{ $color['title'] }}
                            </div>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding:16px 24px 8px">
                            <h1 style="margin:0;font-size:22px;font-weight:700;color:#0F172A">
                                @if ($role === 'customer')
                                    Hola {{ $customer->name ?? '' }},
                                @elseif ($role === 'employee')
                                    Hola {{ $employee->name ?? '' }},
                                @else
                                    Hola,
                                @endif
                            </h1>
                            <p style="margin:8px 0 0;color:#374151;font-size:15px">
                                @switch($event)
                                    @case('created')
                                        @if ($role === 'customer')
                                            Tu cita en <strong>{{ $business->name }}</strong> ha sido registrada correctamente. Te esperamos.
                                        @elseif ($role === 'employee')
                                            Se te asignó una nueva cita en <strong>{{ $business->name }}</strong>.
                                        @else
                                            Se acaba de registrar una nueva cita en <strong>{{ $business->name }}</strong>.
                                        @endif
                                        @break
                                    @case('cancelled')
                                        La cita fue cancelada.
                                        @if (! empty($extra['changed_by']))
                                            <em style="color:#6B7280">({{ $extra['changed_by'] }})</em>
                                        @endif
                                        @break
                                    @case('rescheduled')
                                        La cita fue reprogramada.
                                        @if (! empty($extra['changed_by']))
                                            <em style="color:#6B7280">({{ $extra['changed_by'] }})</em>
                                        @endif
                                        @break
                                    @case('completed')
                                        Gracias por tu visita. Esperamos haberte atendido bien.
                                        @break
                                    @case('reminder_24h')
                                        Te recordamos que <strong>mañana</strong> tienes tu cita en <strong>{{ $business->name }}</strong>.
                                        @break
                                    @case('reminder_1h')
                                        Tu cita en <strong>{{ $business->name }}</strong> es <strong>en 1 hora</strong>.
                                        @break
                                @endswitch
                            </p>
                        </td>
                    </tr>

                    {{-- Appointment details --}}
                    <tr>
                        <td style="padding:20px 24px">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#FAFAF8;border:1px solid #E7E5DF;border-radius:12px">
                                <tr>
                                    <td style="padding:14px 18px 6px;font-size:12px;color:#6B7280;text-transform:uppercase;letter-spacing:0.05em;font-weight:700">
                                        Detalles
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px 14px">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:6px 0;width:40%;color:#6B7280;font-size:13px">Negocio</td>
                                                <td style="padding:6px 0;color:#111;font-weight:600;font-size:13px;text-align:right">{{ $business->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;color:#6B7280;font-size:13px">Servicio</td>
                                                <td style="padding:6px 0;color:#111;font-weight:600;font-size:13px;text-align:right">{{ $service->name }}</td>
                                            </tr>
                                            @if ($employee)
                                                <tr>
                                                    <td style="padding:6px 0;color:#6B7280;font-size:13px">Profesional</td>
                                                    <td style="padding:6px 0;color:#111;font-weight:600;font-size:13px;text-align:right">{{ $employee->name }}</td>
                                                </tr>
                                            @endif
                                            @if ($event === 'rescheduled' && ! empty($extra['old_date']))
                                                <tr>
                                                    <td style="padding:6px 0;color:#6B7280;font-size:13px">Fecha anterior</td>
                                                    <td style="padding:6px 0;color:#6B7280;font-size:13px;text-align:right;text-decoration:line-through">{{ $extra['old_date'] }} · {{ $extra['old_time'] ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:6px 0;color:#6B7280;font-size:13px">Nueva fecha</td>
                                                    <td style="padding:6px 0;color:#111;font-weight:600;font-size:13px;text-align:right">{{ $date }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td style="padding:6px 0;color:#6B7280;font-size:13px">Fecha</td>
                                                    <td style="padding:6px 0;color:#111;font-weight:600;font-size:13px;text-align:right">{{ $date }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td style="padding:6px 0;color:#6B7280;font-size:13px">Hora</td>
                                                <td style="padding:6px 0;color:#111;font-weight:600;font-size:13px;text-align:right">{{ $time }}</td>
                                            </tr>
                                            @if ($role !== 'employee')
                                                <tr>
                                                    <td style="padding:6px 0;color:#6B7280;font-size:13px">Valor</td>
                                                    <td style="padding:6px 0;color:#D97706;font-weight:700;font-size:15px;text-align:right">${{ number_format($service->price) }}</td>
                                                </tr>
                                            @endif
                                            @if ($role !== 'customer')
                                                <tr>
                                                    <td style="padding:6px 0;color:#6B7280;font-size:13px">Cliente</td>
                                                    <td style="padding:6px 0;color:#111;font-weight:600;font-size:13px;text-align:right">{{ $customer->name ?? '—' }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                                @if ($role !== 'customer' && ! empty($appointment->notes) && in_array($event, ['created', 'rescheduled', 'reminder_24h', 'reminder_1h']))
                                    <tr>
                                        <td style="padding:6px 18px 14px">
                                            <div style="background:#FEF3C7;border-left:3px solid #D97706;border-radius:8px;padding:10px 14px">
                                                <div style="font-size:11px;color:#92400E;text-transform:uppercase;letter-spacing:0.05em;font-weight:700;margin-bottom:4px">📝 Nota del cliente</div>
                                                <div style="font-size:13px;color:#451A03;white-space:pre-wrap">{{ $appointment->notes }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    @if ($shareLink)
                        <tr>
                            <td style="padding:8px 24px 24px;text-align:center">
                                <a href="{{ $shareLink }}" style="display:inline-block;padding:12px 26px;background:#D97706;color:white;font-weight:700;font-size:14px;text-decoration:none;border-radius:10px">
                                    Ver detalles de la cita
                                </a>
                            </td>
                        </tr>
                    @elseif ($event === 'completed')
                        <tr>
                            <td style="padding:8px 24px 24px;text-align:center">
                                <a href="{{ rtrim(config('app.url'), '/') }}/{{ $business->slug }}" style="display:inline-block;padding:12px 26px;background:#D97706;color:white;font-weight:700;font-size:14px;text-decoration:none;border-radius:10px">
                                    Reservar otra cita
                                </a>
                            </td>
                        </tr>
                    @endif

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#FAFAF8;padding:18px 24px;text-align:center;border-top:1px solid #E7E5DF">
                            <div style="font-size:12px;color:#9CA3AF;line-height:1.6">
                                Este correo fue enviado automáticamente por Citora.<br>
                                Si tienes preguntas, responde a este mensaje o escríbenos a
                                <a href="mailto:{{ config('legal.responsible.email', 'webcitora@gmail.com') }}" style="color:#D97706;text-decoration:none">{{ config('legal.responsible.email', 'webcitora@gmail.com') }}</a>.
                            </div>
                            <div style="margin-top:10px">
                                <a href="{{ route('legal.privacy') }}" style="color:#6B7280;font-size:11px;text-decoration:none;margin:0 6px">Privacidad</a>
                                <a href="{{ route('legal.terms') }}" style="color:#6B7280;font-size:11px;text-decoration:none;margin:0 6px">Términos</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
