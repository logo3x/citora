<?php
$secret = 'citora-setup-2026';
if (($_GET['key'] ?? '') !== $secret) die('<!DOCTYPE html><html><body style="font-family:Inter,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#FAFAF8"><div style="text-align:center"><p style="font-size:48px">🔒</p><p style="color:#6b7280">No autorizado</p></div></body></html>');

$step = $_GET['step'] ?? 'info';

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citora Setup</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #FAFAF8; color: #111; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #0F172A, #1E293B); padding: 24px; text-align: center; }
        .header h1 { color: #F59E0B; font-family: 'Poppins', sans-serif; font-size: 22px; }
        .header p { color: #9CA3AF; font-size: 13px; margin-top: 4px; }
        .container { max-width: 600px; margin: 24px auto; padding: 0 16px; }
        .card { background: white; border: 1px solid #E7E5DF; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        .card h2 { font-size: 15px; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .info-item { background: #FAFAF8; border-radius: 8px; padding: 10px 12px; }
        .info-item label { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
        .info-item span { display: block; font-weight: 600; font-size: 14px; margin-top: 2px; }
        .table-list { font-size: 12px; color: #6b7280; columns: 2; column-gap: 12px; }
        .table-list span { display: block; padding: 2px 0; }
        .actions { display: grid; gap: 8px; }
        .btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-primary { background: #D97706; color: white; }
        .btn-primary:hover { background: #B45309; }
        .btn-outline { background: white; color: #374151; border: 1px solid #E7E5DF; }
        .btn-outline:hover { background: #FAFAF8; }
        .btn-danger { background: #dc2626; color: white; }
        .output { background: #0F172A; color: #10b981; border-radius: 10px; padding: 16px; font-family: monospace; font-size: 13px; white-space: pre-wrap; overflow-x: auto; margin-top: 16px; line-height: 1.6; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 600; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .footer { text-align: center; padding: 24px; font-size: 12px; color: #9ca3af; }
        .warning { background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #92400e; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚙️ Citora Setup</h1>
        <p>Panel de configuración del servidor</p>
    </div>

    <div class="container">
        <div class="warning">
            ⚠️ <strong>Elimina este archivo después de configurar.</strong> Es un riesgo de seguridad dejarlo accesible.
        </div>

        <?php if ($step === 'info'): ?>
            <div class="card">
                <h2>📊 Estado del sistema</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>PHP</label>
                        <span><?= PHP_VERSION ?></span>
                    </div>
                    <div class="info-item">
                        <label>Laravel</label>
                        <span><?= app()->version() ?></span>
                    </div>
                    <div class="info-item">
                        <label>Entorno</label>
                        <span><?= app()->environment() ?> <span class="badge <?= app()->environment() === 'production' ? 'badge-green' : 'badge-yellow' ?>"><?= app()->environment() ?></span></span>
                    </div>
                    <div class="info-item">
                        <label>Base de datos</label>
                        <span><?= config('database.connections.mysql.database') ?></span>
                    </div>
                </div>

                <?php
                try {
                    $pdo = DB::connection()->getPdo();
                    $tables = DB::select('SHOW TABLES');
                    $tableCount = count($tables);
                ?>
                <div style="margin-top: 12px">
                    <div class="info-item">
                        <label>Conexión DB</label>
                        <span>✅ OK · <?= $tableCount ?> tablas</span>
                    </div>
                    <?php if ($tableCount > 0): ?>
                    <details style="margin-top: 8px">
                        <summary style="cursor:pointer;font-size:12px;color:#6b7280;font-weight:500">Ver tablas</summary>
                        <div class="table-list" style="margin-top: 6px">
                            <?php foreach ($tables as $t): ?>
                                <span>• <?= array_values((array)$t)[0] ?></span>
                            <?php endforeach; ?>
                        </div>
                    </details>
                    <?php endif; ?>
                </div>
                <?php } catch (Exception $e) { ?>
                <div style="margin-top: 12px; background:#fee2e2; border-radius:8px; padding:10px 12px; color:#991b1b; font-size:13px">
                    ❌ Error de conexión: <?= $e->getMessage() ?>
                </div>
                <?php } ?>
            </div>

            <div class="card">
                <h2>🚀 Acciones</h2>
                <div class="actions">
                    <a href="?key=<?= $secret ?>&step=key" class="btn btn-outline">🔑 Generar App Key</a>
                    <a href="?key=<?= $secret ?>&step=migrate" class="btn btn-primary">📦 Ejecutar migraciones</a>
                    <a href="?key=<?= $secret ?>&step=seed" class="btn btn-outline">🌱 Ejecutar seeders</a>
                    <a href="?key=<?= $secret ?>&step=storage" class="btn btn-outline">🔗 Crear Storage Link</a>
                    <a href="?key=<?= $secret ?>&step=cache" class="btn btn-outline">⚡ Cachear config y rutas</a>
                    <a href="?key=<?= $secret ?>&step=clear" class="btn btn-outline">🧹 Limpiar toda la cache</a>
                    <a href="?key=<?= $secret ?>&step=email-test" class="btn btn-outline">📧 Enviar email de prueba</a>
                    <a href="?key=<?= $secret ?>&step=whatsapp-test" class="btn btn-outline">📲 Enviar WhatsApp de prueba</a>
                </div>
            </div>

        <?php else: ?>
            <div class="card">
                <h2>
                    <?= match($step) {
                        'key' => '🔑 Generar App Key',
                        'migrate' => '📦 Migraciones',
                        'seed' => '🌱 Seeders',
                        'storage' => '🔗 Storage Link',
                        'cache' => '⚡ Cache',
                        'clear' => '🧹 Limpiar cache',
                        'email-test' => '📧 Email de prueba',
                        'whatsapp-test' => '📲 WhatsApp de prueba',
                        default => '⚙️ Resultado'
                    } ?>
                </h2>

                <div class="output"><?php
                    try {
                        if ($step === 'key') {
                            Artisan::call('key:generate', ['--force' => true]);
                            echo Artisan::output();
                        }
                        if ($step === 'migrate') {
                            Artisan::call('migrate', ['--force' => true]);
                            echo Artisan::output();
                        }
                        if ($step === 'seed') {
                            Artisan::call('db:seed', ['--force' => true]);
                            echo Artisan::output();
                        }
                        if ($step === 'storage') {
                            Artisan::call('storage:link');
                            echo Artisan::output();
                        }
                        if ($step === 'cache') {
                            Artisan::call('config:cache');
                            echo "✅ Config cached\n";
                            Artisan::call('route:cache');
                            echo "✅ Routes cached\n";
                            try {
                                Artisan::call('view:cache');
                                echo "✅ Views cached\n";
                            } catch (Throwable $e) {
                                echo "⚠️ View cache skipped (normal with Filament custom views)\n";
                            }
                        }
                        if ($step === 'clear') {
                            Artisan::call('config:clear');
                            echo "✅ Config cache cleared\n";
                            Artisan::call('route:clear');
                            echo "✅ Route cache cleared\n";
                            Artisan::call('view:clear');
                            echo "✅ View cache cleared\n";
                            Artisan::call('cache:clear');
                            echo "✅ Application cache cleared\n";
                            Artisan::call('queue:restart');
                            echo "✅ Queue workers restarted\n";
                        }
                        if ($step === 'email-test') {
                            $adminRaw = config('mail.admin_email');
                            $recipients = array_values(array_filter(array_map('trim', explode(',', (string) $adminRaw))));
                            $mailer = config('mail.default');
                            $host = config("mail.mailers.{$mailer}.host");
                            $port = config("mail.mailers.{$mailer}.port");
                            $scheme = config("mail.mailers.{$mailer}.scheme") ?: 'auto';
                            $username = config("mail.mailers.{$mailer}.username");
                            $from = config('mail.from.address');

                            echo "📋 Configuración actual:\n";
                            echo "   Mailer:   {$mailer}\n";
                            echo "   Host:     " . ($host ?: '(vacío)') . "\n";
                            echo "   Puerto:   " . ($port ?: '(vacío)') . "\n";
                            echo "   Scheme:   {$scheme}\n";
                            echo "   Usuario:  " . ($username ?: '(vacío)') . "\n";
                            echo "   Desde:    " . ($from ?: '(vacío)') . "\n";
                            echo "   Admins:   " . (empty($recipients) ? '(no configurado)' : implode(', ', $recipients)) . "\n\n";

                            if (empty($recipients)) {
                                echo "❌ MAIL_ADMIN_EMAIL no está configurado en .env\n";
                                echo "   Agrega esta línea: MAIL_ADMIN_EMAIL=tu-correo@ejemplo.com\n";
                                echo "   También puedes usar varios separados por coma:\n";
                                echo "   MAIL_ADMIN_EMAIL=\"correo1@x.com, correo2@x.com\"\n";
                            } else {
                                echo "🚀 Enviando email de prueba a " . count($recipients) . " destinatario(s)...\n\n";

                                Illuminate\Support\Facades\Mail::raw(
                                    "Este es un correo de prueba enviado desde setup.php de Citora.\n\n"
                                    ."Si recibes este mensaje, la configuración SMTP está funcionando correctamente.\n\n"
                                    ."Destinatarios: ".implode(', ', $recipients)."\n"
                                    ."Timestamp: ".now()->toDateTimeString()."\n"
                                    ."Host: {$host}:{$port} ({$scheme})",
                                    function ($message) use ($recipients) {
                                        $message->to($recipients)->subject('✅ Prueba SMTP - Citora');
                                    }
                                );

                                echo "✅ Email enviado correctamente a:\n";
                                foreach ($recipients as $r) {
                                    echo "   • {$r}\n";
                                }
                                echo "\n📬 Revisa las bandejas (y carpetas de spam)\n";
                            }
                        }
                        if ($step === 'whatsapp-test') {
                            $testPhone = $_GET['to'] ?? '';
                            $sid = config('services.twilio.sid');
                            $token = config('services.twilio.auth_token');
                            $channel = config('services.twilio.channel');
                            $smsFrom = config('services.twilio.sms_from');
                            $whatsFrom = config('services.twilio.whatsapp_from');
                            $templates = config('services.twilio.templates', []);
                            $tpl = $templates['appointment.confirmed.customer'] ?? null;

                            echo "📋 Configuración actual:\n";
                            echo "   SID:             " . ($sid ? substr($sid, 0, 8) . '…' : '(vacío)') . "\n";
                            echo "   Token:           " . ($token ? '(configurado)' : '(vacío)') . "\n";
                            echo "   Channel:         " . ($channel ?: '(no configurado)') . "\n";
                            echo "   SMS From:        " . ($smsFrom ?: '(vacío)') . "\n";
                            echo "   WhatsApp From:   " . ($whatsFrom ?: '(vacío)') . "\n";
                            echo "   Template conf:   " . ($tpl ?: '(vacío — enviará texto libre)') . "\n\n";

                            if (! $testPhone) {
                                echo "⚠️ Para enviar, agrega ?to=TELEFONO a la URL\n";
                                echo "   Ejemplo: ?key={$secret}&step=whatsapp-test&to=3143693735\n";
                                echo "   (Formato: 10 dígitos colombianos, sin +57)\n";
                            } else {
                                echo "🚀 Enviando mensaje de prueba a {$testPhone}...\n\n";

                                $channelService = app(\App\Contracts\MessagingChannel::class);
                                $channelClass = get_class($channelService);
                                echo "   Clase activa: {$channelClass}\n\n";

                                $result = $channelService->sendTemplate(
                                    $testPhone,
                                    'appointment.confirmed.customer',
                                    [
                                        1 => 'Prueba',
                                        2 => 'Citora Test',
                                        3 => 'Servicio demo',
                                        4 => 'Profesional demo',
                                        5 => 'hoy',
                                        6 => 'ahora',
                                        7 => '$0',
                                        8 => rtrim(config('app.url'), '/').'/',
                                    ],
                                    'Prueba de Citora: este mensaje llegó correctamente vía '.$channel.'.'
                                );

                                if ($result) {
                                    echo "✅ Mensaje despachado correctamente al proveedor\n";
                                    echo "📱 Revisa tu WhatsApp / SMS al número {$testPhone}\n";
                                    echo "\n⚠️ Si NO llega, revisa storage/logs/laravel.log y en Twilio Console → Monitor → Logs\n";
                                } else {
                                    echo "❌ Falló el despacho\n";
                                    echo "   Revisa storage/logs/laravel.log para ver el error exacto.\n";
                                }
                            }
                        }
                        echo "\n✅ Completado";
                    } catch (Throwable $e) {
                        echo "❌ Error: " . $e->getMessage() . "\n";
                        echo "📁 " . $e->getFile() . ":" . $e->getLine();
                    }
                ?></div>

                <a href="?key=<?= $secret ?>" class="btn btn-outline" style="margin-top: 12px">← Volver al panel</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        Citora Setup · Elimina este archivo después de usar
    </div>
</body>
</html>
