<x-filament-widgets::widget>
    <x-filament::section>
        @if($isUnlocked)
            {{-- Unlocked state --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">🚀 Plan Ilimitado activo</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $used }} citas este mes · Vence {{ $expiresAt }}
                            @if($daysLeft !== null)
                                <span class="font-medium text-emerald-600">({{ $daysLeft }} días restantes)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

        @elseif($isBlocked)
            {{-- Blocked state --}}
            <div class="flex flex-col gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center shrink-0 animate-pulse">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-red-600 dark:text-red-400">🔒 Reservas pausadas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Has usado las {{ $limit }} citas gratuitas. Tus clientes no pueden reservar hasta que desbloquees.
                        </p>
                    </div>
                </div>

                <div class="relative w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700 overflow-hidden">
                    <div class="absolute inset-0 h-3 rounded-full bg-red-500" style="width: 100%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-red-600 font-semibold">{{ $used }} / {{ $limit }} citas usadas</span>
                    <a href="{{ route('payment.checkout', $slug) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold rounded-xl hover:from-amber-600 hover:to-amber-700 transition shadow-lg shadow-amber-500/25 text-sm">
                        🔓 Desbloquear por $29,900
                    </a>
                </div>
            </div>

        @else
            {{-- Free plan with usage --}}
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Plan Gratuito</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $remaining }} citas disponibles</p>
                        </div>
                    </div>
                    @if($remaining <= 50)
                        <a href="{{ route('payment.checkout', $slug) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition text-xs">
                            ⚡ Pasar a ilimitado
                        </a>
                    @endif
                </div>

                <div class="relative w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-1000 ease-out {{ $percentage >= 80 ? 'bg-gradient-to-r from-amber-400 to-red-500' : 'bg-gradient-to-r from-emerald-400 to-emerald-500' }}"
                         style="width: {{ $percentage }}%"></div>
                </div>

                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span><strong class="text-gray-900 dark:text-white">{{ $used }}</strong> usadas</span>
                    <span><strong class="text-gray-900 dark:text-white">{{ $limit }}</strong> total</span>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
