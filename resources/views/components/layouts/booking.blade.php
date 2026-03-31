<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Reservar cita' }} - Citora</title>
    <link rel="icon" href="/images/logo-light.png" type="image/png">
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
<body class="min-h-screen">
    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
