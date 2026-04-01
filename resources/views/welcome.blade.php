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
    <nav style="position:fixed;top:0;width:100%;z-index:50;background:rgba(250,250,248,0.92);backdrop-filter:blur(8px);border-bottom:1px solid #E7E5DF">
        <div style="max-width:1100px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;justify-content:space-between">
            <a href="/" style="display:flex;align-items:center;gap:8px;text-decoration:none">
                <img src="/images/logo-light.png" alt="Citora" style="height:32px" onerror="this.style.display='none'">
                <span style="font-size:18px;font-weight:700;color:#0F172A;font-family:Poppins,sans-serif">Citora</span>
            </a>
            <div style="display:flex;align-items:center;gap:10px;font-size:13px">
                @auth
                    <a href="{{ route('customer.appointments') }}" style="padding:8px 14px;color:#374151;text-decoration:none;font-weight:500;border:1px solid #E7E5DF;border-radius:8px">📅 Mis citas</a>
                    @if(auth()->user()->business_id)
                        <a href="{{ filament()->getUrl() }}" style="padding:8px 14px;background:#D97706;color:white;font-weight:600;border-radius:8px;text-decoration:none">Mi panel</a>
                    @endif
                @else
                    <a href="{{ route('auth.google.redirect') }}" style="padding:8px 14px;color:#374151;text-decoration:none;font-weight:500;border:1px solid #E7E5DF;border-radius:8px">📅 Mis citas</a>
                    <a href="{{ route('auth.google.redirect') }}" style="padding:8px 14px;background:#D97706;color:white;font-weight:600;border-radius:8px;text-decoration:none">🏪 Registra tu negocio</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section style="background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%);padding:90px 16px 60px">
        <div style="max-width:700px;margin:0 auto;text-align:center">
            <h1 style="font-size:clamp(28px,5vw,48px);font-weight:800;color:white;line-height:1.15;font-family:Poppins,sans-serif">
                Reserva tu cita en segundos
            </h1>
            <p style="margin-top:12px;font-size:17px;color:#9CA3AF;max-width:500px;margin-left:auto;margin-right:auto">
                Encuentra tu barbería, salón o spa favorito y agenda online. Sin llamadas.
            </p>

            {{-- Search bar --}}
            <div style="position:relative;max-width:520px;margin:28px auto 0">
                <div style="display:flex;align-items:center;background:white;border-radius:14px;padding:4px;box-shadow:0 8px 32px rgba(0,0,0,0.2)">
                    <span style="padding:0 12px;font-size:20px">🔍</span>
                    <input type="text" id="search-input" placeholder="Buscar negocio o servicio..."
                           style="flex:1;border:none;outline:none;font-size:15px;padding:12px 0;background:transparent;font-family:Inter,sans-serif"
                           autocomplete="off">
                    <button onclick="document.getElementById('search-input').value && (window.location.hash='resultados')"
                            style="padding:10px 20px;background:#D97706;color:white;font-weight:600;border-radius:10px;border:none;font-size:14px;cursor:pointer">
                        Buscar
                    </button>
                </div>
                {{-- Search results dropdown --}}
                <div id="search-results" style="display:none;position:absolute;top:100%;left:0;right:0;margin-top:8px;background:white;border-radius:12px;border:1px solid #E7E5DF;box-shadow:0 12px 40px rgba(0,0,0,0.15);max-height:360px;overflow-y:auto;z-index:10"></div>
            </div>

            <div style="display:flex;justify-content:center;gap:10px;margin-top:24px;flex-wrap:wrap">
                <a href="{{ route('auth.google.redirect') }}" style="padding:10px 20px;background:#D97706;color:white;font-weight:700;border-radius:10px;text-decoration:none;font-size:14px;display:inline-flex;align-items:center;gap:6px">
                    🏪 Registra tu negocio gratis
                </a>
                @auth
                    <a href="{{ route('customer.appointments') }}" style="padding:10px 20px;border:1px solid rgba(255,255,255,0.2);color:white;font-weight:600;border-radius:10px;text-decoration:none;font-size:14px;display:inline-flex;align-items:center;gap:6px">
                        📅 Consultar mis citas
                    </a>
                @else
                    <a href="{{ route('auth.google.redirect') }}" style="padding:10px 20px;border:1px solid rgba(255,255,255,0.2);color:white;font-weight:600;border-radius:10px;text-decoration:none;font-size:14px;display:inline-flex;align-items:center;gap:6px">
                        📅 Consultar mis citas
                    </a>
                @endauth
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
                        html += '<div style="padding:10px 16px 6px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em">Servicios</div>';
                        data.services.forEach(s => {
                            html += `<a href="/${s.slug}" style="display:flex;align-items:center;gap:10px;padding:10px 16px;text-decoration:none;border-bottom:1px solid #f3f4f6"
                                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                                ${s.image ? `<img src="${s.image}" style="width:36px;height:36px;border-radius:8px;object-fit:cover">` : '<span style="width:36px;height:36px;border-radius:8px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:16px">✂️</span>'}
                                <div style="flex:1;min-width:0">
                                    <p style="font-weight:600;font-size:14px;color:#0F172A">${s.name}</p>
                                    <p style="font-size:12px;color:#6b7280">${s.business} · ${s.duration} min · $${Number(s.price).toLocaleString()}</p>
                                </div>
                            </a>`;
                        });
                    }

                    if (data.businesses.length > 0) {
                        html += '<div style="padding:10px 16px 6px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em">Negocios</div>';
                        data.businesses.forEach(b => {
                            html += `<a href="/${b.slug}" style="display:flex;align-items:center;gap:10px;padding:10px 16px;text-decoration:none;border-bottom:1px solid #f3f4f6"
                                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                                ${b.logo ? `<img src="${b.logo}" style="width:36px;height:36px;border-radius:8px;object-fit:cover">` : '<span style="width:36px;height:36px;border-radius:8px;background:#D97706;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px">' + b.name[0] + '</span>'}
                                <div style="flex:1;min-width:0">
                                    <p style="font-weight:600;font-size:14px;color:#0F172A">${b.name}</p>
                                    <p style="font-size:12px;color:#6b7280">${b.address || 'Sin dirección'}</p>
                                </div>
                            </a>`;
                        });
                    }

                    if (!html) html = '<p style="padding:20px;text-align:center;color:#9ca3af;font-size:14px">No se encontraron resultados</p>';

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
    <section style="padding:48px 0">
        <div style="max-width:1100px;margin:0 auto;padding:0 16px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <div>
                    <p style="font-size:12px;font-weight:700;color:#D97706;text-transform:uppercase;letter-spacing:0.05em">Explora</p>
                    <h2 style="font-size:24px;font-weight:700;color:#0F172A;font-family:Poppins,sans-serif">Servicios disponibles</h2>
                </div>
            </div>
        </div>
        <div style="overflow-x:auto;padding:0 16px 16px;-webkit-overflow-scrolling:touch">
            <div style="display:flex;gap:16px;max-width:1100px;margin:0 auto">
                @foreach($services as $service)
                <a href="{{ route('booking.show', $service->business->slug) }}" style="flex-shrink:0;width:260px;background:white;border:1px solid #E7E5DF;border-radius:12px;overflow:hidden;text-decoration:none;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                    @if($service->getFirstMediaUrl('image'))
                        <img src="{{ $service->getFirstMediaUrl('image') }}" alt="{{ $service->name }}" style="width:100%;height:140px;object-fit:cover">
                    @else
                        <div style="width:100%;height:140px;background:linear-gradient(135deg,rgba(217,119,6,0.1),rgba(13,148,136,0.1));display:flex;align-items:center;justify-content:center;font-size:40px">✂️</div>
                    @endif
                    <div style="padding:12px">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px">
                            @if($service->business->getFirstMediaUrl('logo'))
                                <img src="{{ $service->business->getFirstMediaUrl('logo') }}" alt="" style="width:20px;height:20px;border-radius:50%;object-fit:cover">
                            @endif
                            <span style="font-size:11px;color:#6b7280">{{ $service->business->name }}</span>
                        </div>
                        <p style="font-weight:700;font-size:15px;color:#0F172A">{{ $service->name }}</p>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px">
                            <span style="font-size:12px;color:#6b7280">⏱ {{ $service->duration_minutes }} min</span>
                            <span style="font-size:14px;font-weight:700;color:#D97706">${{ number_format($service->price) }}</span>
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
    <section style="padding:48px 0;background:white;border-top:1px solid #E7E5DF;border-bottom:1px solid #E7E5DF">
        <div style="max-width:1100px;margin:0 auto;padding:0 16px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <div>
                    <p style="font-size:12px;font-weight:700;color:#0D9488;text-transform:uppercase;letter-spacing:0.05em">Directorio</p>
                    <h2 style="font-size:24px;font-weight:700;color:#0F172A;font-family:Poppins,sans-serif">Negocios en Citora</h2>
                </div>
            </div>
        </div>
        <div style="overflow-x:auto;padding:0 16px 16px;-webkit-overflow-scrolling:touch">
            <div style="display:flex;gap:16px;max-width:1100px;margin:0 auto">
                @foreach($businesses as $business)
                <a href="{{ route('booking.show', $business->slug) }}" style="flex-shrink:0;width:280px;background:#FAFAF8;border:1px solid #E7E5DF;border-radius:12px;padding:16px;text-decoration:none;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                        @if($business->getFirstMediaUrl('logo'))
                            <img src="{{ $business->getFirstMediaUrl('logo') }}" alt="{{ $business->name }}" style="width:48px;height:48px;border-radius:10px;object-fit:cover;border:1px solid #E7E5DF">
                        @else
                            <div style="width:48px;height:48px;border-radius:10px;background:rgba(217,119,6,0.1);display:flex;align-items:center;justify-content:center;color:#D97706;font-weight:700;font-size:18px;font-family:Poppins">{{ substr($business->name, 0, 1) }}</div>
                        @endif
                        <div>
                            <p style="font-weight:700;font-size:15px;color:#0F172A">{{ $business->name }}</p>
                            @if($business->address)
                                <p style="font-size:12px;color:#6b7280">📍 {{ Str::limit($business->address, 30) }}</p>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;gap:12px;font-size:12px;color:#6b7280">
                        <span>✂️ {{ $business->services_count }} servicios</span>
                        <span>👤 {{ $business->employees_count }} profesionales</span>
                    </div>
                    <div style="margin-top:12px;padding-top:12px;border-top:1px solid #E7E5DF;text-align:center">
                        <span style="font-size:13px;font-weight:600;color:#0D9488">Reservar cita →</span>
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
