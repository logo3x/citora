<x-layouts.booking title="Desbloquear citas">
    <div class="max-w-2xl mx-auto px-4 py-10">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Desbloquea tu negocio</h1>
            <p class="text-gray-500 mt-2 text-lg">{{ $business->name }} · {{ now()->translatedFormat('F Y') }}</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-6 mb-8">
            {{-- Current usage --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Tu uso actual</h3>
                <div class="flex items-end gap-3 mb-3">
                    <span class="text-4xl font-bold text-amber-600">{{ $used }}</span>
                    <span class="text-gray-400 text-lg mb-1">/ {{ $limit }} citas</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                    <div class="h-3 rounded-full transition-all {{ $used >= $limit ? 'bg-red-500' : 'bg-amber-500' }}"
                         style="width: {{ min(100, ($used / max(1, $limit)) * 100) }}%"></div>
                </div>
                @if($used >= $limit)
                    <p class="text-sm text-red-600 font-medium">Has alcanzado el límite. Tus clientes no pueden reservar.</p>
                @else
                    <p class="text-sm text-gray-500">{{ $limit - $used }} citas restantes este mes.</p>
                @endif
            </div>

            {{-- Benefits --}}
            <div class="bg-amber-50 rounded-2xl border border-amber-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Con el desbloqueo obtienes</h3>
                <ul class="space-y-2.5">
                    <li class="flex items-start gap-2 text-sm">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span><strong>Citas ilimitadas</strong> por el resto del mes</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Tus clientes vuelven a reservar <strong>al instante</strong></span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span><strong>No es suscripción</strong> — un solo pago por este mes</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>WhatsApp automático incluido</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>El próximo mes vuelves a tener <strong>200 gratis</strong></span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Payment card --}}
        <div class="bg-white rounded-2xl border-2 border-amber-500 p-8 text-center mb-6">
            <p class="text-gray-500 text-sm mb-1">Pago único</p>
            <div class="text-5xl font-bold text-gray-900 mb-1">${{ number_format($price) }}</div>
            <p class="text-gray-400 mb-6">COP · IVA incluido</p>

            <form action="{{ config('services.wompi.base_url') }}/v1/payment_links" method="GET"
                  id="wompi-form">
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
                   class="inline-block px-8 py-4 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition text-lg">
                    Pagar con Wompi
                </a>
            </noscript>
        </div>

        {{-- Payment methods --}}
        <div class="text-center mb-6">
            <p class="text-sm text-gray-400 mb-3">Métodos de pago aceptados</p>
            <div class="flex justify-center gap-4 flex-wrap">
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Tarjeta crédito/débito
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    PSE
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Nequi
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Bancolombia
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Efecty
                </div>
            </div>
        </div>

        {{-- Security & trust --}}
        <div class="flex items-center justify-center gap-2 text-xs text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Pago seguro procesado por Wompi · Datos encriptados
        </div>

        <div class="text-center mt-6">
            <a href="{{ filament()->getUrl() }}" class="text-sm text-gray-400 hover:text-amber-500">Volver al panel</a>
        </div>
    </div>
</x-layouts.booking>
