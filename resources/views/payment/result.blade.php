<x-layouts.booking title="Resultado del pago">
    <div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
        <div class="max-w-md w-full">

            @if($status === 'approved')
                <div class="bg-white rounded-2xl border border-[#E7E5DF] p-8 text-center shadow-sm">
                    <div class="w-20 h-20 bg-[#0D9488]/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-[#0F172A] mb-2" style="font-family:Poppins">¡Pago exitoso!</h1>
                    <p class="text-[#666666] mb-3">Tu negocio <strong>{{ $business->name }}</strong> ahora tiene citas ilimitadas para este mes.</p>
                    <div class="bg-[#0D9488]/5 rounded-xl p-4 mb-6 text-sm text-[#0D9488]">
                        <p class="font-semibold mb-1">✅ Plan desbloqueado</p>
                        <p>Tus clientes ya pueden reservar sin límite hasta fin de mes.</p>
                    </div>
                    <div class="flex flex-col gap-3">
                        <a href="{{ filament()->getUrl() }}" class="px-6 py-3 bg-[#D97706] text-white font-bold rounded-xl hover:bg-[#B45309] transition text-center">
                            Ir a mi panel
                        </a>
                        <a href="{{ route('booking.show', $business->slug) }}" class="px-6 py-3 border border-[#E7E5DF] text-[#666666] font-medium rounded-xl hover:bg-[#FAFAF8] transition text-center text-sm">
                            Ver mi página pública
                        </a>
                    </div>
                </div>

            @elseif($status === 'declined')
                <div class="bg-white rounded-2xl border border-[#E7E5DF] p-8 text-center shadow-sm">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-[#0F172A] mb-2" style="font-family:Poppins">Pago rechazado</h1>
                    <p class="text-[#666666] mb-3">El pago no pudo ser procesado. Esto puede ocurrir por fondos insuficientes o datos incorrectos.</p>
                    <div class="bg-red-50 rounded-xl p-4 mb-6 text-sm text-red-600">
                        <p class="font-semibold mb-1">¿Qué puedes hacer?</p>
                        <ul class="text-left space-y-1 mt-2">
                            <li>• Verifica los datos de tu tarjeta o método de pago</li>
                            <li>• Asegúrate de tener fondos suficientes</li>
                            <li>• Intenta con otro método de pago</li>
                        </ul>
                    </div>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('payment.checkout', $business->slug) }}" class="px-6 py-3 bg-[#D97706] text-white font-bold rounded-xl hover:bg-[#B45309] transition text-center">
                            Intentar de nuevo
                        </a>
                        <a href="{{ filament()->getUrl() }}" class="px-6 py-3 border border-[#E7E5DF] text-[#666666] font-medium rounded-xl hover:bg-[#FAFAF8] transition text-center text-sm">
                            Volver al panel
                        </a>
                    </div>
                </div>

            @else
                <div class="bg-white rounded-2xl border border-[#E7E5DF] p-8 text-center shadow-sm">
                    <div class="w-20 h-20 bg-[#D97706]/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#D97706] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-[#0F172A] mb-2" style="font-family:Poppins">Pago en proceso</h1>
                    <p class="text-[#666666] mb-3">Tu pago para <strong>{{ $business->name }}</strong> está siendo verificado.</p>
                    <div class="bg-[#D97706]/5 rounded-xl p-4 mb-6 text-sm text-[#D97706]">
                        <p class="font-semibold mb-1">⏳ Esto puede tomar unos minutos</p>
                        <p>Te notificaremos por WhatsApp cuando se confirme. Tu plan se desbloqueará automáticamente.</p>
                    </div>
                    <a href="{{ filament()->getUrl() }}" class="inline-flex px-6 py-3 bg-[#D97706] text-white font-bold rounded-xl hover:bg-[#B45309] transition text-center">
                        Ir a mi panel
                    </a>
                </div>
            @endif

            <p class="text-center text-xs text-[#666666] mt-8">
                Pago procesado por Wompi ·
                <a href="mailto:webcitora@gmail.com" class="text-[#D97706] hover:underline">¿Necesitas ayuda?</a>
            </p>
        </div>
    </div>
</x-layouts.booking>
