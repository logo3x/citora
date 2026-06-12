<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Reservar cita' }} - Citora</title>
    <link rel="icon" type="image/png" sizes="32x32" href="@vasset('images/favicon-32.png')">
    <link rel="icon" type="image/png" sizes="16x16" href="@vasset('images/favicon-16.png')">
    <link rel="apple-touch-icon" href="@vasset('images/favicon-180.png')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FAFAF8; color: #111111; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; }

        /* ===========================================================
           Premium hero
           =========================================================== */
        @keyframes biz-mesh-shift {
            0%, 100% { background-position: 0% 0%, 100% 100%, 50% 50%; }
            50%      { background-position: 100% 50%, 0% 50%, 30% 70%; }
        }
        @keyframes biz-float-y {
            0%, 100% { transform: translate3d(0, 0, 0); }
            50%      { transform: translate3d(0, -8px, 0); }
        }
        @keyframes biz-orb-drift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50%      { transform: translate3d(20px, -16px, 0) scale(1.08); }
        }
        @keyframes biz-rise {
            from { opacity: 0; transform: translate3d(0, 18px, 0); }
            to   { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        @keyframes biz-pill-pop {
            from { opacity: 0; transform: scale(.82); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes biz-success-check {
            0%   { stroke-dashoffset: 60; }
            100% { stroke-dashoffset: 0; }
        }

        .biz-hero {
            position: relative;
            min-height: 260px;
            overflow: hidden;
            background: linear-gradient(135deg, #0B1326 0%, #1E293B 55%, #312E81 100%);
        }
        .biz-hero-banner {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
            filter: brightness(.55) saturate(1.05);
        }
        .biz-hero-mesh {
            position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 18% 22%, rgba(217,119,6,.32), transparent 42%),
                radial-gradient(circle at 82% 76%, rgba(13,148,136,.32), transparent 46%),
                radial-gradient(circle at 50% 50%, rgba(99,102,241,.18), transparent 60%);
            background-size: 200% 200%, 200% 200%, 220% 220%;
            animation: biz-mesh-shift 14s ease-in-out infinite;
            mix-blend-mode: screen;
            pointer-events: none;
        }
        .biz-hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(48px);
            pointer-events: none;
        }
        .biz-hero-orb-1 {
            width: 220px; height: 220px;
            background: rgba(245,158,11,.35);
            top: -60px; right: -40px;
            animation: biz-orb-drift 9s ease-in-out infinite;
        }
        .biz-hero-orb-2 {
            width: 180px; height: 180px;
            background: rgba(45,181,168,.32);
            bottom: -60px; left: -30px;
            animation: biz-orb-drift 12s ease-in-out infinite reverse;
        }
        .biz-hero-grain {
            position: absolute; inset: 0;
            background-image: linear-gradient(180deg, transparent 60%, rgba(0,0,0,.45) 100%);
            pointer-events: none;
        }
        .biz-hero-content {
            position: relative; z-index: 2;
            display: flex; align-items: flex-end; gap: 18px;
            padding: 56px 16px 32px;
            max-width: 700px; margin: 0 auto;
        }
        .biz-hero-logo {
            width: 82px; height: 82px;
            border-radius: 22px;
            border: 3px solid rgba(255,255,255,.85);
            box-shadow: 0 18px 40px -10px rgba(0,0,0,.5), 0 0 0 8px rgba(255,255,255,.06);
            object-fit: cover;
            background: white;
            animation: biz-rise .7s cubic-bezier(.22,1,.36,1) both, biz-float-y 5s ease-in-out 1s infinite;
        }
        .biz-hero-logo-fallback {
            width: 82px; height: 82px;
            border-radius: 22px;
            background: linear-gradient(135deg, #D97706, #B45309);
            color: white; font-weight: 800; font-size: 36px;
            font-family: Poppins, sans-serif;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 18px 40px -10px rgba(0,0,0,.5);
            animation: biz-rise .7s cubic-bezier(.22,1,.36,1) both, biz-float-y 5s ease-in-out 1s infinite;
        }
        .biz-hero-title {
            font-family: Poppins, sans-serif;
            font-size: clamp(22px, 4.5vw, 32px);
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
            line-height: 1.1;
            text-shadow: 0 2px 12px rgba(0,0,0,.45);
            animation: biz-rise .7s cubic-bezier(.22,1,.36,1) .12s both;
        }
        .biz-hero-slogan {
            color: rgba(255,255,255,.85);
            font-size: 14px;
            margin-top: 4px;
            text-shadow: 0 1px 8px rgba(0,0,0,.35);
            animation: biz-rise .7s cubic-bezier(.22,1,.36,1) .22s both;
        }
        .biz-hero-status {
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 10px;
            padding: 4px 10px 4px 8px;
            background: rgba(16, 185, 129, .15);
            border: 1px solid rgba(110, 231, 183, .35);
            color: #6EE7B7;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            backdrop-filter: blur(6px);
            animation: biz-rise .6s cubic-bezier(.22,1,.36,1) .32s both;
        }
        .biz-hero-status .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #10B981;
            box-shadow: 0 0 0 0 rgba(16,185,129,.45);
            animation: citora-pulse-ring 1.8s ease-out infinite;
        }
        .biz-hero-status.is-closed {
            background: rgba(244, 63, 94, .12);
            border-color: rgba(251, 113, 133, .35);
            color: #FDA4AF;
        }
        .biz-hero-status.is-closed .dot {
            background: #F43F5E;
        }

        /* ===========================================================
           Quick action pills (replaces "Ver más" details)
           =========================================================== */
        .biz-quick-row {
            display: flex; gap: 8px;
            overflow-x: auto;
            padding: 16px 16px 4px;
            max-width: 700px; margin: 0 auto;
            scrollbar-width: none;
        }
        .biz-quick-row::-webkit-scrollbar { display: none; }
        .biz-quick {
            flex-shrink: 0;
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 14px;
            background: white;
            border: 1px solid #E7E5DF;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            color: #0F172A;
            text-decoration: none;
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
            animation: biz-pill-pop .45s cubic-bezier(.22,1,.36,1) both;
        }
        .biz-quick:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px -14px rgba(15,23,42,.25);
            border-color: #D97706;
        }
        .biz-quick svg { width: 15px; height: 15px; }
        .biz-quick.is-wa { color: #059669; }
        .biz-quick.is-call { color: #2563EB; }
        .biz-quick.is-map { color: #DC2626; }
        .biz-quick.is-info { color: #D97706; }
        .biz-quick.is-toggle[aria-expanded="true"] {
            background: #FEF3C7;
            border-color: #FCD34D;
        }
        .biz-quick:nth-child(1) { animation-delay: .35s; }
        .biz-quick:nth-child(2) { animation-delay: .42s; }
        .biz-quick:nth-child(3) { animation-delay: .49s; }
        .biz-quick:nth-child(4) { animation-delay: .56s; }
        .biz-quick:nth-child(5) { animation-delay: .63s; }

        /* Expandable info panel */
        .biz-info-panel {
            max-width: 700px; margin: 8px auto 0;
            padding: 0 16px;
            display: grid; grid-template-rows: 0fr;
            transition: grid-template-rows .4s cubic-bezier(.22,1,.36,1);
        }
        .biz-info-panel.is-open { grid-template-rows: 1fr; }
        .biz-info-panel > div {
            overflow: hidden;
        }
        .biz-info-card {
            background: white;
            border: 1px solid #E7E5DF;
            border-radius: 18px;
            padding: 20px;
            margin-top: 12px;
            box-shadow: 0 14px 32px -22px rgba(15,23,42,.18);
        }

        /* ===========================================================
           Stats pills row
           =========================================================== */
        .biz-stats {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            max-width: 700px; margin: 16px auto 0;
            padding: 0 16px;
        }
        .biz-stat {
            background: white;
            border: 1px solid #E7E5DF;
            border-radius: 14px;
            padding: 12px;
            text-align: center;
            transition: transform .25s ease, box-shadow .25s ease;
            animation: biz-rise .6s cubic-bezier(.22,1,.36,1) both;
        }
        .biz-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 22px -14px rgba(15,23,42,.18); }
        .biz-stat:nth-child(1) { animation-delay: .55s; }
        .biz-stat:nth-child(2) { animation-delay: .62s; }
        .biz-stat:nth-child(3) { animation-delay: .69s; }
        .biz-stat .num {
            font-family: Poppins, sans-serif;
            font-size: 22px; font-weight: 800;
            color: #0F172A;
            line-height: 1;
        }
        .biz-stat .lbl {
            font-size: 11px; color: #6b7280;
            text-transform: uppercase; letter-spacing: .04em;
            font-weight: 600;
            margin-top: 4px;
        }

        /* Steps: cross-fade entrance */
        .step { display: none; }
        .step.active {
            display: block;
            animation: citora-fade-up .45s cubic-bezier(.22,1,.36,1) both;
        }

        /* ===========================================================
           Service card redesign
           =========================================================== */
        .service-card {
            position: relative;
            overflow: hidden;
            transition:
                border-color .25s ease,
                box-shadow .3s ease,
                transform .3s cubic-bezier(.22,1,.36,1);
        }
        .service-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #D97706, #B45309);
            transform: scaleY(0);
            transform-origin: top;
            transition: transform .3s cubic-bezier(.22,1,.36,1);
        }
        .service-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -18px rgba(15,23,42,.22);
            border-color: rgba(217,119,6,.4);
        }
        .service-card:hover::after { transform: scaleY(1); }
        .service-card:hover img { transform: scale(1.08); }
        .service-card img {
            transition: transform .5s ease;
        }
        .service-card.selected {
            border-color: #D97706;
            box-shadow: 0 0 0 2px #D97706, 0 12px 26px -16px rgba(217,119,6,.35);
        }
        .service-card.selected::after { transform: scaleY(1); }

        /* Employee card (circular avatars) */
        .employee-card {
            transition: transform .25s ease;
        }
        .employee-card:hover { transform: translateY(-2px); }
        .employee-card.selected > div:first-child,
        .employee-card.selected > img {
            border-color: #D97706 !important;
            box-shadow: 0 0 0 2px #D97706, 0 8px 18px -10px rgba(217,119,6,.45);
        }

        /* ===========================================================
           Date pill redesign
           =========================================================== */
        .date-btn {
            position: relative;
            overflow: hidden;
            transition:
                border-color .2s ease,
                background-color .25s ease,
                color .25s ease,
                transform .2s ease,
                box-shadow .25s ease;
        }
        .date-btn:hover { transform: translateY(-2px); }
        .date-btn.is-today::before {
            content: 'Hoy';
            position: absolute;
            top: -7px; right: -2px;
            font-size: 9px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #D97706, #B45309);
            padding: 1px 6px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .date-btn.is-weekend { background: rgba(217,119,6,.04); }
        .date-btn.is-selected {
            background: linear-gradient(135deg, #D97706, #B45309) !important;
            border-color: #D97706 !important;
            color: white !important;
            box-shadow: 0 10px 22px -10px rgba(217,119,6,.55);
            transform: translateY(-2px);
        }
        .date-btn.is-selected span { color: white !important; }

        /* ===========================================================
           Slot button
           =========================================================== */
        .slot-btn {
            position: relative;
            overflow: hidden;
            transition:
                background-color .2s ease,
                border-color .2s ease,
                color .2s ease,
                transform .15s ease,
                box-shadow .25s ease;
            animation: citora-fade-up .35s cubic-bezier(.22,1,.36,1) both;
        }
        .slot-btn:hover {
            transform: translateY(-1px);
            border-color: #fbbf24;
            box-shadow: 0 6px 14px -8px rgba(217,119,6,.4);
        }
        .slot-btn:active { transform: scale(.97); }
        .slot-btn.selected {
            background-color: #D97706;
            color: white;
            border-color: #D97706;
            box-shadow: 0 8px 18px -8px rgba(217,119,6,.55);
            animation: citora-check-pop .35s cubic-bezier(.22,1,.36,1);
        }

        /* Skeleton slot placeholder */
        .slot-skeleton {
            height: 42px;
            border-radius: 8px;
        }

        /* Progress steps */
        .progress-step span:first-child {
            transition: background-color .35s ease, color .35s ease, transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s ease;
        }
        .progress-step.is-active span:first-child {
            box-shadow: 0 0 0 4px rgba(217,119,6,.12);
        }
        .progress-step.just-activated span:first-child {
            animation: citora-check-pop .4s cubic-bezier(.22,1,.36,1);
        }
        .progress-connector {
            position: relative;
            overflow: hidden;
            background-color: #e5e7eb;
        }
        .progress-connector::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, #D97706, #B45309);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .5s cubic-bezier(.22,1,.36,1);
        }
        .progress-connector.is-filled::after { transform: scaleX(1); }

        /* ===========================================================
           Confirm card
           =========================================================== */
        .confirm-card {
            position: relative;
            overflow: hidden;
        }
        .confirm-card::before {
            content: '';
            position: absolute;
            top: -50%; right: -30%;
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(217,119,6,.08), transparent 65%);
            pointer-events: none;
        }
        .confirm-row {
            display: flex; justify-content: space-between; align-items: center;
            position: relative;
        }

        /* ===========================================================
           Primary CTA
           =========================================================== */
        #btn-continue, #btn-book {
            transition: background-color .2s ease, transform .12s ease, box-shadow .25s ease;
        }
        #btn-continue:not(:disabled):hover,
        #btn-book:not(:disabled):hover {
            box-shadow: 0 14px 28px -14px rgba(217,119,6,.55);
        }
        #btn-continue:not(:disabled):active,
        #btn-book:not(:disabled):active {
            transform: scale(.98);
        }
        #btn-continue:not(:disabled) {
            animation: citora-glow 2.4s ease-in-out infinite;
        }

        /* ===========================================================
           Success overlay
           =========================================================== */
        .biz-success-overlay {
            position: fixed; inset: 0; z-index: 9999;
            display: none;
            align-items: center; justify-content: center;
            background: radial-gradient(circle at center, rgba(15,23,42,.92), rgba(15,23,42,.98));
            backdrop-filter: blur(12px);
            animation: citora-fade-in .4s ease both;
        }
        .biz-success-overlay.is-open { display: flex; }
        .biz-success-card {
            background: white;
            border-radius: 28px;
            padding: 40px 32px;
            text-align: center;
            max-width: 360px;
            margin: 16px;
            animation: citora-scale-in .55s cubic-bezier(.22,1,.36,1) both;
            box-shadow: 0 24px 60px -20px rgba(0,0,0,.5);
        }
        .biz-success-icon {
            width: 84px; height: 84px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10B981, #059669);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 14px 36px -10px rgba(16,185,129,.5);
        }
        .biz-success-icon svg {
            width: 44px; height: 44px;
            stroke: white;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
            animation: biz-success-check .6s cubic-bezier(.22,1,.36,1) .25s forwards;
        }
    </style>
</head>
<body style="min-height:100vh">
    @include('partials.gtm-body')
    {{-- Top bar for authenticated users --}}
    @auth
    <div style="background:white;border-bottom:1px solid #E7E5DF;padding:8px 16px">
        <div style="max-width:700px;margin:0 auto;display:flex;align-items:center;justify-content:space-between">
            <a href="/" style="display:flex;align-items:center;gap:6px;text-decoration:none">
                <img src="@vasset('images/logo-mark.png')" alt="Citora" style="height:26px">
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
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
</body>
</html>
