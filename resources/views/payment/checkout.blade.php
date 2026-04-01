<x-layouts.booking title="Desbloquear citas">
    <div class="max-w-2xl mx-auto px-4 py-12">

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-[#D97706]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-[#0F172A]" style="font-family:Poppins">Desbloquea tu negocio</h1>
            <p class="text-[#666666] mt-2 text-lg">{{ $business->name }} · {{ now()->translatedFormat('F Y') }}</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-6 mb-10">
            {{-- Current usage --}}
            <div class="bg-white rounded-2xl border border-[#E7E5DF] p-6">
                <h3 class="font-bold text-[#0F172A] mb-4" style="font-family:Poppins">Tu uso actual</h3>
                <div class="flex items-end gap-3 mb-3">
                    <span class="text-4xl font-bold text-[#D97706]" style="font-family:Poppins">{{ $used }}</span>
                    <span class="text-[#666666] text-lg mb-1">/ {{ $limit }} citas</span>
                </div>
                <div class="w-full bg-[#E7E5DF] rounded-full h-3 mb-3">
                    <div class="h-3 rounded-full transition-all {{ $used >= $limit ? 'bg-red-500' : 'bg-[#D97706]' }}"
                         style="width: {{ min(100, ($used / max(1, $limit)) * 100) }}%"></div>
                </div>
                @if($used >= $limit)
                    <p class="text-sm text-red-600 font-medium">Has alcanzado el límite. Tus clientes no pueden reservar.</p>
                @else
                    <p class="text-sm text-[#666666]">{{ $limit - $used }} citas restantes este mes.</p>
                @endif
            </div>

            {{-- Benefits --}}
            <div class="bg-[#0F172A] rounded-2xl p-6 text-white">
                <h3 class="font-bold mb-4" style="font-family:Poppins">Con el desbloqueo obtienes</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2.5 text-sm">
                        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span><strong>Citas ilimitadas</strong> por el resto del mes</span>
                    </li>
                    <li class="flex items-start gap-2.5 text-sm">
                        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Tus clientes reservan <strong>al instante</strong></span>
                    </li>
                    <li class="flex items-start gap-2.5 text-sm">
                        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span><strong>No es suscripción</strong> — un solo pago</span>
                    </li>
                    <li class="flex items-start gap-2.5 text-sm">
                        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>WhatsApp automático incluido</span>
                    </li>
                    <li class="flex items-start gap-2.5 text-sm">
                        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Próximo mes: <strong>200 citas gratis</strong> de nuevo</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Payment card --}}
        <div class="bg-white rounded-2xl border-2 border-[#D97706] p-8 text-center mb-8 shadow-sm">
            <p class="text-[#666666] text-sm mb-1">Pago único</p>
            <div class="text-5xl font-bold text-[#0F172A] mb-1" style="font-family:Poppins">${{ number_format($price) }}</div>
            <p class="text-[#666666] mb-8">COP · IVA incluido</p>

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
                   class="inline-block px-8 py-4 bg-[#D97706] text-white font-bold rounded-xl hover:bg-[#B45309] transition text-lg">
                    Pagar con Wompi
                </a>
            </noscript>
        </div>

        {{-- Payment methods --}}
        <div class="text-center mb-8">
            <p class="text-xs text-[#666666] mb-3 font-medium">Métodos de pago aceptados</p>
            <div class="flex justify-center gap-3 flex-wrap">
                <span class="flex items-center gap-1.5 px-3 py-2 bg-white rounded-lg text-xs text-[#666666] border border-[#E7E5DF]">
                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Tarjeta
                </span>
                <span class="flex items-center gap-1.5 px-3 py-2 bg-white rounded-lg text-xs text-[#666666] border border-[#E7E5DF]">
                    <svg class="w-4 h-4 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    PSE
                </span>
                <span class="flex items-center gap-1.5 px-3 py-2 bg-white rounded-lg text-xs text-[#666666] border border-[#E7E5DF]">
                    <svg class="w-4 h-4 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Nequi
                </span>
                <span class="flex items-center gap-1.5 px-3 py-2 bg-white rounded-lg text-xs text-[#666666] border border-[#E7E5DF]">
                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Bancolombia
                </span>
                <span class="flex items-center gap-1.5 px-3 py-2 bg-white rounded-lg text-xs text-[#666666] border border-[#E7E5DF]">
                    <svg class="w-4 h-4 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Efecty
                </span>
            </div>
        </div>

        {{-- Security --}}
        <div class="flex items-center justify-center gap-2 text-xs text-[#666666]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Pago seguro procesado por Wompi · Datos encriptados
        </div>

        <div class="text-center mt-6">
            <a href="{{ filament()->getUrl() }}" class="text-sm text-[#666666] hover:text-[#D97706] transition">← Volver al panel</a>
        </div>
    </div>
</x-layouts.booking>
