<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            {{-- Left: Usage info --}}
            <div class="flex-1 w-full">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $isUnlocked ? '🚀 Plan Ilimitado' : '📊 Plan Gratuito' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $period }}</p>
                    </div>
                    @if($isUnlocked)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 text-sm font-bold rounded-full dark:bg-green-900 dark:text-green-300">
                            ✅ Desbloqueado
                        </span>
                    @elseif($isBlocked)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 text-sm font-bold rounded-full dark:bg-red-900 dark:text-red-300 animate-pulse">
                            🔒 Bloqueado
                        </span>
                    @endif
                </div>

                {{-- Progress bar --}}
                <div class="relative w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 mb-2 overflow-hidden">
                    <div class="absolute inset-0 h-6 rounded-full transition-all duration-1000 ease-out {{ $percentage >= 100 ? 'bg-red-500' : ($percentage >= 80 ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-gradient-to-r from-green-400 to-green-500') }}"
                         style="width: {{ $percentage }}%"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xs font-bold {{ $percentage > 45 ? 'text-white' : 'text-gray-600' }}">
                            {{ $used }} / {{ $limit }} citas
                        </span>
                    </div>
                </div>

                <div class="flex justify-between text-sm mt-1">
                    @if($isUnlocked)
                        <span class="text-green-600 dark:text-green-400 font-medium">✨ Citas ilimitadas este mes</span>
                    @elseif($isBlocked)
                        <span class="text-red-600 dark:text-red-400 font-medium">Tus clientes no pueden reservar</span>
                    @elseif($remaining <= 20)
                        <span class="text-amber-600 dark:text-amber-400 font-medium">⚠️ Solo quedan {{ $remaining }} citas</span>
                    @else
                        <span class="text-gray-500 dark:text-gray-400">{{ $remaining }} citas disponibles</span>
                    @endif
                    <span class="text-gray-400 text-xs">Se reinicia el 1 del próximo mes</span>
                </div>
            </div>

            {{-- Right: CTA button --}}
            @if(!$isUnlocked)
                <div class="flex flex-col items-center gap-2 lg:min-w-[200px]">
                    <a href="{{ route('payment.checkout', $slug) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 {{ $isBlocked ? 'bg-red-500 hover:bg-red-600 animate-pulse' : 'bg-amber-500 hover:bg-amber-600' }} text-white font-bold rounded-xl transition text-center">
                        @if($isBlocked)
                            🔓 Desbloquear ahora
                        @else
                            ⚡ Pasar a ilimitado
                        @endif
                    </a>
                    <span class="text-xs text-gray-400">$29,900 COP · Pago único</span>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
