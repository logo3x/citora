<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citora — La forma inteligente de gestionar tu agenda</title>
    <meta name="description" content="Plataforma SaaS para gestión de citas en barberías, salones de belleza y centros estéticos. Reservas online, WhatsApp automático y panel de control.">
    <link rel="icon" href="/images/logo-light.png" type="image/png">
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --amber: #D97706;
            --amber-light: #F59E0B;
            --teal: #0D9488;
            --slate-900: #0F172A;
            --slate-800: #1E293B;
            --slate-700: #334155;
            --slate-400: #94A3B8;
            --slate-300: #CBD5E1;
            --cream: #FAFAF8;
            --border: #E7E5DF;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html {
            scroll-behavior: smooth;
            scroll-snap-type: y proximity;
            scroll-padding-top: 72px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--cream); color: #111; overflow-x: hidden; }
        h1, h2, h3, h4 { font-family: 'Poppins', sans-serif; }
        section {
            scroll-snap-align: start;
            scroll-snap-stop: normal;
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; scroll-snap-type: none; }
            .animate-fade-up, .animate-fade-up-delay-1, .animate-fade-up-delay-2,
            .animate-fade-up-delay-3, .animate-fade-in, .reveal { animation: none !important; transition: none !important; }
            .hero-orb, .hero-section { animation: none !important; }
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(3deg); }
        }
        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(20px) rotate(-3deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(217,119,6,0.15); }
            50% { box-shadow: 0 0 40px rgba(217,119,6,0.3); }
        }
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes scroll-hint {
            0%, 100% { opacity: 1; transform: translateY(0); }
            50% { opacity: 0.5; transform: translateY(8px); }
        }
        @keyframes slide-in-left {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes counter-up {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-fade-up { animation: fadeUp 0.7s ease-out both; }
        .animate-fade-up-delay-1 { animation: fadeUp 0.7s ease-out 0.15s both; }
        .animate-fade-up-delay-2 { animation: fadeUp 0.7s ease-out 0.3s both; }
        .animate-fade-up-delay-3 { animation: fadeUp 0.7s ease-out 0.45s both; }
        .animate-fade-in { animation: fadeIn 1s ease-out both; }

        /* Scroll animations */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Nav */
        .nav-glass {
            position: fixed; top: 0; width: 100%; z-index: 50;
            background: rgba(250,250,248,0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(231,229,223,0.6);
            transition: all 0.3s ease;
        }
        .nav-glass.scrolled {
            background: rgba(250,250,248,0.95);
            box-shadow: 0 1px 20px rgba(0,0,0,0.06);
        }

        /* Hero */
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, #0a0f1e 0%, #0F172A 30%, #1a2744 60%, #0F172A 100%);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
            padding: 120px 16px 80px;
            overflow: hidden;
        }
        .hero-mesh {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 600px 400px at 20% 50%, rgba(217,119,6,0.12), transparent),
                radial-gradient(ellipse 500px 300px at 80% 30%, rgba(13,148,136,0.08), transparent),
                radial-gradient(ellipse 300px 300px at 50% 80%, rgba(37,99,235,0.06), transparent);
            pointer-events: none;
        }
        .hero-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
        }
        .hero-orb-1 {
            width: 300px; height: 300px;
            background: rgba(217,119,6,0.15);
            top: -50px; right: -50px;
            animation: float 8s ease-in-out infinite;
        }
        .hero-orb-2 {
            width: 200px; height: 200px;
            background: rgba(13,148,136,0.1);
            bottom: -30px; left: -30px;
            animation: float-reverse 10s ease-in-out infinite;
        }

        /* Search */
        .search-wrapper {
            position: relative;
            max-width: 560px;
            margin: 32px auto 0;
            z-index: 20;
        }
        .search-box {
            display: flex; align-items: center;
            background: rgba(255,255,255,0.97);
            border-radius: 16px;
            padding: 5px 5px 5px 18px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.1);
            transition: box-shadow 0.3s, transform 0.3s;
        }
        .search-box:focus-within {
            box-shadow: 0 12px 50px rgba(0,0,0,0.3), 0 0 0 2px rgba(217,119,6,0.4);
            transform: translateY(-2px);
        }
        .search-box input {
            flex: 1; border: none; outline: none;
            font-size: 15px; padding: 14px 12px;
            background: transparent; font-family: Inter, sans-serif;
            color: #111;
        }
        .search-box input::placeholder { color: #9ca3af; }
        .search-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #D97706, #B45309);
            color: white; font-weight: 700; font-size: 14px;
            border-radius: 12px; border: none; cursor: pointer;
            font-family: Inter, sans-serif;
            transition: all 0.2s;
        }
        .search-btn:hover { filter: brightness(1.1); transform: scale(1.02); }

        #search-results {
            display: none; position: absolute;
            top: 100%; left: 0; right: 0;
            margin-top: 8px;
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            max-height: 380px; overflow-y: auto;
            z-index: 10;
        }

        /* Cards */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        }

        /* Service card */
        .service-card {
            flex-shrink: 0; width: 280px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            text-decoration: none;
            transition: all 0.35s ease;
        }
        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            border-color: rgba(217,119,6,0.3);
        }
        .service-card .card-img {
            position: relative; overflow: hidden;
            height: 160px;
        }
        .service-card .card-img img,
        .service-card .card-img .img-placeholder {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.5s ease;
        }
        .service-card:hover .card-img img,
        .service-card:hover .card-img .img-placeholder {
            transform: scale(1.08);
        }
        .service-card .card-img::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, transparent 50%, rgba(0,0,0,0.06) 100%);
        }
        .service-card .price-badge {
            position: absolute; top: 12px; right: 12px;
            background: rgba(15,23,42,0.85);
            backdrop-filter: blur(8px);
            color: var(--amber-light);
            font-weight: 700; font-size: 13px;
            padding: 4px 10px;
            border-radius: 8px;
            z-index: 2;
        }

        /* Business card */
        .biz-card {
            flex-shrink: 0; width: 300px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            text-decoration: none;
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }
        .biz-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--amber), var(--teal));
            opacity: 0; transition: opacity 0.3s;
        }
        .biz-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        }
        .biz-card:hover::before { opacity: 1; }

        /* Feature bento */
        .bento-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .bento-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        .bento-card::before {
            content: ''; position: absolute;
            top: -50%; right: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(217,119,6,0.04) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.4s;
            pointer-events: none;
        }
        .bento-card:hover { border-color: rgba(217,119,6,0.2); }
        .bento-card:hover::before { opacity: 1; }
        .bento-full {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--slate-900), var(--slate-800));
            border-color: transparent;
            display: flex;
            gap: 40px;
            align-items: center;
            padding: 36px 40px;
        }
        .bento-full:hover { border-color: transparent; }
        .bento-full h3, .bento-full p { color: white !important; }
        .bento-full p { color: var(--slate-400) !important; }

        /* Steps */
        .step-card {
            text-align: center; position: relative;
            padding: 32px 24px;
        }
        .step-number {
            width: 64px; height: 64px;
            border-radius: 20px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 800;
            font-family: Poppins, sans-serif;
            margin-bottom: 20px;
            position: relative;
        }
        .step-connector {
            position: absolute;
            top: 58px; left: 60%; right: -40%;
            height: 2px;
            background: repeating-linear-gradient(90deg, var(--border) 0, var(--border) 8px, transparent 8px, transparent 16px);
        }

        /* Pricing */
        .pricing-card {
            background: white;
            border-radius: 24px;
            padding: 36px;
            position: relative;
            transition: all 0.4s ease;
        }
        .pricing-featured {
            border: 2px solid var(--amber);
            animation: pulse-glow 3s ease-in-out infinite;
        }
        .pricing-featured:hover {
            animation: none;
            box-shadow: 0 20px 60px rgba(217,119,6,0.2);
            transform: translateY(-4px);
        }
        .pricing-regular {
            border: 1px solid var(--border);
        }
        .pricing-regular:hover {
            border-color: rgba(13,148,136,0.3);
            box-shadow: 0 20px 60px rgba(0,0,0,0.06);
            transform: translateY(-4px);
        }

        /* Segments */
        .segment-card {
            padding: 28px 20px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            text-align: center;
            transition: all 0.35s ease;
            cursor: default;
        }
        .segment-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            border-color: var(--amber);
        }
        .segment-card:hover .segment-icon {
            transform: scale(1.15);
        }
        .segment-icon {
            display: inline-block;
            font-size: 42px;
            margin-bottom: 12px;
            transition: transform 0.35s ease;
        }

        /* CTA section */
        .cta-section {
            position: relative;
            background: linear-gradient(135deg, #0a0f1e, #0F172A, #1a2744);
            overflow: hidden;
        }
        .cta-section::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 400px 300px at 30% 50%, rgba(217,119,6,0.1), transparent),
                radial-gradient(ellipse 300px 200px at 70% 50%, rgba(13,148,136,0.08), transparent);
            pointer-events: none;
        }
        .cta-btn {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 36px;
            background: linear-gradient(135deg, #D97706, #B45309);
            color: white; font-weight: 700; font-size: 17px;
            border-radius: 14px; text-decoration: none;
            font-family: Inter, sans-serif;
            transition: all 0.3s;
            box-shadow: 0 8px 30px rgba(217,119,6,0.3);
        }
        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(217,119,6,0.4);
            filter: brightness(1.1);
        }

        /* Footer */
        .footer-link {
            color: var(--slate-400);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        .footer-link:hover { color: var(--amber-light); }

        /* Carousel scroll */
        .carousel-track {
            overflow-x: auto;
            padding: 8px 16px 20px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .carousel-track::-webkit-scrollbar { display: none; }

        /* Responsive */
        @media (max-width: 1024px) {
            .bento-grid { grid-template-columns: 1fr; }
            .bento-full { padding: 32px 28px; gap: 28px; }
            section[style*="padding:80px"] { padding-top: 64px !important; padding-bottom: 64px !important; }
        }
        @media (max-width: 768px) {
            .bento-grid { grid-template-columns: 1fr; }
            .bento-full {
                grid-column: span 1;
                flex-direction: column;
                gap: 16px;
                padding: 28px;
                text-align: center;
            }
            .hero-section { padding: 100px 16px 60px; }
            .nav-actions .nav-label { display: none; }
            .steps-grid { flex-direction: column; }
            .step-connector { display: none; }
            .pricing-grid { grid-template-columns: 1fr !important; }
            .pricing-card { padding: 28px !important; }
            .segments-grid { grid-template-columns: repeat(2, 1fr) !important; }
            section[style*="padding:80px"] { padding-top: 56px !important; padding-bottom: 56px !important; }
            section[style*="padding:64px"] { padding-top: 48px !important; padding-bottom: 36px !important; }
        }
        @media (max-width: 640px) {
            .hero-section { padding: 96px 14px 52px; }
            .search-box { padding: 4px 4px 4px 14px; border-radius: 14px; }
            .search-box input { font-size: 14px; padding: 12px 10px; }
            .search-btn { padding: 10px 16px; font-size: 13px; }
            .bento-card { padding: 24px; border-radius: 18px; }
            .pricing-card { padding: 24px !important; border-radius: 20px !important; }
            .segment-card { padding: 20px 14px; }
            .segment-icon { font-size: 36px; }
            .cta-btn { padding: 14px 28px; font-size: 15px; }
        }
        @media (max-width: 480px) {
            .segments-grid { grid-template-columns: 1fr !important; }
            .service-card, .biz-card { width: calc(85vw - 16px) !important; max-width: 280px; }
            .carousel-track { padding: 8px 14px 20px; }
            section[style*="padding:80px 16px"] { padding-left: 14px !important; padding-right: 14px !important; }
            .cta-section { padding: 72px 14px !important; }
        }
        @media (max-width: 380px) {
            .search-btn { padding: 8px 12px; font-size: 12px; }
            .bento-card { padding: 20px; }
            .pricing-card { padding: 20px !important; }
        }
    </style>
</head>
<body class="antialiased">

    {{-- Navigation --}}
    <nav class="nav-glass" id="main-nav">
        <div style="max-width:1140px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;justify-content:space-between">
            <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none">
                <img src="/images/logo-light.png" alt="Citora" style="height:30px;mix-blend-mode:multiply" onerror="this.style.display='none'">
                <span style="font-size:20px;font-weight:800;color:var(--slate-900);font-family:Poppins,sans-serif;letter-spacing:-0.02em">Citora</span>
            </a>
            <div class="nav-actions" style="display:flex;align-items:center;gap:8px;font-size:13px">
                @auth
                    <a href="{{ route('customer.appointments') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;color:var(--slate-700);text-decoration:none;font-weight:500;border:1px solid var(--border);border-radius:10px;transition:all 0.2s"
                       onmouseover="this.style.borderColor='var(--amber)';this.style.color='var(--amber)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--slate-700)'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="nav-label">Mis citas</span>
                    </a>
                    @if(auth()->user()->business_id)
                        <a href="{{ filament()->getUrl() }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:linear-gradient(135deg,#D97706,#B45309);color:white;font-weight:600;border-radius:10px;text-decoration:none;transition:all 0.2s;font-size:13px"
                           onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="nav-label">Mi panel</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('auth.google.redirect', ['redirect_to' => '/mis-citas']) }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;color:var(--slate-700);text-decoration:none;font-weight:500;border:1px solid var(--border);border-radius:10px;transition:all 0.2s"
                       onmouseover="this.style.borderColor='var(--amber)';this.style.color='var(--amber)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--slate-700)'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="nav-label">Mis citas</span>
                    </a>
                    <a href="{{ route('auth.google.redirect') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:linear-gradient(135deg,#D97706,#B45309);color:white;font-weight:600;border-radius:10px;text-decoration:none;transition:all 0.2s;font-size:13px"
                       onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span class="nav-label">Registra tu negocio</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero-section">
        <div class="hero-mesh"></div>
        <div class="hero-grid"></div>
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>

        <div style="position:relative;z-index:2;max-width:720px;margin:0 auto;text-align:center">
            {{-- Pill badge --}}
            <div class="animate-fade-up" style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px 6px 8px;background:rgba(217,119,6,0.12);border:1px solid rgba(217,119,6,0.2);border-radius:999px;margin-bottom:24px">
                <span style="background:var(--amber);color:white;font-size:10px;font-weight:700;padding:3px 8px;border-radius:999px;text-transform:uppercase;letter-spacing:0.05em">Nuevo</span>
                <span style="font-size:13px;color:rgba(255,255,255,0.8)">Agenda inteligente para negocios</span>
            </div>

            <h1 class="animate-fade-up-delay-1" style="font-size:clamp(32px,5.5vw,56px);font-weight:900;color:white;line-height:1.08;letter-spacing:-0.03em">
                Reserva tu cita
                <span style="background:linear-gradient(135deg,#F59E0B,#D97706,#0D9488);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">en segundos</span>
            </h1>

            <p class="animate-fade-up-delay-2" style="margin-top:16px;font-size:18px;color:var(--slate-400);max-width:480px;margin-left:auto;margin-right:auto;line-height:1.6">
                Encuentra tu barbería, salón o spa favorito y agenda online. Sin llamadas, sin esperas.
            </p>

            {{-- Search --}}
            <div class="search-wrapper animate-fade-up-delay-3">
                <div class="search-box">
                    <svg width="20" height="20" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="flex-shrink:0"><circle cx="11" cy="11" r="8" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
                    <input type="text" id="search-input" placeholder="Buscar negocio o servicio..." autocomplete="off">
                    <button class="search-btn" onclick="document.getElementById('search-input').value && (window.location.hash='resultados')">Buscar</button>
                </div>
                <div id="search-results"></div>
            </div>

            {{-- CTAs --}}
            <div class="animate-fade-up-delay-3" style="display:flex;justify-content:center;gap:12px;margin-top:28px;flex-wrap:wrap">
                <a href="{{ route('auth.google.redirect') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:linear-gradient(135deg,#D97706,#B45309);color:white;font-weight:700;border-radius:12px;text-decoration:none;font-size:14px;transition:all 0.3s;box-shadow:0 4px 20px rgba(217,119,6,0.3)"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 30px rgba(217,119,6,0.4)'" onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 20px rgba(217,119,6,0.3)'">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Registra tu negocio gratis
                </a>
                @auth
                    <a href="{{ route('customer.appointments') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);font-weight:600;border-radius:12px;text-decoration:none;font-size:14px;transition:all 0.2s;background:rgba(255,255,255,0.04)"
                       onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.borderColor='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.borderColor='rgba(255,255,255,0.15)'">
                        Consultar mis citas
                    </a>
                @else
                    <a href="{{ route('auth.google.redirect', ['redirect_to' => '/mis-citas']) }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);font-weight:600;border-radius:12px;text-decoration:none;font-size:14px;transition:all 0.2s;background:rgba(255,255,255,0.04)"
                       onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.borderColor='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.borderColor='rgba(255,255,255,0.15)'">
                        Consultar mis citas
                    </a>
                @endauth
            </div>

            {{-- Scroll hint --}}
            <div style="margin-top:48px;animation:scroll-hint 2s ease-in-out infinite">
                <svg width="24" height="24" fill="none" stroke="rgba(255,255,255,0.3)" viewBox="0 0 24 24" style="margin:0 auto"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </div>
        </div>
    </section>

    {{-- Search JS --}}
    <script>
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            if (q.length < 2) { searchResults.style.display = 'none'; return; }

            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`/buscar?q=${encodeURIComponent(q)}`);
                    const data = await res.json();
                    let html = '';

                    if (data.services.length > 0) {
                        html += '<div style="padding:12px 18px 6px;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em">Servicios</div>';
                        data.services.forEach(s => {
                            html += `<a href="/${s.slug}" style="display:flex;align-items:center;gap:12px;padding:10px 18px;text-decoration:none;transition:background 0.15s"
                                        onmouseover="this.style.background='#f8f8f6'" onmouseout="this.style.background='white'">
                                ${s.image ? `<img src="${s.image}" style="width:40px;height:40px;border-radius:10px;object-fit:cover">` : '<span style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,rgba(217,119,6,0.1),rgba(13,148,136,0.1));display:flex;align-items:center;justify-content:center;font-size:18px">&#9986;</span>'}
                                <div style="flex:1;min-width:0">
                                    <p style="font-weight:600;font-size:14px;color:#0F172A;margin:0">${s.name}</p>
                                    <p style="font-size:12px;color:#6b7280;margin:2px 0 0">${s.business} &middot; ${s.duration} min &middot; $${Number(s.price).toLocaleString()}</p>
                                </div>
                            </a>`;
                        });
                    }

                    if (data.businesses.length > 0) {
                        html += '<div style="padding:12px 18px 6px;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.08em">Negocios</div>';
                        data.businesses.forEach(b => {
                            html += `<a href="/${b.slug}" style="display:flex;align-items:center;gap:12px;padding:10px 18px;text-decoration:none;transition:background 0.15s"
                                        onmouseover="this.style.background='#f8f8f6'" onmouseout="this.style.background='white'">
                                ${b.logo ? `<img src="${b.logo}" style="width:40px;height:40px;border-radius:10px;object-fit:cover">` : '<span style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#D97706,#B45309);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:15px">' + b.name[0] + '</span>'}
                                <div style="flex:1;min-width:0">
                                    <p style="font-weight:600;font-size:14px;color:#0F172A;margin:0">${b.name}</p>
                                    <p style="font-size:12px;color:#6b7280;margin:2px 0 0">${b.address || 'Sin direcci\u00f3n'}</p>
                                </div>
                            </a>`;
                        });
                    }

                    if (!html) html = '<p style="padding:24px;text-align:center;color:#9ca3af;font-size:14px">No se encontraron resultados</p>';

                    searchResults.innerHTML = html;
                    searchResults.style.display = 'block';
                } catch(e) { searchResults.style.display = 'none'; }
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    </script>

    {{-- Services carousel --}}
    @if($services->count() > 0)
    <section style="padding:64px 0 48px" class="reveal">
        <div style="max-width:1140px;margin:0 auto;padding:0 16px">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(217,119,6,0.08);border-radius:8px;margin-bottom:8px">
                        <span style="width:6px;height:6px;border-radius:50%;background:var(--amber)"></span>
                        <span style="font-size:12px;font-weight:600;color:var(--amber);text-transform:uppercase;letter-spacing:0.06em">Explora</span>
                    </div>
                    <h2 style="font-size:28px;font-weight:800;color:var(--slate-900);letter-spacing:-0.02em">Servicios disponibles</h2>
                </div>
                <p style="font-size:13px;color:var(--slate-400);display:none">Desliza para ver más &rarr;</p>
            </div>
        </div>
        <div class="carousel-track">
            <div style="display:flex;gap:18px;max-width:1140px;margin:0 auto;padding:0 16px">
                @foreach($services as $service)
                <a href="{{ route('booking.show', $service->business->slug) }}" class="service-card">
                    <div class="card-img">
                        @if($service->getFirstMediaUrl('image'))
                            <img src="{{ $service->getFirstMediaUrl('image') }}" alt="{{ $service->name }}">
                        @else
                            <div class="img-placeholder" style="background:linear-gradient(135deg,rgba(217,119,6,0.08),rgba(13,148,136,0.08));display:flex;align-items:center;justify-content:center;font-size:42px">&#9986;</div>
                        @endif
                        <span class="price-badge">${{ number_format($service->price) }}</span>
                    </div>
                    <div style="padding:14px 16px 16px">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                            @if($service->business->getFirstMediaUrl('logo'))
                                <img src="{{ $service->business->getFirstMediaUrl('logo') }}" alt="" style="width:22px;height:22px;border-radius:6px;object-fit:cover;border:1px solid var(--border)">
                            @else
                                <span style="width:22px;height:22px;border-radius:6px;background:rgba(217,119,6,0.1);display:flex;align-items:center;justify-content:center;color:var(--amber);font-weight:700;font-size:10px">{{ substr($service->business->name, 0, 1) }}</span>
                            @endif
                            <span style="font-size:12px;color:#6b7280;font-weight:500">{{ $service->business->name }}</span>
                        </div>
                        <p style="font-weight:700;font-size:15px;color:var(--slate-900);margin-bottom:6px">{{ $service->name }}</p>
                        <div style="display:flex;align-items:center;gap:4px;font-size:12px;color:var(--slate-400)">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                            {{ $service->duration_minutes }} min
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Businesses carousel --}}
    @if($businesses->count() > 0)
    <section style="padding:48px 0 64px;background:white;border-top:1px solid var(--border);border-bottom:1px solid var(--border)" class="reveal">
        <div style="max-width:1140px;margin:0 auto;padding:0 16px">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(13,148,136,0.08);border-radius:8px;margin-bottom:8px">
                        <span style="width:6px;height:6px;border-radius:50%;background:var(--teal)"></span>
                        <span style="font-size:12px;font-weight:600;color:var(--teal);text-transform:uppercase;letter-spacing:0.06em">Directorio</span>
                    </div>
                    <h2 style="font-size:28px;font-weight:800;color:var(--slate-900);letter-spacing:-0.02em">Negocios en Citora</h2>
                </div>
            </div>
        </div>
        <div class="carousel-track">
            <div style="display:flex;gap:18px;max-width:1140px;margin:0 auto;padding:0 16px">
                @foreach($businesses as $business)
                <a href="{{ route('booking.show', $business->slug) }}" class="biz-card">
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
                        @if($business->getFirstMediaUrl('logo'))
                            <img src="{{ $business->getFirstMediaUrl('logo') }}" alt="{{ $business->name }}" style="width:52px;height:52px;border-radius:14px;object-fit:cover;border:1px solid var(--border)">
                        @else
                            <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,rgba(217,119,6,0.12),rgba(217,119,6,0.06));display:flex;align-items:center;justify-content:center;color:var(--amber);font-weight:800;font-size:20px;font-family:Poppins">{{ substr($business->name, 0, 1) }}</div>
                        @endif
                        <div style="flex:1;min-width:0">
                            <p style="font-weight:700;font-size:16px;color:var(--slate-900);margin:0">{{ $business->name }}</p>
                            @if($business->address)
                                <p style="font-size:12px;color:#6b7280;margin:3px 0 0;display:flex;align-items:center;gap:4px">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ Str::limit($business->address, 35) }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;gap:16px;font-size:12px;color:#6b7280;margin-bottom:16px">
                        <span style="display:flex;align-items:center;gap:4px">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            {{ $business->services_count }} servicios
                        </span>
                        <span style="display:flex;align-items:center;gap:4px">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            {{ $business->employees_count }} profesionales
                        </span>
                    </div>
                    <div style="padding-top:14px;border-top:1px solid var(--border);text-align:center">
                        <span style="font-size:13px;font-weight:700;color:var(--teal);display:inline-flex;align-items:center;gap:6px;transition:gap 0.2s">
                            Reservar cita
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Features Bento Grid --}}
    <section style="padding:80px 16px" class="reveal">
        <div style="max-width:1000px;margin:0 auto">
            <div style="text-align:center;margin-bottom:48px">
                <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(217,119,6,0.08);border-radius:8px;margin-bottom:12px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--amber)"></span>
                    <span style="font-size:12px;font-weight:600;color:var(--amber);text-transform:uppercase;letter-spacing:0.06em">Funcionalidades</span>
                </div>
                <h2 style="font-size:clamp(28px,4vw,40px);font-weight:800;color:var(--slate-900);letter-spacing:-0.02em">Todo lo que necesitas para crecer</h2>
                <p style="color:#6b7280;margin-top:10px;font-size:17px;max-width:500px;margin-left:auto;margin-right:auto">Herramientas diseñadas para negocios reales. Sin complicaciones.</p>
            </div>

            <div class="bento-grid">
                {{-- Row 1: 2 equal cards --}}
                <div class="bento-card">
                    <div style="width:48px;height:48px;background:rgba(245,158,11,0.12);border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px">
                        <svg width="24" height="24" fill="none" stroke="#F59E0B" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--slate-900);margin-bottom:6px">Tu página de reservas</h3>
                    <p style="color:#6b7280;font-size:14px;line-height:1.6">Enlace personalizado para tu negocio. Compártelo en redes, WhatsApp o donde quieras.</p>
                </div>

                <div class="bento-card">
                    <div style="width:48px;height:48px;background:rgba(13,148,136,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px">
                        <svg width="24" height="24" fill="var(--teal)" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                    </div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--slate-900);margin-bottom:6px">WhatsApp automático</h3>
                    <p style="color:#6b7280;font-size:14px;line-height:1.6">Confirmaciones, recordatorios 24h y 1h antes, cancelaciones. Todo automático.</p>
                </div>

                {{-- Row 2: Full-width dark card --}}
                <div class="bento-card bento-full">
                    <div style="flex:1">
                        <h3 style="font-size:22px;font-weight:800;margin-bottom:8px">Anti-cruces inteligente</h3>
                        <p style="font-size:15px;line-height:1.6">Nunca más citas cruzadas. Validación en tiempo real por profesional y horario. Tu agenda siempre organizada.</p>
                    </div>
                    <div style="flex-shrink:0;width:64px;height:64px;background:rgba(37,99,235,0.12);border-radius:18px;display:flex;align-items:center;justify-content:center">
                        <svg width="32" height="32" fill="none" stroke="#60a5fa" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>

                {{-- Row 3: 2 equal cards --}}
                <div class="bento-card">
                    <div style="width:48px;height:48px;background:rgba(217,119,6,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px">
                        <svg width="24" height="24" fill="none" stroke="var(--amber)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--slate-900);margin-bottom:6px">Gestión de equipo</h3>
                    <p style="color:#6b7280;font-size:14px;line-height:1.6">Empleados, servicios asignados y horarios individuales. Todo bajo tu control.</p>
                </div>

                <div class="bento-card">
                    <div style="width:48px;height:48px;background:rgba(13,148,136,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px">
                        <svg width="24" height="24" fill="none" stroke="var(--teal)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--slate-900);margin-bottom:6px">Métricas en tiempo real</h3>
                    <p style="color:#6b7280;font-size:14px;line-height:1.6">Citas del día, ingresos mensuales, ocupación. Panel optimizado para celular.</p>
                </div>

                {{-- Row 4: Full-width dark card --}}
                <div class="bento-card bento-full">
                    <div style="flex-shrink:0;width:64px;height:64px;background:rgba(245,158,11,0.12);border-radius:18px;display:flex;align-items:center;justify-content:center">
                        <svg width="32" height="32" fill="none" stroke="#F59E0B" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div style="flex:1">
                        <h3 style="font-size:22px;font-weight:800;margin-bottom:8px">Mobile-first, desde cualquier lugar</h3>
                        <p style="font-size:15px;line-height:1.6">Tus clientes reservan desde el celular en segundos. Tú gestionas tu negocio estés donde estés.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section style="padding:80px 16px;background:white;border-top:1px solid var(--border);border-bottom:1px solid var(--border)" class="reveal">
        <div style="max-width:900px;margin:0 auto">
            <div style="text-align:center;margin-bottom:56px">
                <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(13,148,136,0.08);border-radius:8px;margin-bottom:12px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--teal)"></span>
                    <span style="font-size:12px;font-weight:600;color:var(--teal);text-transform:uppercase;letter-spacing:0.06em">Proceso</span>
                </div>
                <h2 style="font-size:clamp(28px,4vw,40px);font-weight:800;color:var(--slate-900);letter-spacing:-0.02em">Listo en 3 pasos</h2>
            </div>

            <div class="steps-grid" style="display:flex;gap:0;justify-content:center">
                <div class="step-card" style="flex:1;max-width:280px">
                    <div class="step-number" style="background:var(--slate-900);color:var(--amber-light)">1</div>
                    <div class="step-connector"></div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--slate-900);margin-bottom:8px">Crea tu cuenta</h3>
                    <p style="font-size:14px;color:#6b7280;line-height:1.6">Regístrate con Google en segundos. Sin formularios largos ni verificaciones.</p>
                </div>
                <div class="step-card" style="flex:1;max-width:280px">
                    <div class="step-number" style="background:var(--slate-900);color:var(--amber-light)">2</div>
                    <div class="step-connector"></div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--slate-900);margin-bottom:8px">Configura tu negocio</h3>
                    <p style="font-size:14px;color:#6b7280;line-height:1.6">Wizard guiado: servicios, empleados, horarios e imágenes. En 5 minutos.</p>
                </div>
                <div class="step-card" style="flex:1;max-width:280px">
                    <div class="step-number" style="background:linear-gradient(135deg,var(--amber),#B45309);color:white">3</div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--slate-900);margin-bottom:8px">Comparte tu enlace</h3>
                    <p style="font-size:14px;color:#6b7280;line-height:1.6">Tus clientes reservan online. Tú recibes todo en tu panel y WhatsApp.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section style="padding:80px 16px" class="reveal">
        <div style="max-width:960px;margin:0 auto">
            <div style="text-align:center;margin-bottom:48px">
                <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(37,99,235,0.08);border-radius:8px;margin-bottom:12px">
                    <span style="width:6px;height:6px;border-radius:50%;background:#2563EB"></span>
                    <span style="font-size:12px;font-weight:600;color:#2563EB;text-transform:uppercase;letter-spacing:0.06em">Precios</span>
                </div>
                <h2 style="font-size:clamp(28px,4vw,40px);font-weight:800;color:var(--slate-900);letter-spacing:-0.02em">Simple y transparente</h2>
                <p style="color:#6b7280;margin-top:10px;font-size:17px">Sin suscripciones. Paga solo si lo necesitas.</p>
            </div>

            <div class="pricing-grid" style="display:grid;grid-template-columns:repeat(3, 1fr);gap:20px">
                {{-- Free --}}
                <div class="pricing-card pricing-featured">
                    <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%)">
                        <span style="background:linear-gradient(135deg,#D97706,#B45309);color:white;font-size:11px;font-weight:700;padding:5px 16px;border-radius:999px;text-transform:uppercase;letter-spacing:0.05em">Popular</span>
                    </div>
                    <h3 style="font-size:18px;font-weight:800;color:var(--slate-900);margin-bottom:4px">Gratis</h3>
                    <div style="font-size:42px;font-weight:900;color:var(--slate-900);font-family:Poppins;letter-spacing:-0.03em;line-height:1.1">$0</div>
                    <p style="color:#6b7280;margin-bottom:24px;font-size:13px">Para siempre</p>
                    <div style="display:flex;flex-direction:column;gap:12px">
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            200 citas al mes
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Página de reservas
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            WhatsApp automático
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Panel completo
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Empleados ilimitados
                        </div>
                    </div>
                </div>

                {{-- Monthly --}}
                <div class="pricing-card pricing-regular">
                    <h3 style="font-size:18px;font-weight:800;color:var(--slate-900);margin-bottom:4px">Mensual</h3>
                    <div style="font-size:42px;font-weight:900;color:var(--slate-900);font-family:Poppins;letter-spacing:-0.03em;line-height:1.1">$34.900</div>
                    <p style="color:#6b7280;margin-bottom:24px;font-size:13px">Pago único por mes</p>
                    <div style="display:flex;flex-direction:column;gap:12px">
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>Citas ilimitadas</strong>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Todo del plan gratis
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Sin suscripción
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Paga cuando lo necesites
                        </div>
                    </div>
                </div>

                {{-- Semester --}}
                <div class="pricing-card pricing-regular" style="border-color:var(--teal);position:relative">
                    <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%)">
                        <span style="background:linear-gradient(135deg,#0D9488,#0F766E);color:white;font-size:11px;font-weight:700;padding:5px 16px;border-radius:999px;text-transform:uppercase;letter-spacing:0.05em">Ahorra 15%</span>
                    </div>
                    <h3 style="font-size:18px;font-weight:800;color:var(--slate-900);margin-bottom:4px">Semestral</h3>
                    <div style="font-size:42px;font-weight:900;color:var(--slate-900);font-family:Poppins;letter-spacing:-0.03em;line-height:1.1">$179.400</div>
                    <p style="color:#6b7280;margin-bottom:24px;font-size:13px">6 meses &middot; <span style="color:var(--teal);font-weight:600">$29.900/mes</span></p>
                    <div style="display:flex;flex-direction:column;gap:12px">
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>Citas ilimitadas x 6 meses</strong>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Todo del plan gratis
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Mejor precio por mes
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--slate-900)">
                            <svg width="18" height="18" fill="var(--teal)" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Un solo pago, sin renovación
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Ideal for --}}
    <section style="padding:80px 16px;background:white;border-top:1px solid var(--border);border-bottom:1px solid var(--border)" class="reveal">
        <div style="max-width:900px;margin:0 auto;text-align:center">
            <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(217,119,6,0.08);border-radius:8px;margin-bottom:12px">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--amber)"></span>
                <span style="font-size:12px;font-weight:600;color:var(--amber);text-transform:uppercase;letter-spacing:0.06em">Segmentos</span>
            </div>
            <h2 style="font-size:clamp(28px,4vw,40px);font-weight:800;color:var(--slate-900);letter-spacing:-0.02em;margin-bottom:40px">Ideal para</h2>
            <div class="segments-grid" style="display:grid;grid-template-columns:repeat(4, 1fr);gap:14px">
                <div class="segment-card">
                    <span class="segment-icon">&#128136;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Barberías</p>
                </div>
                <div class="segment-card">
                    <span class="segment-icon">&#128135;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Salones de belleza</p>
                </div>
                <div class="segment-card">
                    <span class="segment-icon">&#128133;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Centros estéticos</p>
                </div>
                <div class="segment-card">
                    <span class="segment-icon">&#128134;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Spas y masajes</p>
                </div>
                <div class="segment-card">
                    <span class="segment-icon">&#129657;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Odontología</p>
                </div>
                <div class="segment-card">
                    <span class="segment-icon">&#129658;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Fisioterapia</p>
                </div>
                <div class="segment-card">
                    <span class="segment-icon">&#127947;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Entrenadores</p>
                </div>
                <div class="segment-card">
                    <span class="segment-icon">&#128137;</span>
                    <p style="font-weight:700;color:var(--slate-900);font-size:14px">Consultorios médicos</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cta-section" style="padding:100px 16px">
        <div style="position:relative;z-index:2;max-width:640px;margin:0 auto;text-align:center">
            <h2 style="font-size:clamp(28px,4.5vw,44px);font-weight:900;color:white;letter-spacing:-0.02em;line-height:1.1">
                ¿Listo para automatizar tu negocio?
            </h2>
            <p style="color:var(--slate-400);margin-top:16px;font-size:17px;line-height:1.6">
                Únete a los negocios que ya gestionan sus citas con Citora. Empieza gratis hoy.
            </p>
            <a href="{{ route('auth.google.redirect') }}" class="cta-btn" style="margin-top:36px">
                <svg width="22" height="22" viewBox="0 0 24 24"><path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" opacity=".7"/><path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" opacity=".8"/><path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" opacity=".6"/><path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" opacity=".9"/></svg>
                Comenzar gratis ahora
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer style="padding:40px 16px;background:var(--slate-900);border-top:1px solid rgba(255,255,255,0.05)">
        <div style="max-width:1140px;margin:0 auto">
            <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:20px">
                <div style="display:flex;align-items:center;gap:10px">
                    <img src="/images/logo-dark.png" alt="Citora" style="height:28px;mix-blend-mode:lighten" onerror="this.style.display='none'">
                    <span style="color:white;font-weight:800;font-size:18px;font-family:Poppins;letter-spacing:-0.02em">Citora</span>
                </div>
                <p style="font-size:13px;color:var(--slate-400)">&copy; {{ date('Y') }} Citora. Todos los derechos reservados.</p>
                <div style="display:flex;gap:24px">
                    <a href="{{ route('legal.terms') }}" class="footer-link">Términos</a>
                    <a href="{{ route('legal.privacy') }}" class="footer-link">Privacidad</a>
                    <a href="mailto:webcitora@gmail.com" class="footer-link">Contacto</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scroll animations & Nav scroll effect --}}
    <script>
        // Nav scroll effect
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });

        // Scroll reveal
        const revealEls = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(el => revealObserver.observe(el));
    </script>

</body>
</html>
