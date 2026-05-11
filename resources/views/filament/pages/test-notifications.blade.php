<x-filament-panels::page>

    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 mb-4">
        <div style="display:flex;align-items:flex-start;gap:14px;padding:16px;background:#FEF3C7;border:1px solid #FDE68A;border-radius:12px">
            <div style="font-size:28px">💡</div>
            <div>
                <p style="font-weight:700;color:#92400E;margin-bottom:6px;font-size:14px">¿Cómo funciona?</p>
                <p style="color:#78350F;font-size:13px;line-height:1.5">
                    Cada tarjeta abajo es uno de los correos automáticos que Citora manda en cada evento.
                    Click en <strong>Vista previa</strong> para verlo o <strong>Enviarme</strong> para que te llegue a
                    <code style="background:rgba(180,83,9,0.1);padding:2px 6px;border-radius:4px;font-size:12px;color:#92400E">{{ auth()->user()->email }}</code>
                    y lo veas en tu bandeja real.
                </p>
            </div>
        </div>
    </div>

    <div class="citora-test-grid">
        @foreach ($this->getNotificationTypes() as $i => $type)
            <div class="citora-test-card citora-test-card--{{ $type['color'] }}">
                <div class="citora-test-card__head">
                    <h3 class="citora-test-card__title">{{ $type['label'] }}</h3>
                    <p class="citora-test-card__desc">{{ $type['description'] }}</p>
                </div>
                <div class="citora-test-card__actions">
                    <a href="#preview-{{ $i }}"
                       onclick="event.preventDefault(); document.getElementById('preview-iframe-{{ $i }}').classList.toggle('open');"
                       class="citora-btn citora-btn--ghost">
                        👁️ Vista previa
                    </a>
                    <button type="button"
                            wire:click="sendTest('{{ $type['event'] }}', '{{ $type['role'] }}')"
                            wire:loading.attr="disabled"
                            class="citora-btn citora-btn--primary">
                        📧 Enviarme
                    </button>
                </div>
                <div id="preview-iframe-{{ $i }}" class="citora-test-card__preview">
                    @php
                        $appointment = $this->demoAppointment();
                        $extra = $this->extraDataFor($type['event']);
                        $mail = new \App\Mail\AppointmentStatusMail($appointment, $type['event'], $type['role'], $extra);
                    @endphp
                    <div class="citora-test-card__preview-frame">
                        {!! $mail->render() !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .citora-test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }
        .citora-test-card {
            background: white;
            border: 1px solid #E7E5DF;
            border-radius: 14px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            transition: box-shadow 0.15s ease, transform 0.1s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .citora-test-card:hover {
            box-shadow: 0 6px 18px rgba(217,119,6,0.10);
            transform: translateY(-2px);
        }
        .dark .citora-test-card {
            background: rgb(31 41 55);
            border-color: rgba(255,255,255,0.1);
        }
        .citora-test-card--success { border-top: 3px solid #059669; }
        .citora-test-card--info    { border-top: 3px solid #2563EB; }
        .citora-test-card--warning { border-top: 3px solid #F59E0B; }
        .citora-test-card--danger  { border-top: 3px solid #DC2626; }
        .citora-test-card__title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: #0F172A;
            line-height: 1.3;
            margin: 0 0 6px 0;
        }
        .dark .citora-test-card__title { color: #F8FAFC; }
        .citora-test-card__desc {
            font-size: 12.5px;
            color: #64748B;
            line-height: 1.5;
            margin: 0;
        }
        .dark .citora-test-card__desc { color: #94A3B8; }
        .citora-test-card__actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }
        .citora-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            border: none;
            font-family: inherit;
            text-decoration: none;
        }
        .citora-btn--ghost {
            background: white;
            color: #475569;
            border: 1px solid #E2E8F0;
        }
        .citora-btn--ghost:hover {
            background: #F8FAFC;
            color: #0F172A;
            border-color: #CBD5E1;
        }
        .citora-btn--primary {
            background: linear-gradient(135deg, #D97706, #F59E0B);
            color: white;
            box-shadow: 0 2px 6px rgba(217,119,6,0.25);
        }
        .citora-btn--primary:hover {
            background: linear-gradient(135deg, #B45309, #D97706);
            box-shadow: 0 4px 10px rgba(217,119,6,0.35);
            transform: translateY(-1px);
        }
        .citora-btn--primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .citora-test-card__preview {
            display: none;
            max-height: 0;
            overflow: hidden;
        }
        .citora-test-card__preview.open {
            display: block;
            max-height: 600px;
            overflow-y: auto;
            border-top: 1px solid #E7E5DF;
            margin: 0 -18px -18px -18px;
            padding-top: 0;
        }
        .citora-test-card__preview-frame {
            background: #FAFAF8;
            padding: 12px;
            font-size: 12px;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
        }
        .citora-test-card__preview-frame > * {
            transform: scale(0.85);
            transform-origin: top left;
            width: 117.6%;
        }
    </style>
</x-filament-panels::page>
