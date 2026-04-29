<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Reservar cita' }} - Citora</title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/images/logo-light.png" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FAFAF8; color: #111111; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
        .slot-btn.selected { background-color: #D97706; color: white; }
        .step { display: none; }
        .step.active { display: block; }
        .service-card.selected { border-color: #D97706; box-shadow: 0 0 0 2px #D97706; }
        .employee-card.selected { border-color: #D97706; box-shadow: 0 0 0 2px #D97706; }
    </style>
</head>
<body style="min-height:100vh">
    @include('partials.gtm-body')
    {{-- Top bar for authenticated users --}}
    @auth
    <div style="background:white;border-bottom:1px solid #E7E5DF;padding:8px 16px">
        <div style="max-width:700px;margin:0 auto;display:flex;align-items:center;justify-content:space-between">
            <a href="/" style="display:flex;align-items:center;gap:6px;text-decoration:none">
                <img src="/images/logo-mark.svg" alt="Citora" style="height:24px" onerror="this.src='/images/logo-light.png'">
                <span style="font-weight:700;font-size:14px;color:#0F172A;font-family:Poppins,sans-serif">Citora</span>
            </a>
            <div style="display:flex;align-items:center;gap:14px;font-size:13px">
                <a href="{{ route('customer.appointments') }}" style="color:#374151;text-decoration:none;font-weight:500">📅 Mis citas</a>
                @if(auth()->user()->business_id)
                    <a href="{{ filament()->getUrl() }}" style="color:#D97706;text-decoration:none;font-weight:600">Mi panel</a>
                @endif
            </div>
        </div>
    </div>
    @endauth

    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
