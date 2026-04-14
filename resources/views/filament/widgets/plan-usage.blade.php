<x-filament-widgets::widget>
    <x-filament::section>
        @if($isUnlocked)
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:12px">
                    <span style="font-size:24px">🚀</span>
                    <div>
                        <p style="font-weight:700;font-size:15px">Plan Ilimitado activo</p>
                        <p style="font-size:13px;color:#6b7280">
                            {{ $used }} citas este mes · Vence {{ $expiresAt }}
                            @if($daysLeft !== null)
                                <span style="color:#059669;font-weight:600">({{ $daysLeft }} días restantes)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

        @elseif($isBlocked)
            <div style="display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;align-items:flex-start;gap:12px">
                    <span style="font-size:24px">🔒</span>
                    <div>
                        <p style="font-weight:700;font-size:15px;color:#dc2626">Reservas pausadas</p>
                        <p style="font-size:13px;color:#6b7280;margin-top:2px">
                            Has usado las {{ $limit }} citas gratuitas. Tus clientes no pueden reservar.
                        </p>
                    </div>
                </div>

                <div style="width:100%;background:#e5e7eb;border-radius:9999px;height:10px;overflow:hidden">
                    <div style="height:10px;border-radius:9999px;background:#dc2626;width:100%"></div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                    <span style="font-size:13px;color:#dc2626;font-weight:600">{{ $used }} / {{ $limit }} citas usadas</span>
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        <a href="{{ route('payment.checkout', ['business' => $slug, 'plan' => 'monthly']) }}"
                           style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#d97706;color:white;font-weight:700;border-radius:10px;font-size:13px;text-decoration:none">
                            🔓 Mensual · $34.900
                        </a>
                        <a href="{{ route('payment.checkout', ['business' => $slug, 'plan' => 'semester']) }}"
                           style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#0d9488;color:white;font-weight:700;border-radius:10px;font-size:13px;text-decoration:none">
                            🔓 Semestral · $179.400
                        </a>
                    </div>
                </div>

                <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;margin-top:4px">
                    <p style="font-size:12px;font-weight:700;color:#92400e;margin-bottom:6px">Con el plan ilimitado obtienes:</p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px">
                        <span style="font-size:11px;color:#92400e">✅ Citas sin límite</span>
                        <span style="font-size:11px;color:#92400e">✅ WhatsApp automático</span>
                        <span style="font-size:11px;color:#92400e">✅ Sin suscripción</span>
                        <span style="font-size:11px;color:#92400e">✅ Semestral ahorra 15%</span>
                    </div>
                </div>
            </div>

        @else
            <div style="display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <span style="font-size:20px">⚡</span>
                        <div>
                            <p style="font-weight:700;font-size:14px">Plan Gratuito</p>
                            <p style="font-size:12px;color:#6b7280">{{ $remaining }} citas disponibles</p>
                        </div>
                    </div>
                    <a href="{{ route('payment.checkout', ['business' => $slug, 'plan' => 'monthly']) }}"
                       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#d97706;color:white;font-weight:600;border-radius:8px;font-size:12px;text-decoration:none">
                        🚀 Pasar a ilimitado · desde $34.900
                    </a>
                </div>

                <div style="width:100%;background:#e5e7eb;border-radius:9999px;height:10px;overflow:hidden">
                    <div style="height:10px;border-radius:9999px;transition:all 1s;background:{{ $percentage >= 80 ? '#f59e0b' : '#10b981' }};width:{{ $percentage }}%"></div>
                </div>

                <div style="display:flex;justify-content:space-between;font-size:12px;color:#6b7280">
                    <span><strong style="color:#111827">{{ $used }}</strong> usadas</span>
                    <span><strong style="color:#111827">{{ $limit }}</strong> total</span>
                </div>

                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 14px;display:flex;flex-wrap:wrap;gap:12px;align-items:center">
                    <span style="font-size:11px;color:#166534">✅ Reservas online</span>
                    <span style="font-size:11px;color:#166534">✅ WhatsApp automático</span>
                    <span style="font-size:11px;color:#166534">✅ Panel de control</span>
                    <span style="font-size:11px;color:#166534;font-weight:600">Mejora a ilimitado →</span>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
