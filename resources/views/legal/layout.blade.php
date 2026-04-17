<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Citora' }}</title>
    <meta name="description" content="{{ $description ?? 'Citora - Plataforma SaaS de gestión de citas' }}">
    <link rel="icon" href="/images/logo-light.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --amber: #D97706;
            --amber-light: #F59E0B;
            --slate-900: #0F172A;
            --slate-800: #1E293B;
            --slate-400: #94A3B8;
            --cream: #FAFAF8;
            --border: #E7E5DF;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--cream); color: #111; line-height: 1.6; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; letter-spacing: -0.02em; }

        .header { background: linear-gradient(135deg, var(--slate-900), var(--slate-800)); padding: 24px 16px; }
        .header-inner { max-width: 900px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; color: white; }
        .brand img { height: 28px; mix-blend-mode: lighten; }
        .brand span { color: white; font-weight: 800; font-size: 20px; font-family: Poppins; }
        .back-link { color: var(--amber-light); font-size: 14px; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }

        .container { max-width: 900px; margin: 0 auto; padding: 32px 16px 80px; }
        .page-title { font-size: 32px; font-weight: 800; margin-bottom: 8px; }
        .page-subtitle { color: #6b7280; font-size: 14px; margin-bottom: 32px; }
        .meta-box { background: white; border: 1px solid var(--border); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px; color: #6b7280; display: flex; flex-wrap: wrap; gap: 16px; }
        .meta-box strong { color: #111; }

        .content { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 32px 28px; }
        .content h2 { font-size: 22px; font-weight: 700; margin-top: 32px; margin-bottom: 12px; color: var(--slate-900); }
        .content h2:first-child { margin-top: 0; }
        .content h3 { font-size: 16px; font-weight: 600; margin-top: 20px; margin-bottom: 8px; color: var(--slate-800); }
        .content p { margin-bottom: 12px; color: #374151; }
        .content ul, .content ol { margin: 12px 0 16px 24px; color: #374151; }
        .content li { margin-bottom: 6px; }
        .content strong { color: #111; }
        .content a { color: var(--amber); text-decoration: none; font-weight: 500; }
        .content a:hover { text-decoration: underline; }

        .callout { background: #FEF3C7; border-left: 4px solid var(--amber); border-radius: 8px; padding: 16px 20px; margin: 16px 0; font-size: 14px; color: #92400E; }

        .footer { text-align: center; padding: 32px 16px; font-size: 13px; color: var(--slate-400); background: var(--slate-900); }
        .footer a { color: var(--amber-light); text-decoration: none; margin: 0 8px; }

        @media (max-width: 640px) {
            .page-title { font-size: 26px; }
            .content { padding: 24px 20px; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <a href="/" class="brand">
                <img src="/images/logo-dark.png" alt="Citora" onerror="this.style.display='none'">
                <span>Citora</span>
            </a>
            <a href="/" class="back-link">← Volver al inicio</a>
        </div>
    </header>

    <main class="container">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="footer">
        <div>&copy; {{ date('Y') }} {{ config('legal.responsible.brand') }}. Todos los derechos reservados.</div>
        <div style="margin-top: 8px">
            <a href="{{ route('legal.privacy') }}">Privacidad</a>
            <a href="{{ route('legal.terms') }}">Términos</a>
            <a href="mailto:{{ config('legal.responsible.email') }}">Contacto</a>
        </div>
    </footer>
</body>
</html>
