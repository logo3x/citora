<x-layouts.booking :title="$business->name">

    {{-- Hero / Banner --}}
    <div class="relative">
        @if($business->getFirstMediaUrl('banner'))
            <img src="{{ $business->getFirstMediaUrl('banner') }}" alt="{{ $business->name }}" class="w-full h-40 sm:h-48 object-cover">
        @else
            <div class="w-full h-40 sm:h-48" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%)"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6">
            <div class="max-w-2xl mx-auto flex items-end gap-3">
                @if($business->getFirstMediaUrl('logo'))
                    <img src="{{ $business->getFirstMediaUrl('logo') }}" alt="" class="w-14 h-14 rounded-xl border-2 border-white/80 shadow-lg object-cover">
                @endif
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white drop-shadow" style="font-family:Poppins">{{ $business->name }}</h1>
                    @if($business->slogan)
                        <p class="text-white/80 text-sm">{{ $business->slogan }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Business info collapsible --}}
    <div class="max-w-2xl mx-auto px-4 pt-8 pb-4">
        <details class="bg-white rounded-2xl border border-[#E7E5DF] overflow-hidden shadow-sm">
            <summary class="px-5 py-4 cursor-pointer flex items-center justify-between hover:bg-[#FAFAF8] transition">
                <div class="flex items-center gap-3 min-w-0">
                    @if($business->getFirstMediaUrl('logo'))
                        <img src="{{ $business->getFirstMediaUrl('logo') }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0 border border-[#E7E5DF]">
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#0F172A]">{{ $business->name }}</p>
                        <p class="text-xs text-[#666666]">
                            @if($business->phone){{ $business->phone }}@endif
                            @if($business->phone && $business->address) · @endif
                            @if($business->address){{ Str::limit($business->address, 30) }}@endif
                        </p>
                    </div>
                </div>
                <span class="text-xs text-[#D97706] font-medium shrink-0 ml-3">Ver más ▾</span>
            </summary>
            <div class="px-5 pb-5 border-t border-[#E7E5DF]">
                @if($business->description)
                    <p class="text-sm text-[#666666] leading-relaxed mt-4">{{ $business->description }}</p>
                @endif

                <div class="space-y-2.5 text-sm mt-4">
                    @if($business->address)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($business->address) }}" target="_blank" class="flex items-start gap-2.5 text-[#666666] hover:text-[#2563EB] transition">
                        <svg class="w-4 h-4 text-[#2563EB] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $business->address }}
                    </a>
                    @endif
                    @if($business->phone)
                    <a href="https://wa.me/57{{ $business->phone }}" target="_blank" class="flex items-center gap-2 text-[#666666] hover:text-[#0D9488] transition">
                        <svg class="w-4 h-4 text-[#0D9488] shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        {{ $business->phone }}
                    </a>
                    @endif
                    @if($business->email)
                    <a href="mailto:{{ $business->email }}" class="flex items-center gap-2 text-[#666666] hover:text-[#2563EB] transition">
                        <svg class="w-4 h-4 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $business->email }}
                    </a>
                    @endif
                </div>

                {{-- Schedule --}}
                @if($business->schedules->count() > 0)
                @php $days = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado']; @endphp
                <div class="mt-4 pt-4 border-t border-[#E7E5DF]">
                    <p class="text-xs font-bold text-[#0F172A] mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Horario de atención
                    </p>
                    <div class="space-y-1.5">
                        @foreach($business->schedules->sortBy(fn($s) => $s->day_of_week === 0 ? 7 : $s->day_of_week) as $schedule)
                        <div class="flex justify-between text-xs {{ $schedule->day_of_week === now()->dayOfWeek ? 'text-[#D97706] font-semibold' : 'text-[#666666]' }}">
                            <span>{{ $days[$schedule->day_of_week] }}</span>
                            <span>{{ $schedule->is_active ? \Carbon\Carbon::parse($schedule->open_time)->format('g:i A').' - '.\Carbon\Carbon::parse($schedule->close_time)->format('g:i A') : 'Cerrado' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Team --}}
                @if($business->employees->count() > 0)
                <div class="mt-4 pt-4 border-t border-[#E7E5DF]">
                    <p class="text-xs font-bold text-[#0F172A] mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Nuestro equipo
                    </p>
                    <div class="flex flex-wrap gap-4">
                        @foreach($business->employees as $emp)
                        <div class="flex items-center gap-2.5">
                            @if($emp->getFirstMediaUrl('photo'))
                                <img src="{{ $emp->getFirstMediaUrl('photo') }}" alt="" class="w-9 h-9 rounded-full object-cover border border-[#E7E5DF]">
                            @else
                                <div class="w-9 h-9 rounded-full bg-[#0D9488]/10 flex items-center justify-center text-[#0D9488] font-bold text-xs">{{ substr($emp->name, 0, 1) }}</div>
                            @endif
                            <div>
                                <span class="text-xs font-medium text-[#0F172A]">{{ $emp->name }}</span>
                                @if($emp->position)<p class="text-[10px] text-[#666666]">{{ $emp->position }}</p>@endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </details>
    </div>

    {{-- Booking Container --}}
    <div class="max-w-2xl mx-auto px-4 pt-8 pb-10">

        {{-- Progress Steps --}}
        <div class="flex items-center justify-center gap-3 mb-10">
            <div id="progress-1" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-sm font-bold">1</span>
                <span class="text-sm font-medium text-gray-900 hidden sm:inline">Servicio</span>
            </div>
            <div class="w-8 h-px bg-gray-300"></div>
            <div id="progress-2" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-bold">2</span>
                <span class="text-sm font-medium text-gray-400 hidden sm:inline">Horario</span>
            </div>
            <div class="w-8 h-px bg-gray-300"></div>
            <div id="progress-3" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-bold">3</span>
                <span class="text-sm font-medium text-gray-400 hidden sm:inline">Confirmar</span>
            </div>
        </div>

        @unless($canBook)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center">
            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="font-bold text-gray-900 mb-1">Reservas temporalmente no disponibles</h3>
            <p class="text-gray-500 text-sm">Este negocio ha alcanzado su límite de citas del mes. Por favor contacta directamente al negocio.</p>
            @if($business->phone)
                <a href="https://wa.me/57{{ $business->phone }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                    Contactar por WhatsApp
                </a>
            @endif
        </div>
        @else

        {{-- STEP 1: Select Service --}}
        <div id="step-1" class="step active">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Elige un servicio</h2>
            <div class="grid gap-3">
                @foreach($business->services as $service)
                <div class="service-card cursor-pointer bg-white rounded-xl border-2 border-gray-100 p-4 transition hover:border-amber-300 flex gap-4"
                     data-service-id="{{ $service->id }}"
                     data-service-name="{{ $service->name }}"
                     data-service-duration="{{ $service->duration_minutes }}"
                     data-service-price="{{ $service->price }}"
                     onclick="selectService(this)">
                    @if($service->getFirstMediaUrl('image'))
                        <img src="{{ $service->getFirstMediaUrl('image') }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900">{{ $service->name }}</h3>
                        @if($service->description)
                            <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ $service->description }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $service->duration_minutes }} min
                            </span>
                            <span class="text-sm font-bold text-amber-600">${{ number_format($service->price) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-300 check-icon hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- STEP 2: Select Employee + Date + Time --}}
        <div id="step-2" class="step">
            <button onclick="goToStep(1)" class="text-amber-600 text-sm font-medium mb-4 flex items-center gap-1 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Cambiar servicio
            </button>

            {{-- Employee Selection --}}
            @if($business->employees->count() > 0)
            <h3 class="text-lg font-bold text-gray-900 mb-3">Elige un profesional <span class="text-sm font-normal text-gray-400">(opcional)</span></h3>
            <div class="flex gap-3 overflow-x-auto pb-3 mb-6" id="employees-container">
                <div class="employee-card cursor-pointer flex-shrink-0 w-20 text-center selected" data-employee-id="" onclick="selectEmployee(this)">
                    <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center border-2 border-transparent">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xs mt-1 block text-gray-600">Cualquiera</span>
                </div>
                @foreach($business->employees as $employee)
                <div class="employee-card cursor-pointer flex-shrink-0 w-20 text-center"
                     data-employee-id="{{ $employee->id }}"
                     data-employee-services="{{ $employee->services->pluck('id')->join(',') }}"
                     onclick="selectEmployee(this)">
                    @if($employee->getFirstMediaUrl('photo'))
                        <img src="{{ $employee->getFirstMediaUrl('photo') }}" alt="" class="w-16 h-16 mx-auto rounded-full object-cover border-2 border-transparent">
                    @else
                        <div class="w-16 h-16 mx-auto rounded-full bg-amber-100 flex items-center justify-center border-2 border-transparent text-amber-700 font-bold text-lg">
                            {{ substr($employee->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="text-xs mt-1 block text-gray-600 truncate">{{ $employee->name }}</span>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Date Selection --}}
            <h3 class="text-lg font-bold text-gray-900 mb-3">Selecciona fecha</h3>
            <div class="flex gap-2 overflow-x-auto pb-3 mb-6" id="dates-container"></div>

            {{-- Time Selection --}}
            <h3 class="text-lg font-bold text-gray-900 mb-3">Selecciona hora</h3>
            <div id="slots-container" class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-4">
                <p class="col-span-full text-gray-400 text-sm text-center py-4">Selecciona una fecha para ver horarios disponibles</p>
            </div>
            <div id="slots-loading" class="hidden text-center py-4">
                <svg class="animate-spin h-6 w-6 text-amber-500 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </div>

            <button id="btn-continue" onclick="goToStep(3)" disabled class="w-full mt-4 py-3 bg-amber-500 text-white font-bold rounded-xl disabled:opacity-40 disabled:cursor-not-allowed hover:bg-amber-600 transition">
                Continuar
            </button>
        </div>

        {{-- STEP 3: Confirm --}}
        <div id="step-3" class="step">
            <button onclick="goToStep(2)" class="text-amber-600 text-sm font-medium mb-4 flex items-center gap-1 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Cambiar horario
            </button>

            <h2 class="text-xl font-bold text-gray-900 mb-4">Confirma tu cita</h2>

            <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 space-y-3">
                <div class="flex justify-between"><span class="text-gray-500">Servicio</span><span id="confirm-service" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Profesional</span><span id="confirm-employee" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Fecha</span><span id="confirm-date" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Hora</span><span id="confirm-time" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Duración</span><span id="confirm-duration" class="font-semibold"></span></div>
                <hr>
                <div class="flex justify-between"><span class="text-gray-500">Precio</span><span id="confirm-price" class="font-bold text-amber-600 text-lg"></span></div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de celular *</label>
                    <input type="tel" id="input-phone" value="{{ auth()->user()?->phone ?? '' }}" placeholder="3001234567" maxlength="20" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500">
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        📱 Lo usamos únicamente para enviarte la <strong>confirmación</strong> y los <strong>recordatorios</strong> de tu cita por SMS. No compartimos tu número con terceros.
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas (opcional)</label>
                    <textarea id="input-notes" rows="2" maxlength="1000" placeholder="Algún detalle adicional..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500"></textarea>
                </div>
                <button id="btn-book" onclick="handleConfirm()" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Confirmar cita
                </button>
            </div>
        </div>
    </div>

    @endunless

    </div>

    {{-- Footer --}}
    <div class="text-center py-6 text-xs text-[#666666] border-t border-[#E7E5DF] mt-6 space-y-2">
        <div>Powered by <a href="/" class="text-[#D97706] hover:underline font-medium">Citora</a></div>
        <div>
            <a href="{{ route('legal.privacy') }}" class="text-[#666666] hover:text-[#D97706]">Privacidad</a>
            <span class="mx-2">·</span>
            <a href="{{ route('legal.terms') }}" class="text-[#666666] hover:text-[#D97706]">Términos</a>
        </div>
    </div>

    <script>
        const SLUG = '{{ $business->slug }}';
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const IS_AUTH = {{ auth()->check() ? 'true' : 'false' }};
        const LOGIN_URL = '{{ route('auth.google.redirect') }}';

        let booking = { serviceId: null, serviceName: '', employeeId: null, employeeName: 'Cualquiera', date: null, dateLabel: '', time: null, timeLabel: '', duration: 0, price: 0 };

        // Generate next 14 days
        function initDates() {
            const container = document.getElementById('dates-container');
            const days = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
            const months = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

            for (let i = 0; i < 14; i++) {
                const d = new Date();
                d.setDate(d.getDate() + i);
                const iso = d.toISOString().split('T')[0];
                const btn = document.createElement('button');
                btn.className = 'date-btn flex-shrink-0 w-16 py-3 rounded-xl border-2 border-gray-100 text-center hover:border-amber-300 transition';
                btn.dataset.date = iso;
                btn.innerHTML = `<span class="block text-xs text-gray-400">${days[d.getDay()]}</span><span class="block text-lg font-bold">${d.getDate()}</span><span class="block text-xs text-gray-400">${months[d.getMonth()]}</span>`;
                btn.onclick = () => selectDate(btn, iso, `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`);
                container.appendChild(btn);
            }
        }

        function selectService(el) {
            document.querySelectorAll('.service-card').forEach(c => { c.classList.remove('selected'); c.querySelector('.check-icon')?.classList.add('hidden'); });
            el.classList.add('selected');
            el.querySelector('.check-icon')?.classList.remove('hidden');

            booking.serviceId = el.dataset.serviceId;
            booking.serviceName = el.dataset.serviceName;
            booking.duration = el.dataset.serviceDuration;
            booking.price = el.dataset.servicePrice;

            // Reset selections
            booking.employeeId = null;
            booking.employeeName = 'Cualquiera';
            booking.time = null;
            booking.timeLabel = '';
            document.getElementById('btn-continue').disabled = true;
            document.querySelectorAll('.employee-card').forEach(c => c.classList.remove('selected'));
            document.querySelector('.employee-card[data-employee-id=""]')?.classList.add('selected');

            filterEmployees();

            // Reload slots if date was already selected
            if (booking.date) loadSlots();

            goToStep(2);
        }

        function filterEmployees() {
            document.querySelectorAll('.employee-card[data-employee-id]').forEach(card => {
                if (!card.dataset.employeeId) return;
                const services = card.dataset.employeeServices?.split(',') || [];
                card.style.display = (services.includes(booking.serviceId) || services.length === 0) ? '' : 'none';
            });
        }

        function selectEmployee(el) {
            document.querySelectorAll('.employee-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            booking.employeeId = el.dataset.employeeId || null;
            booking.employeeName = el.querySelector('span').textContent.trim();
            if (booking.date) loadSlots();
        }

        function selectDate(el, date, label) {
            document.querySelectorAll('.date-btn').forEach(b => { b.classList.remove('border-amber-500', 'bg-amber-50'); b.classList.add('border-gray-100'); });
            el.classList.remove('border-gray-100');
            el.classList.add('border-amber-500', 'bg-amber-50');
            booking.date = date;
            booking.dateLabel = label;
            booking.time = null;
            document.getElementById('btn-continue').disabled = true;
            loadSlots();
        }

        async function loadSlots() {
            const container = document.getElementById('slots-container');
            const loading = document.getElementById('slots-loading');
            container.innerHTML = '';
            loading.classList.remove('hidden');

            const params = new URLSearchParams({ date: booking.date, service_id: booking.serviceId });
            if (booking.employeeId) params.append('employee_id', booking.employeeId);

            try {
                const res = await fetch(`/${SLUG}/slots?${params}`);
                const slots = await res.json();
                loading.classList.add('hidden');

                const entries = Object.entries(slots);
                if (entries.length === 0) {
                    container.innerHTML = '<p class="col-span-full text-gray-400 text-sm text-center py-4">No hay horarios disponibles para esta fecha</p>';
                    return;
                }

                entries.forEach(([time, label]) => {
                    const btn = document.createElement('button');
                    btn.className = 'slot-btn py-2.5 px-3 rounded-lg border border-gray-200 text-sm font-medium hover:border-amber-400 transition';
                    btn.textContent = label;
                    btn.onclick = () => selectSlot(btn, time, label);
                    container.appendChild(btn);
                });
            } catch (e) {
                loading.classList.add('hidden');
                container.innerHTML = '<p class="col-span-full text-red-500 text-sm text-center py-4">Error al cargar horarios</p>';
            }
        }

        function selectSlot(el, time, label) {
            document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
            el.classList.add('selected');
            booking.time = time;
            booking.timeLabel = label;
            document.getElementById('btn-continue').disabled = false;
        }

        function goToStep(n) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById(`step-${n}`).classList.add('active');

            for (let i = 1; i <= 3; i++) {
                const p = document.getElementById(`progress-${i}`);
                const circle = p.querySelector('span:first-child');
                const label = p.querySelector('span:last-child');
                if (i <= n) {
                    circle.className = 'w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-sm font-bold';
                    if (label) label.className = 'text-sm font-medium text-gray-900 hidden sm:inline';
                } else {
                    circle.className = 'w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-bold';
                    if (label) label.className = 'text-sm font-medium text-gray-400 hidden sm:inline';
                }
            }

            if (n === 3) {
                document.getElementById('confirm-service').textContent = booking.serviceName;
                document.getElementById('confirm-employee').textContent = booking.employeeName;
                document.getElementById('confirm-date').textContent = booking.dateLabel;
                document.getElementById('confirm-time').textContent = booking.timeLabel;
                document.getElementById('confirm-duration').textContent = booking.duration + ' min';
                document.getElementById('confirm-price').textContent = '$' + Number(booking.price).toLocaleString();
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function handleConfirm() {
            if (!IS_AUTH) {
                // Guardar estado en localStorage antes de redirigir a Google
                localStorage.setItem('citora_booking_' + SLUG, JSON.stringify(booking));
                window.location.href = LOGIN_URL + '?redirect_to=' + encodeURIComponent(window.location.href);
                return;
            }
            submitBooking();
        }

        async function submitBooking() {
            const phone = document.getElementById('input-phone').value.trim();
            if (!phone) { Swal.fire('', 'El teléfono es obligatorio', 'warning'); return; }

            const btn = document.getElementById('btn-book');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Reservando...';

            try {
                const res = await fetch(`/${SLUG}/book`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({
                        service_id: booking.serviceId,
                        employee_id: booking.employeeId,
                        date: booking.date,
                        time: booking.time,
                        phone: phone,
                        notes: document.getElementById('input-notes').value.trim(),
                    }),
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.error || data.message || 'Error al reservar');
                }

                localStorage.removeItem('citora_booking_' + SLUG);

                await Swal.fire({
                    icon: 'success',
                    title: '🎉 ¡Cita agendada!',
                    html: `<p><strong>${data.appointment.service}</strong></p><p>${data.appointment.date}</p><p>${data.appointment.time}</p>`,
                    confirmButtonText: 'Perfecto',
                    confirmButtonColor: '#f59e0b',
                });

                window.location.reload();
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Confirmar cita';
            }
        }

        // Restaurar booking guardado después del login con Google
        function restoreBooking() {
            const saved = localStorage.getItem('citora_booking_' + SLUG);
            if (!saved || !IS_AUTH) return;

            try {
                const restored = JSON.parse(saved);
                if (!restored.serviceId || !restored.date || !restored.time) return;

                booking = restored;

                // Marcar servicio seleccionado
                const serviceCard = document.querySelector(`.service-card[data-service-id="${booking.serviceId}"]`);
                if (serviceCard) {
                    serviceCard.classList.add('selected');
                    serviceCard.querySelector('.check-icon')?.classList.remove('hidden');
                }

                // Ir directo al paso 3 con los datos restaurados
                goToStep(3);

                localStorage.removeItem('citora_booking_' + SLUG);
            } catch (e) {
                localStorage.removeItem('citora_booking_' + SLUG);
            }
        }

        initDates();
        restoreBooking();
    </script>

</x-layouts.booking>
