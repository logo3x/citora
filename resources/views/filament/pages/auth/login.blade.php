<div class="fi-simple-layout flex min-h-screen">
    {{-- Left side - Brand --}}
    <div class="hidden lg:flex lg:w-1/2 items-center justify-center relative overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%)">
        {{-- Decorative elements --}}
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="absolute top-20 left-20 w-64 h-64 rounded-full" style="background: radial-gradient(circle, #D97706 0%, transparent 70%)"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 rounded-full" style="background: radial-gradient(circle, #0D9488 0%, transparent 70%)"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 rounded-full" style="background: radial-gradient(circle, #2563EB 0%, transparent 70%)"></div>
        </div>

        <div class="relative z-10 text-center px-12 max-w-lg">
            <img src="/images/logo-dark.png" alt="Citora" class="h-16 mx-auto mb-8" onerror="this.style.display='none'">
            <h2 class="text-3xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif">La forma inteligente de gestionar tu agenda</h2>
            <p class="text-gray-400 text-lg leading-relaxed">Reservas online, WhatsApp automático y panel de control. Todo en un solo lugar.</p>

            <div class="mt-12 grid grid-cols-3 gap-6 text-center">
                <div>
                    <div class="text-2xl font-bold text-[#F59E0B]" style="font-family: 'Poppins', sans-serif">200</div>
                    <div class="text-xs text-gray-500 mt-1">Citas gratis/mes</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-[#0D9488]" style="font-family: 'Poppins', sans-serif">24/7</div>
                    <div class="text-xs text-gray-500 mt-1">Reservas online</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-[#5EAEFF]" style="font-family: 'Poppins', sans-serif">WhatsApp</div>
                    <div class="text-xs text-gray-500 mt-1">Automático</div>
                </div>
            </div>

            <p class="text-gray-600 text-xs mt-16">&copy; {{ date('Y') }} Citora. Todos los derechos reservados.</p>
        </div>
    </div>

    {{-- Right side - Login form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white dark:bg-gray-900">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden text-center mb-8">
                <img src="/images/logo-light.png" alt="Citora" class="h-12 mx-auto mb-3" onerror="this.style.display='none'">
                <p class="text-sm text-gray-500">La forma inteligente de gestionar tu agenda</p>
            </div>

            {{-- Greeting --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white" style="font-family: 'Poppins', sans-serif">
                    {{ $this->getHeading() }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Ingresa a tu cuenta para continuar</p>
            </div>

            {{-- Form --}}
            <x-filament-panels::form id="form" wire:submit="authenticate">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="true"
                />
            </x-filament-panels::form>

            {{-- Google button --}}
            {{ filament()->renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER) }}
        </div>
    </div>
</div>
