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

        /* Steps: cross-fade entrance */
        .step { display: none; }
        .step.active {
            display: block;
            animation: citora-fade-up .45s cubic-bezier(.22,1,.36,1) both;
        }

        /* Service card */
        .service-card {
            transition:
                border-color .25s ease,
                box-shadow .25s ease,
                transform .25s cubic-bezier(.22,1,.36,1);
        }
        .service-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -16px rgba(15,23,42,.18);
        }
        .service-card.selected {
            border-color: #D97706;
            box-shadow: 0 0 0 2px #D97706, 0 12px 26px -16px rgba(217,119,6,.35);
        }

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

        /* Date pill */
        .date-btn {
            transition:
                border-color .2s ease,
                background-color .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }
        .date-btn:hover { transform: translateY(-2px); }
        .date-btn.is-selected {
            box-shadow: 0 8px 20px -10px rgba(217,119,6,.4);
        }

        /* Slot button */
        .slot-btn {
            transition:
                background-color .2s ease,
                border-color .2s ease,
                color .2s ease,
                transform .15s ease,
                box-shadow .2s ease;
            animation: citora-fade-up .35s cubic-bezier(.22,1,.36,1) both;
        }
        .slot-btn:hover { transform: translateY(-1px); border-color: #fbbf24; }
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

        /* Progress steps: animated transitions */
        #progress-1 span:first-child,
        #progress-2 span:first-child,
        #progress-3 span:first-child {
            transition: background-color .35s ease, color .35s ease, transform .35s cubic-bezier(.22,1,.36,1);
        }
        .progress-active-pop {
            animation: citora-check-pop .4s cubic-bezier(.22,1,.36,1);
        }

        /* Floating label inputs (already styled in animations.css via .cit-float-label) */

        /* Primary CTAs ripple-like press */
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
