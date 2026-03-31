<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citora — La forma inteligente de gestionar tu agenda</title>
    <meta name="description" content="Plataforma SaaS para gestión de citas en barberías, salones de belleza y centros estéticos. Reservas online, WhatsApp automático y panel de control.">
    <link rel="icon" href="/images/logo-light.png" type="image/png">
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FAFAF8; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
        .gradient-hero { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); }
        .cta-primary { background: #D97706; }
        .cta-primary:hover { background: #B45309; }
    </style>
</head>
<body class="text-[#111111] antialiased">

    {{-- Nav --}}
    <nav class="fixed top-0 w-full z-50 bg-[#FAFAF8]/90 backdrop-blur border-b border-[#E7E5DF]">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <img src="/images/logo-light.png" alt="Citora" class="h-9" onerror="this.style.display='none'">
                <span class="text-xl font-bold text-[#0F172A]" style="font-family:Poppins,sans-serif">Citora</span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ filament()->getUrl() }}" class="px-5 py-2.5 cta-primary text-white text-sm font-semibold rounded-lg transition">Mi panel</a>
                @else
                    <a href="{{ route('auth.google.redirect') }}" class="px-5 py-2.5 cta-primary text-white text-sm font-semibold rounded-lg transition">Comenzar gratis</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="gradient-hero pt-28 pb-24 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 rounded-full text-sm text-[#5EAEFF] font-medium mb-6 border border-white/10">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                La forma inteligente de gestionar tu agenda
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                Control total de tu negocio,
                <span class="block text-[#F59E0B]">citas sin esfuerzo</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-[#9CA3AF] max-w-2xl mx-auto">
                Página de reservas, notificaciones WhatsApp, gestión de equipo y métricas. Todo automatizado para que tú te enfoques en lo que importa.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('auth.google.redirect') }}" class="px-8 py-4 cta-primary text-white font-bold rounded-xl transition text-lg shadow-lg shadow-amber-500/25 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" viewBox="0 0 24 24"><path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" opacity=".7"/><path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" opacity=".8"/><path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" opacity=".6"/><path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" opacity=".9"/></svg>
                    Crear mi negocio gratis
                </a>
                <a href="#como-funciona" class="px-8 py-4 border border-white/20 text-white font-semibold rounded-xl hover:bg-white/5 transition text-lg flex items-center justify-center gap-2">
                    Ver cómo funciona
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>
            <p class="mt-5 text-[#666666] text-sm">Sin tarjeta de crédito · 200 citas/mes gratis · Configura en 5 minutos</p>
        </div>
    </section>

    {{-- Services showcase --}}
    @if($services->count() > 0)
    <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-sm font-semibold text-[#D97706] uppercase tracking-wider">Explora</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 text-[#0F172A]">Servicios disponibles</h2>
                <p class="text-[#666666] mt-3 text-lg">Encuentra el servicio perfecto y reserva en segundos</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($services as $service)
                <a href="{{ route('booking.show', $service->business->slug) }}"
                   class="bg-white rounded-xl border border-[#E7E5DF] overflow-hidden hover:shadow-lg hover:border-[#D97706]/30 transition group">
                    @if($service->getFirstMediaUrl('image'))
                        <div class="h-40 overflow-hidden">
                            <img src="{{ $service->getFirstMediaUrl('image') }}" alt="{{ $service->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                    @else
                        <div class="h-40 bg-gradient-to-br from-[#D97706]/10 to-[#0D9488]/10 flex items-center justify-center">
                            <svg class="w-12 h-12 text-[#D97706]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            @if($service->business->getFirstMediaUrl('logo'))
                                <img src="{{ $service->business->getFirstMediaUrl('logo') }}" alt="" class="w-6 h-6 rounded-full object-cover">
                            @endif
                            <span class="text-xs text-[#666666]">{{ $service->business->name }}</span>
                        </div>
                        <h3 class="font-bold text-[#0F172A] group-hover:text-[#D97706] transition">{{ $service->name }}</h3>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-[#666666]">{{ $service->duration_minutes }} min</span>
                            <span class="text-sm font-bold text-[#D97706]">${{ number_format($service->price) }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Businesses showcase --}}
    @if($businesses->count() > 0)
    <section class="py-20 px-4 bg-white border-y border-[#E7E5DF]">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-sm font-semibold text-[#0D9488] uppercase tracking-wider">Directorio</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 text-[#0F172A]">Negocios en Citora</h2>
                <p class="text-[#666666] mt-3 text-lg">Reserva tu cita con los mejores profesionales</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($businesses as $business)
                <a href="{{ route('booking.show', $business->slug) }}"
                   class="bg-[#FAFAF8] rounded-xl border border-[#E7E5DF] p-6 hover:shadow-lg hover:border-[#0D9488]/30 transition group">
                    <div class="flex items-center gap-4 mb-4">
                        @if($business->getFirstMediaUrl('logo'))
                            <img src="{{ $business->getFirstMediaUrl('logo') }}" alt="{{ $business->name }}"
                                 class="w-14 h-14 rounded-xl object-cover border border-[#E7E5DF]">
                        @else
                            <div class="w-14 h-14 rounded-xl bg-[#D97706]/10 flex items-center justify-center text-[#D97706] font-bold text-xl" style="font-family:Poppins">
                                {{ substr($business->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-[#0F172A] group-hover:text-[#0D9488] transition">{{ $business->name }}</h3>
                            @if($business->address)
                                <p class="text-xs text-[#666666] mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ Str::limit($business->address, 40) }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-[#666666]">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            {{ $business->services_count }} servicios
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $business->employees_count }} profesionales
                        </span>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[#E7E5DF] text-center">
                        <span class="text-sm font-semibold text-[#0D9488] group-hover:text-[#D97706] transition">Reservar cita →</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Features --}}
    <section class="py-24 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-sm font-semibold text-[#D97706] uppercase tracking-wider">Funcionalidades</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 text-[#0F172A]">Todo lo que necesitas para crecer</h2>
                <p class="text-[#666666] mt-3 text-lg max-w-xl mx-auto">Herramientas diseñadas para negocios reales. Sin complicaciones.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-7 rounded-xl border border-[#E7E5DF] hover:shadow-md hover:border-[#D97706]/30 transition group">
                    <div class="w-12 h-12 bg-[#D97706]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#D97706]/20 transition">
                        <svg class="w-6 h-6 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A] mb-2">Tu página de reservas</h3>
                    <p class="text-[#666666] text-sm leading-relaxed">Enlace personalizado para tu negocio. Compártelo en redes, WhatsApp o donde quieras.</p>
                </div>
                <div class="bg-white p-7 rounded-xl border border-[#E7E5DF] hover:shadow-md hover:border-[#0D9488]/30 transition group">
                    <div class="w-12 h-12 bg-[#0D9488]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#0D9488]/20 transition">
                        <svg class="w-6 h-6 text-[#0D9488]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A] mb-2">WhatsApp automático</h3>
                    <p class="text-[#666666] text-sm leading-relaxed">Confirmaciones, recordatorios 24h y 1h antes, cancelaciones. Todo automático.</p>
                </div>
                <div class="bg-white p-7 rounded-xl border border-[#E7E5DF] hover:shadow-md hover:border-[#2563EB]/30 transition group">
                    <div class="w-12 h-12 bg-[#2563EB]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#2563EB]/20 transition">
                        <svg class="w-6 h-6 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A] mb-2">Anti-cruces inteligente</h3>
                    <p class="text-[#666666] text-sm leading-relaxed">Nunca más citas cruzadas. Validación en tiempo real por profesional y horario.</p>
                </div>
                <div class="bg-white p-7 rounded-xl border border-[#E7E5DF] hover:shadow-md hover:border-[#D97706]/30 transition group">
                    <div class="w-12 h-12 bg-[#D97706]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#D97706]/20 transition">
                        <svg class="w-6 h-6 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A] mb-2">Gestión de equipo</h3>
                    <p class="text-[#666666] text-sm leading-relaxed">Empleados, servicios asignados y horarios individuales. Todo bajo tu control.</p>
                </div>
                <div class="bg-white p-7 rounded-xl border border-[#E7E5DF] hover:shadow-md hover:border-[#0D9488]/30 transition group">
                    <div class="w-12 h-12 bg-[#0D9488]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#0D9488]/20 transition">
                        <svg class="w-6 h-6 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A] mb-2">Métricas en tiempo real</h3>
                    <p class="text-[#666666] text-sm leading-relaxed">Citas del día, ingresos mensuales, ocupación de equipo. Decisiones informadas.</p>
                </div>
                <div class="bg-white p-7 rounded-xl border border-[#E7E5DF] hover:shadow-md hover:border-[#2563EB]/30 transition group">
                    <div class="w-12 h-12 bg-[#2563EB]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#2563EB]/20 transition">
                        <svg class="w-6 h-6 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A] mb-2">Mobile-first</h3>
                    <p class="text-[#666666] text-sm leading-relaxed">Tus clientes reservan desde el celular en segundos. Experiencia rápida y fluida.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="como-funciona" class="py-24 px-4 bg-white border-y border-[#E7E5DF]">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-sm font-semibold text-[#0D9488] uppercase tracking-wider">Proceso</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 text-[#0F172A]">Listo en 3 pasos</h2>
            </div>
            <div class="grid sm:grid-cols-3 gap-10">
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#0F172A] text-[#F59E0B] rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl font-bold" style="font-family:Poppins">1</div>
                    <h3 class="font-bold text-lg mb-2 text-[#0F172A]">Crea tu cuenta</h3>
                    <p class="text-[#666666] text-sm">Regístrate con Google en segundos. Sin formularios largos ni verificaciones.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#0F172A] text-[#F59E0B] rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl font-bold" style="font-family:Poppins">2</div>
                    <h3 class="font-bold text-lg mb-2 text-[#0F172A]">Configura tu negocio</h3>
                    <p class="text-[#666666] text-sm">Wizard guiado: servicios, empleados, horarios e imágenes. En 5 minutos.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#0F172A] text-[#F59E0B] rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl font-bold" style="font-family:Poppins">3</div>
                    <h3 class="font-bold text-lg mb-2 text-[#0F172A]">Comparte tu enlace</h3>
                    <p class="text-[#666666] text-sm">Tus clientes reservan online. Tú recibes todo en tu panel y WhatsApp.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section class="py-24 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-sm font-semibold text-[#2563EB] uppercase tracking-wider">Precios</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 text-[#0F172A]">Simple y transparente</h2>
                <p class="text-[#666666] mt-3 text-lg">Sin suscripciones. Paga solo si lo necesitas.</p>
            </div>
            <div class="grid sm:grid-cols-2 gap-8 max-w-2xl mx-auto">
                <div class="bg-white p-8 rounded-2xl border-2 border-[#D97706] relative shadow-sm">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#D97706] text-white text-xs font-bold px-4 py-1 rounded-full">POPULAR</span>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-2">Gratis</h3>
                    <div class="text-4xl font-bold text-[#0F172A] mb-1" style="font-family:Poppins">$0</div>
                    <p class="text-[#666666] mb-6">Para siempre</p>
                    <ul class="space-y-3 text-sm text-[#111111]">
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> 200 citas al mes</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Página de reservas propia</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> WhatsApp automático</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Panel de control completo</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Empleados ilimitados</li>
                    </ul>
                </div>
                <div class="bg-white p-8 rounded-2xl border border-[#E7E5DF] shadow-sm">
                    <h3 class="text-xl font-bold text-[#0F172A] mb-2">Desbloqueo mensual</h3>
                    <div class="text-4xl font-bold text-[#0F172A] mb-1" style="font-family:Poppins">$29,900</div>
                    <p class="text-[#666666] mb-6">Pago único por mes</p>
                    <ul class="space-y-3 text-sm text-[#111111]">
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <strong>Citas ilimitadas</strong></li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Todo del plan gratis</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Sin suscripción ni compromiso</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Paga solo cuando lo necesites</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Ideal for --}}
    <section class="py-20 px-4 bg-white border-y border-[#E7E5DF]">
        <div class="max-w-4xl mx-auto text-center">
            <span class="text-sm font-semibold text-[#D97706] uppercase tracking-wider">Segmentos</span>
            <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-12 text-[#0F172A]">Ideal para</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                <div class="p-6 bg-[#FAFAF8] rounded-xl border border-[#E7E5DF]">
                    <div class="text-4xl mb-3">💈</div>
                    <p class="font-semibold text-[#0F172A]">Barberías</p>
                </div>
                <div class="p-6 bg-[#FAFAF8] rounded-xl border border-[#E7E5DF]">
                    <div class="text-4xl mb-3">💇‍♀️</div>
                    <p class="font-semibold text-[#0F172A]">Salones de belleza</p>
                </div>
                <div class="p-6 bg-[#FAFAF8] rounded-xl border border-[#E7E5DF]">
                    <div class="text-4xl mb-3">💅</div>
                    <p class="font-semibold text-[#0F172A]">Centros estéticos</p>
                </div>
                <div class="p-6 bg-[#FAFAF8] rounded-xl border border-[#E7E5DF]">
                    <div class="text-4xl mb-3">💆</div>
                    <p class="font-semibold text-[#0F172A]">Spas y masajes</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="gradient-hero py-24 px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">¿Listo para automatizar tu negocio?</h2>
            <p class="text-[#9CA3AF] mt-4 text-lg">Únete a los negocios que ya gestionan sus citas con Citora.</p>
            <a href="{{ route('auth.google.redirect') }}" class="inline-flex items-center gap-3 mt-10 px-8 py-4 cta-primary text-white font-bold rounded-xl transition text-lg shadow-lg shadow-amber-500/25">
                <svg class="w-6 h-6" viewBox="0 0 24 24"><path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" opacity=".7"/><path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" opacity=".8"/><path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" opacity=".6"/><path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" opacity=".9"/></svg>
                Comenzar gratis ahora
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-10 px-4 bg-[#0F172A]">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <img src="/images/logo-dark.png" alt="Citora" class="h-8" onerror="this.style.display='none'">
                <span class="text-white font-bold text-lg" style="font-family:Poppins">Citora</span>
            </div>
            <p class="text-sm text-[#9CA3AF]">&copy; {{ date('Y') }} Citora. Todos los derechos reservados.</p>
            <div class="flex gap-6 text-sm text-[#9CA3AF]">
                <a href="#" class="hover:text-[#F59E0B] transition">Términos</a>
                <a href="#" class="hover:text-[#F59E0B] transition">Privacidad</a>
                <a href="mailto:webcitora@gmail.com" class="hover:text-[#F59E0B] transition">Contacto</a>
            </div>
        </div>
    </footer>

</body>
</html>
