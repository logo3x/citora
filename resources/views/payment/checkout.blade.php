<x-layouts.booking title="Desbloquear citas">
    <div style="max-width:720px;margin:0 auto;padding:32px 16px">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:32px">
            <div style="width:56px;height:56px;background:rgba(217,119,6,0.1);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <svg width="28" height="28" fill="none" stroke="#D97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h1 style="font-size:28px;font-weight:800;color:#0F172A;font-family:Poppins,sans-serif">Desbloquea tu negocio</h1>
            <p style="color:#6b7280;margin-top:6px;font-size:16px">{{ $business->name }}</p>
        </div>

        {{-- Usage bar --}}
        <div style="background:white;border:1px solid #E7E5DF;border-radius:14px;padding:16px 20px;margin-bottom:28px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                <span style="font-size:13px;font-weight:600;color:#0F172A">Tu uso actual</span>
                <span style="font-size:13px;color:{{ $used >= $limit ? '#dc2626' : '#6b7280' }};font-weight:600">{{ $used }} / {{ $limit }} citas</span>
            </div>
            <div style="width:100%;background:#e5e7eb;border-radius:999px;height:8px;overflow:hidden">
                <div style="height:8px;border-radius:999px;background:{{ $used >= $limit ? '#dc2626' : '#D97706' }};width:{{ min(100, ($used / max(1, $limit)) * 100) }}%;transition:all 0.5s"></div>
            </div>
            @if($used >= $limit)
                <p style="font-size:12px;color:#dc2626;margin-top:6px;font-weight:500">Has alcanzado el límite. Tus clientes no pueden reservar.</p>
            @endif
        </div>

        {{-- Plan selector --}}
        <p style="font-size:14px;font-weight:700;color:#0F172A;margin-bottom:12px">Elige tu plan</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:28px">
            {{-- Monthly --}}
            <a href="{{ route('payment.checkout', ['business' => $business->slug, 'plan' => 'monthly']) }}"
               style="display:block;background:white;border-radius:16px;padding:20px;text-decoration:none;transition:all 0.2s;cursor:pointer;position:relative;{{ $planType === 'monthly' ? 'border:2px solid #D97706;box-shadow:0 0 0 3px rgba(217,119,6,0.1)' : 'border:1px solid #E7E5DF' }}">
                @if($planType === 'monthly')
                    <div style="position:absolute;top:-10px;right:12px;background:#D97706;color:white;font-size:10px;font-weight:700;padding:3px 10px;border-radius:999px;text-transform:uppercase">Seleccionado</div>
                @endif
                <p style="font-size:13px;font-weight:600;color:#6b7280;margin-bottom:4px">Mensual</p>
                <div style="font-size:32px;font-weight:900;color:#0F172A;font-family:Poppins,sans-serif;line-height:1.1">${{ number_format($plans['monthly']['price']) }}</div>
                <p style="font-size:12px;color:#6b7280;margin-top:4px">Pago único · 30 días</p>
                <div style="margin-top:12px;display:flex;flex-direction:column;gap:6px">
                    <span style="font-size:12px;color:#0F172A;display:flex;align-items:center;gap:6px">
                        <svg width="14" height="14" fill="#0D9488" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Citas ilimitadas
                    </span>
                    <span style="font-size:12px;color:#0F172A;display:flex;align-items:center;gap:6px">
                        <svg width="14" height="14" fill="#0D9488" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Sin suscripción
                    </span>
                </div>
            </a>

            {{-- Semester --}}
            <a href="{{ route('payment.checkout', ['business' => $business->slug, 'plan' => 'semester']) }}"
               style="display:block;background:white;border-radius:16px;padding:20px;text-decoration:none;transition:all 0.2s;cursor:pointer;position:relative;{{ $planType === 'semester' ? 'border:2px solid #0D9488;box-shadow:0 0 0 3px rgba(13,148,136,0.1)' : 'border:1px solid #E7E5DF' }}">
                <div style="position:absolute;top:-10px;left:12px;background:linear-gradient(135deg,#0D9488,#0F766E);color:white;font-size:10px;font-weight:700;padding:3px 10px;border-radius:999px;text-transform:uppercase">Ahorra 15%</div>
                @if($planType === 'semester')
                    <div style="position:absolute;top:-10px;right:12px;background:#0D9488;color:white;font-size:10px;font-weight:700;padding:3px 10px;border-radius:999px;text-transform:uppercase">Seleccionado</div>
                @endif
                <p style="font-size:13px;font-weight:600;color:#6b7280;margin-bottom:4px">Semestral</p>
                <div style="font-size:32px;font-weight:900;color:#0F172A;font-family:Poppins,sans-serif;line-height:1.1">${{ number_format($plans['semester']['price']) }}</div>
                <p style="font-size:12px;color:#0D9488;margin-top:4px;font-weight:600">${{ number_format($plans['semester']['price'] / 6) }}/mes · 6 meses</p>
                <div style="margin-top:12px;display:flex;flex-direction:column;gap:6px">
                    <span style="font-size:12px;color:#0F172A;display:flex;align-items:center;gap:6px">
                        <svg width="14" height="14" fill="#0D9488" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Citas ilimitadas x 6 meses
                    </span>
                    <span style="font-size:12px;color:#0F172A;display:flex;align-items:center;gap:6px">
                        <svg width="14" height="14" fill="#0D9488" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Mejor precio por mes
                    </span>
                </div>
            </a>
        </div>

        {{-- Payment widget --}}
        <div style="background:white;border-radius:20px;border:2px solid {{ $planType === 'semester' ? '#0D9488' : '#D97706' }};padding:28px;text-align:center;margin-bottom:24px;box-shadow:0 4px 24px rgba(0,0,0,0.06)">
            <p style="font-size:13px;color:#6b7280;margin-bottom:4px">Total a pagar</p>
            <div style="font-size:40px;font-weight:900;color:#0F172A;font-family:Poppins,sans-serif;letter-spacing:-0.02em">${{ number_format($price) }}</div>
            <p style="font-size:13px;color:#6b7280;margin-bottom:20px">COP · Plan {{ $planType === 'semester' ? 'Semestral (180 días)' : 'Mensual (30 días)' }}</p>

            <form action="{{ config('services.wompi.base_url') }}/v1/payment_links" method="GET" id="wompi-form">
                <script
                    src="https://checkout.wompi.co/widget.js"
                    data-render="button"
                    data-public-key="{{ $payment['public_key'] }}"
                    data-currency="{{ $payment['currency'] }}"
                    data-amount-in-cents="{{ $payment['amount_in_cents'] }}"
                    data-reference="{{ $payment['reference'] }}"
                    data-signature:integrity="{{ $payment['signature'] }}"
                    data-redirect-url="{{ $payment['redirect_url'] }}"
                ></script>
            </form>

            <noscript>
                <a href="https://checkout.wompi.co/p/?public-key={{ $payment['public_key'] }}&currency={{ $payment['currency'] }}&amount-in-cents={{ $payment['amount_in_cents'] }}&reference={{ $payment['reference'] }}&signature:integrity={{ $payment['signature'] }}&redirect-url={{ urlencode($payment['redirect_url']) }}"
                   style="display:inline-block;padding:14px 32px;background:#D97706;color:white;font-weight:700;border-radius:12px;text-decoration:none;font-size:16px">
                    Pagar con Wompi
                </a>
            </noscript>
        </div>

        {{-- Benefits --}}
        <div style="background:#0F172A;border-radius:16px;padding:20px 24px;margin-bottom:24px">
            <p style="font-size:13px;font-weight:700;color:white;margin-bottom:12px">Lo que obtienes</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <span style="font-size:12px;color:#94A3B8;display:flex;align-items:center;gap:6px">
                    <svg width="14" height="14" fill="#F59E0B" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Citas ilimitadas
                </span>
                <span style="font-size:12px;color:#94A3B8;display:flex;align-items:center;gap:6px">
                    <svg width="14" height="14" fill="#F59E0B" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Reservas al instante
                </span>
                <span style="font-size:12px;color:#94A3B8;display:flex;align-items:center;gap:6px">
                    <svg width="14" height="14" fill="#F59E0B" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    WhatsApp automático
                </span>
                <span style="font-size:12px;color:#94A3B8;display:flex;align-items:center;gap:6px">
                    <svg width="14" height="14" fill="#F59E0B" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Sin suscripción
                </span>
            </div>
        </div>

        {{-- Payment methods --}}
        <div style="text-align:center;margin-bottom:20px">
            <p style="font-size:11px;color:#6b7280;margin-bottom:8px;font-weight:500">Métodos de pago aceptados</p>
            <div style="display:flex;justify-content:center;gap:8px;flex-wrap:wrap">
                <span style="display:flex;align-items:center;gap:4px;padding:6px 10px;background:white;border-radius:8px;font-size:11px;color:#6b7280;border:1px solid #E7E5DF">
                    <svg width="14" height="14" fill="none" stroke="#2563EB" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Tarjeta
                </span>
                <span style="display:flex;align-items:center;gap:4px;padding:6px 10px;background:white;border-radius:8px;font-size:11px;color:#6b7280;border:1px solid #E7E5DF">PSE</span>
                <span style="display:flex;align-items:center;gap:4px;padding:6px 10px;background:white;border-radius:8px;font-size:11px;color:#6b7280;border:1px solid #E7E5DF">Nequi</span>
                <span style="display:flex;align-items:center;gap:4px;padding:6px 10px;background:white;border-radius:8px;font-size:11px;color:#6b7280;border:1px solid #E7E5DF">Bancolombia</span>
                <span style="display:flex;align-items:center;gap:4px;padding:6px 10px;background:white;border-radius:8px;font-size:11px;color:#6b7280;border:1px solid #E7E5DF">Efecty</span>
            </div>
        </div>

        {{-- Security + back --}}
        <div style="text-align:center">
            <div style="display:flex;align-items:center;justify-content:center;gap:6px;font-size:11px;color:#6b7280;margin-bottom:16px">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Pago seguro procesado por Wompi · Datos encriptados
            </div>
            <a href="{{ filament()->getUrl() }}" style="font-size:13px;color:#6b7280;text-decoration:none">← Volver al panel</a>
        </div>
    </div>
</x-layouts.booking>
