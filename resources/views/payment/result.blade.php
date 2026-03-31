<x-layouts.booking title="Resultado del pago">
    <div class="max-w-lg mx-auto px-4 py-12 text-center">
        @if($status === 'approved')
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">¡Pago exitoso!</h1>
            <p class="text-gray-500 mb-8">Tu negocio ahora tiene citas ilimitadas para este mes.</p>
        @elseif($status === 'declined')
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pago rechazado</h1>
            <p class="text-gray-500 mb-8">El pago no pudo ser procesado. Intenta de nuevo.</p>
        @else
            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pago en proceso</h1>
            <p class="text-gray-500 mb-8">Tu pago está siendo procesado. Te notificaremos cuando se confirme.</p>
        @endif

        <a href="{{ filament()->getUrl() }}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition">
            Ir al panel de administración
        </a>
    </div>
</x-layouts.booking>
