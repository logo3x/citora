<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo negocio en Citora</title>
</head>
<body style="margin:0;padding:0;background:#FAFAF8;font-family:Arial,Helvetica,sans-serif;color:#111">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#FAFAF8;padding:24px 12px">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E7E5DF">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0F172A,#1E293B);padding:24px;text-align:center">
                            <div style="color:#F59E0B;font-size:24px;font-weight:800;letter-spacing:-0.02em">🎉 ¡Nuevo negocio en Citora!</div>
                            <div style="color:#9CA3AF;font-size:13px;margin-top:4px">Notificación para el administrador</div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px">
                            <h2 style="margin:0 0 8px 0;font-size:20px;color:#0F172A">{{ $business->name }}</h2>
                            @if ($business->slogan)
                                <p style="margin:0 0 16px 0;color:#6B7280;font-style:italic">"{{ $business->slogan }}"</p>
                            @endif

                            <p style="color:#374151;font-size:14px;line-height:1.6">
                                Se acaba de registrar un nuevo negocio en la plataforma.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;background:#FAFAF8;border-radius:12px;border:1px solid #E7E5DF">
                                <tr>
                                    <td style="padding:16px 20px">
                                        <div style="font-size:12px;color:#6B7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Enlace público</div>
                                        <a href="{{ rtrim(config('app.url'), '/') }}/{{ $business->slug }}" style="color:#D97706;font-weight:600;text-decoration:none;font-size:14px">{{ rtrim(config('app.url'), '/') }}/{{ $business->slug }}</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px">
                                @if ($business->email)
                                    <tr>
                                        <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;width:40%;color:#6B7280;font-size:13px">📧 Correo</td>
                                        <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;color:#111;font-weight:500;font-size:13px">{{ $business->email }}</td>
                                    </tr>
                                @endif
                                @if ($business->phone)
                                    <tr>
                                        <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;width:40%;color:#6B7280;font-size:13px">📱 Teléfono</td>
                                        <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;color:#111;font-weight:500;font-size:13px">{{ $business->phone }}</td>
                                    </tr>
                                @endif
                                @if ($business->address)
                                    <tr>
                                        <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;width:40%;color:#6B7280;font-size:13px">📍 Dirección</td>
                                        <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;color:#111;font-weight:500;font-size:13px">{{ $business->address }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;width:40%;color:#6B7280;font-size:13px">🛠️ Servicios</td>
                                    <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;color:#111;font-weight:500;font-size:13px">{{ $business->services->count() }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;width:40%;color:#6B7280;font-size:13px">👥 Empleados</td>
                                    <td style="padding:8px 0;border-bottom:1px solid #E7E5DF;color:#111;font-weight:500;font-size:13px">{{ $business->employees->count() }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;width:40%;color:#6B7280;font-size:13px">🕐 Registrado</td>
                                    <td style="padding:8px 0;color:#111;font-weight:500;font-size:13px">{{ $business->created_at->translatedFormat('d \\d\\e F \\d\\e Y \\a \\l\\a\\s H:i') }}</td>
                                </tr>
                            </table>

                            @if ($business->services->count() > 0)
                                <h3 style="margin:24px 0 12px 0;font-size:15px;color:#0F172A">Servicios registrados</h3>
                                <ul style="margin:0;padding-left:20px;color:#374151;font-size:13px">
                                    @foreach ($business->services as $service)
                                        <li style="margin-bottom:4px">{{ $service->name }} — ${{ number_format($service->price) }} · {{ $service->duration_minutes }} min</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($business->employees->count() > 0)
                                <h3 style="margin:20px 0 12px 0;font-size:15px;color:#0F172A">Empleados registrados</h3>
                                <ul style="margin:0;padding-left:20px;color:#374151;font-size:13px">
                                    @foreach ($business->employees as $employee)
                                        <li style="margin-bottom:4px">{{ $employee->name }}@if ($employee->position) — {{ $employee->position }}@endif</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#FAFAF8;padding:16px 24px;text-align:center;border-top:1px solid #E7E5DF">
                            <div style="font-size:12px;color:#9CA3AF">
                                Este correo fue enviado automáticamente por Citora.<br>
                                <a href="{{ config('app.url') }}" style="color:#D97706;text-decoration:none">{{ config('app.url') }}</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
