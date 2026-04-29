<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu cita en {{ $appointment->business->name }} — Citora</title>
    <meta name="description" content="Detalles de tu cita de {{ $appointment->service->name }} en {{ $appointment->business->name }}.">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/images/favicon-32.png" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="/images/favicon-180.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --amber: #D97706;
            --amber-light: #F59E0B;
            --amber-soft: #FEF3C7;
            --slate-900: #0F172A;
            --slate-700: #334155;
            --slate-500: #64748b;
            --slate-400: #94A3B8;
            --green: #059669;
            --green-soft: #D1FAE5;
            --red: #DC2626;
            --red-soft: #FEE2E2;
            --blue: #2563EB;
            --blue-soft: #DBEAFE;
            --cream: #FAFAF8;
            --border: #E7E5DF;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--cream); color: #111; min-height: 100vh; }
        h1, h2 { font-family: 'Poppins', sans-serif; letter-spacing: -0.02em; }

        .page { max-width: 560px; margin: 0 auto; }

        .hero {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, var(--slate-900) 0%, var(--slate-700) 100%);
            overflow: hidden;
        }
        .hero img { width: 100%; height: 100%; object-fit: cover; opacity: 0.85; }
        .hero::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.6) 100%);
        }

        .brand-badge {
            position: absolute; bottom: -28px; left: 24px;
            background: white; border: 2px solid white;
            border-radius: 16px; padding: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            z-index: 2;
        }
        .brand-badge img { width: 48px; height: 48px; object-fit: cover; border-radius: 10px; }

        .container { padding: 44px 20px 20px; }
        .business-name { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
        .business-addr { color: var(--slate-500); font-size: 13px; margin-bottom: 20px; }

        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 600;
            margin-bottom: 20px;
        }
        .status-pending { background: var(--amber-soft); color: #92400E; }
        .status-confirmed { background: var(--green-soft); color: #065F46; }
        .status-cancelled { background: var(--red-soft); color: #991B1B; }
        .status-completed { background: var(--blue-soft); color: #1E40AF; }
        .status-pill::before { content: '●'; font-size: 8px; }

        .card {
            background: white; border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            margin-bottom: 16px;
        }
        .service-image { width: 100%; height: 180px; object-fit: cover; background: var(--cream); }
        .service-body { padding: 20px; }
        .service-name { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .service-duration { font-size: 13px; color: var(--slate-500); }

        .detail-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px; border-top: 1px solid var(--border);
        }
        .detail-row:first-child { border-top: none; }
        .detail-label { font-size: 13px; color: var(--slate-500); display: flex; align-items: center; gap: 10px; }
        .detail-label svg { width: 16px; height: 16px; color: var(--slate-400); }
        .detail-value { font-size: 14px; font-weight: 600; color: #111; text-align: right; }
        .detail-value.price { color: var(--amber); font-size: 18px; font-weight: 800; }

        .actions { display: grid; gap: 10px; margin-top: 8px; }
        .btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 16px; border-radius: 12px; font-weight: 600; font-size: 14px;
            text-decoration: none; border: none; cursor: pointer; transition: transform 0.1s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background: var(--amber); color: white; }
        .btn-primary:hover { background: #B45309; }
        .btn-outline { background: white; color: var(--slate-700); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--cream); }

        .footer {
            text-align: center; padding: 24px; font-size: 11px; color: var(--slate-400);
            margin-top: 24px;
        }
        .footer a { color: var(--slate-500); text-decoration: none; margin: 0 6px; }
        .footer-brand { font-weight: 700; color: var(--slate-700); font-family: Poppins; }

        @media (max-width: 520px) {
            .hero { height: 160px; }
            .business-name { font-size: 20px; }
            .service-image { height: 140px; }
        }
    </style>
</head>
<body>
    @include('partials.gtm-body')
    @php
        $banner = $appointment->business->getFirstMediaUrl('banner');
        $logo = $appointment->business->getFirstMediaUrl('logo');
        $serviceImage = $appointment->service->getFirstMediaUrl('image');
        $status = $appointment->status->value;
        $statusLabels = [
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'completed' => 'Completada',
        ];
        $starts = \Carbon\Carbon::parse($appointment->starts_at);
    @endphp

    <div class="page">
        <div class="hero">
            @if ($banner)
                <img src="{{ $banner }}" alt="{{ $appointment->business->name }}">
            @endif
            <div class="brand-badge">
                @if ($logo)
                    <img src="{{ $logo }}" alt="{{ $appointment->business->name }}">
                @else
                    <div style="width:48px;height:48px;border-radius:10px;background:var(--amber-soft);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--amber);font-size:20px">
                        {{ \Illuminate\Support\Str::of($appointment->business->name)->substr(0, 1)->upper() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="container">
            <div class="business-name">{{ $appointment->business->name }}</div>
            @if ($appointment->business->address)
                <div class="business-addr">{{ $appointment->business->address }}</div>
            @endif

            <div class="status-pill status-{{ $status }}">{{ $statusLabels[$status] ?? ucfirst($status) }}</div>

            <div class="card">
                @if ($serviceImage)
                    <img src="{{ $serviceImage }}" alt="{{ $appointment->service->name }}" class="service-image">
                @endif
                <div class="service-body">
                    <div class="service-name">{{ $appointment->service->name }}</div>
                    <div class="service-duration">Duración: {{ $appointment->service->duration_minutes }} minutos</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profesional
                    </div>
                    <div class="detail-value">{{ $appointment->employee?->name ?? 'Por asignar' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Fecha
                    </div>
                    <div class="detail-value">{{ $starts->translatedFormat('l d \\d\\e F, Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Hora
                    </div>
                    <div class="detail-value">{{ $starts->format('g:i A') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                        Valor
                    </div>
                    <div class="detail-value price">${{ number_format($appointment->service->price) }}</div>
                </div>
                @if ($appointment->notes)
                    <div class="detail-row" style="flex-direction:column;align-items:flex-start;gap:6px">
                        <div class="detail-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Notas
                        </div>
                        <div style="font-size:13px;color:#374151;white-space:pre-wrap">{{ $appointment->notes }}</div>
                    </div>
                @endif
            </div>

            @php
                $canManage = ! in_array($status, ['cancelled', 'completed', 'no_show'])
                    && $appointment->starts_at > now();
                $isPending = $status === 'pending';
            @endphp

            @if (session('success'))
                <div style="background:#D1FAE5;color:#065F46;border:1px solid #6EE7B7;border-radius:12px;padding:12px 16px;margin-bottom:12px;font-size:14px;font-weight:500">
                    ✓ {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div style="background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;border-radius:12px;padding:12px 16px;margin-bottom:12px;font-size:14px;font-weight:500">
                    {{ session('error') }}
                </div>
            @elseif (session('info'))
                <div style="background:#DBEAFE;color:#1E40AF;border:1px solid #93C5FD;border-radius:12px;padding:12px 16px;margin-bottom:12px;font-size:14px;font-weight:500">
                    {{ session('info') }}
                </div>
            @endif

            <div class="actions">
                @if ($canManage)
                    @if ($isPending)
                        <form method="POST" action="{{ route('appointment.share.confirm', ['token' => $shareToken]) }}" style="display:contents">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                ✓ Confirmar cita
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('appointment.share.reschedule', ['token' => $shareToken]) }}" class="btn btn-outline">
                        🔄 Aplazar
                    </a>

                    <form method="POST" action="{{ route('appointment.share.cancel', ['token' => $shareToken]) }}" onsubmit="return confirm('¿Seguro que quieres cancelar esta cita?');" style="display:contents">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="color:#DC2626;border-color:#FCA5A5">
                            ✕ Cancelar
                        </button>
                    </form>
                @endif

                <a href="/{{ $appointment->business->slug }}" class="btn btn-outline">
                    Reservar otra cita en {{ $appointment->business->name }}
                </a>
            </div>

            <div class="footer">
                <div>Enlace enviado por <span class="footer-brand">Citora</span></div>
                <div style="margin-top:6px">
                    <a href="{{ route('legal.privacy') }}">Privacidad</a>
                    <a href="{{ route('legal.terms') }}">Términos</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
