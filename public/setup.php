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
                <h2>📦 Setup base</h2>
                <p style="font-size:12px;color:#6b7280;margin-bottom:10px">Ejecuta esto al desplegar el proyecto por primera vez o cuando subas migraciones nuevas.</p>
                <div class="actions">
                    <a href="?key=<?= $secret ?>&step=key" class="btn btn-outline">🔑 Generar App Key</a>
                    <a href="?key=<?= $secret ?>&step=migrate" class="btn btn-primary">📦 Ejecutar migraciones</a>
                    <a href="?key=<?= $secret ?>&step=seed" class="btn btn-outline">🌱 Ejecutar seeders</a>
                    <a href="?key=<?= $secret ?>&step=storage" class="btn btn-outline">🔗 Crear Storage Link</a>
                </div>
            </div>

            <div class="card">
                <h2>⚡ Cache</h2>
                <p style="font-size:12px;color:#6b7280;margin-bottom:10px">Después de cambiar <code>.env</code>, rutas o vistas, limpia la caché para que tome efecto.</p>
                <div class="actions">
                    <a href="?key=<?= $secret ?>&step=clear" class="btn btn-primary">🧹 Limpiar toda la cache</a>
                    <a href="?key=<?= $secret ?>&step=cache" class="btn btn-outline">⚡ Cachear config y rutas (producción)</a>
                </div>
            </div>

            <div class="card">
                <h2>🔔 Notificaciones push (web)</h2>
                <p style="font-size:12px;color:#6b7280;margin-bottom:10px">Configuración inicial de Web Push: genera las claves VAPID y pégalas en <code>.env</code>.</p>
                <div class="actions">
                    <a href="?key=<?= $secret ?>&step=push-vapid" class="btn btn-primary">🔐 Generar claves VAPID</a>
                    <a href="?key=<?= $secret ?>&step=push-status" class="btn btn-outline">📊 Estado de suscripciones push</a>
                </div>
            </div>

            <div class="card">
                <h2>🔍 Diagnóstico</h2>
                <p style="font-size:12px;color:#6b7280;margin-bottom:10px">Herramientas para investigar por qué algo no llega a destino.</p>
                <div class="actions">
                    <a href="?key=<?= $secret ?>&step=reminders-diagnose" class="btn btn-outline">🔍 Diagnosticar recordatorios</a>
                    <a href="?key=<?= $secret ?>&step=reminders-run" class="btn btn-outline">▶️ Ejecutar recordatorios ahora</a>
                    <a href="?key=<?= $secret ?>&step=email-test" class="btn btn-outline">📧 Enviar email de prueba</a>
                    <a href="?key=<?= $secret ?>&step=whatsapp-test" class="btn btn-outline">📲 Enviar WhatsApp de prueba</a>
                </div>
            </div>

            <div class="card">
                <h2>👑 Administración</h2>
                <p style="font-size:12px;color:#6b7280;margin-bottom:10px">Acciones puntuales sobre usuarios y secretos.</p>
                <div class="actions">
                    <a href="?key=<?= $secret ?>&step=promote-admin" class="btn btn-outline">👑 Promover webcitora a super_admin</a>
                    <a href="?key=<?= $secret ?>&step=gen-secret" class="btn btn-outline">🔐 Generar secret aleatorio</a>
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
                        'gen-secret' => '🔐 Secret aleatorio',
                        'promote-admin' => '👑 Promover a super_admin',
                        'reminders-diagnose' => '🔍 Diagnóstico de recordatorios',
                        'reminders-run' => '▶️ Ejecución manual de recordatorios',
                        'push-vapid' => '🔐 Generar claves VAPID',
                        'push-status' => '📊 Estado de suscripciones push',
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
                        if ($step === 'promote-admin') {
                            $targetEmail = 'webcitora@gmail.com';

                            echo "🔍 Buscando usuario {$targetEmail}...\n\n";

                            $user = App\Models\User::where('email', $targetEmail)->first();

                            if (! $user) {
                                echo "❌ El usuario {$targetEmail} NO existe todavía.\n";
                                echo "   Solución: inicia sesión en https://citora.com.co usando ese correo (vía Google).\n";
                                echo "   Luego vuelve a dar clic en este botón.\n";
                            } else {
                                echo "✅ Usuario encontrado: {$user->name} (ID: {$user->id})\n\n";

                                // Asegurar que el rol super_admin exista
                                $role = Spatie\Permission\Models\Role::where('name', 'super_admin')->where('guard_name', 'web')->first();

                                if (! $role) {
                                    echo "⚙️  El rol super_admin no existe. Ejecutando ShieldSeeder...\n";
                                    Artisan::call('db:seed', ['--class' => 'ShieldSeeder', '--force' => true]);
                                    echo Artisan::output()."\n";
                                    $role = Spatie\Permission\Models\Role::where('name', 'super_admin')->where('guard_name', 'web')->first();
                                }

                                if (! $role) {
                                    echo "❌ No se pudo crear/encontrar el rol super_admin. Abortando.\n";
                                } elseif ($user->hasRole('super_admin')) {
                                    echo "ℹ️  {$user->email} YA tiene el rol super_admin. Nada que hacer.\n";
                                } else {
                                    $user->assignRole('super_admin');
                                    echo "🎉 ¡Listo! {$user->email} es ahora super_admin.\n";
                                    echo "   Ya tiene acceso al módulo completo de Administración.\n";
                                    echo "   Roles actuales: ".implode(', ', $user->fresh()->getRoleNames()->all())."\n";
                                }
                            }
                        }
                        if ($step === 'reminders-diagnose') {
                            echo "🔍 DIAGNÓSTICO DE RECORDATORIOS\n";
                            echo str_repeat('=', 60)."\n\n";

                            // 1. Verificar columnas en la BD
                            echo "1️⃣  Columnas anti-duplicado en appointments:\n";
                            $hasCol24 = \Illuminate\Support\Facades\Schema::hasColumn('appointments', 'reminder_24h_sent_at');
                            $hasCol1 = \Illuminate\Support\Facades\Schema::hasColumn('appointments', 'reminder_1h_sent_at');
                            echo "   • reminder_24h_sent_at: ".($hasCol24 ? '✅' : '❌ FALTA')."\n";
                            echo "   • reminder_1h_sent_at: ".($hasCol1 ? '✅' : '❌ FALTA')."\n\n";

                            // 2. Citas próximas en las próximas 25 horas
                            echo "2️⃣  Citas pendientes/confirmadas en las próximas 25h:\n";
                            $upcoming = App\Models\Appointment::with(['service', 'employee', 'customer', 'business'])
                                ->whereIn('status', [App\Enums\AppointmentStatus::Pending, App\Enums\AppointmentStatus::Confirmed])
                                ->whereBetween('starts_at', [now(), now()->addHours(25)])
                                ->orderBy('starts_at')
                                ->limit(20)
                                ->get();

                            if ($upcoming->isEmpty()) {
                                echo "   ⚠️  Ninguna. Sin citas no se mandan recordatorios.\n";
                            } else {
                                foreach ($upcoming as $a) {
                                    $hours = round(now()->diffInMinutes($a->starts_at, false) / 60, 1);
                                    $r24 = $a->reminder_24h_sent_at ? '✅ '.$a->reminder_24h_sent_at->format('d/m H:i') : '❌';
                                    $r1 = $a->reminder_1h_sent_at ? '✅ '.$a->reminder_1h_sent_at->format('d/m H:i') : '❌';
                                    echo "   #{$a->id} | {$a->starts_at->format('d/m H:i')} (en {$hours}h) | ".($a->customer->name ?? '?')." → ".($a->employee->name ?? '?')."\n";
                                    echo "      24h sent: {$r24}  |  1h sent: {$r1}\n";
                                }
                            }
                            echo "\n";

                            // 3. Configuración Twilio
                            echo "3️⃣  Configuración mensajería:\n";
                            $sid = config('services.twilio.sid');
                            $token = config('services.twilio.auth_token');
                            $channel = config('services.twilio.channel');
                            $from = config('services.twilio.whatsapp_from');
                            $tplCustomer = config('services.twilio.templates.appointment.reminder.customer');
                            $tplInternal = config('services.twilio.templates.appointment.reminder.internal');
                            echo "   • TWILIO_SID: ".($sid ? '✅ '.substr($sid, 0, 10).'…' : '❌ FALTA')."\n";
                            echo "   • TWILIO_AUTH_TOKEN: ".($token ? '✅ presente' : '❌ FALTA')."\n";
                            echo "   • TWILIO_CHANNEL: {$channel}\n";
                            echo "   • TWILIO_WHATSAPP_FROM: ".($from ?: '❌ FALTA')."\n";
                            echo "   • Template REMINDER_CUSTOMER: ".($tplCustomer ?: '❌ FALTA')."\n";
                            echo "   • Template REMINDER_INTERNAL: ".($tplInternal ?: '❌ FALTA')."\n\n";

                            // 4. Últimas líneas de log filtradas
                            echo "4️⃣  Últimas líneas del log relacionadas con reminders/whatsapp:\n";
                            $logFile = storage_path('logs/laravel.log');
                            if (file_exists($logFile)) {
                                $lines = [];
                                $fp = fopen($logFile, 'r');
                                fseek($fp, max(0, filesize($logFile) - 50000));
                                while (! feof($fp)) {
                                    $line = fgets($fp);
                                    if ($line && preg_match('/reminder|recordatorio|whatsapp|twilio|cron/i', $line)) {
                                        $lines[] = trim($line);
                                    }
                                }
                                fclose($fp);
                                if (empty($lines)) {
                                    echo "   ⚠️  Sin entradas. Probablemente el cron NO ha corrido aún.\n";
                                } else {
                                    foreach (array_slice($lines, -15) as $l) {
                                        echo '   '.substr($l, 0, 200)."\n";
                                    }
                                }
                            } else {
                                echo "   ❌ {$logFile} no existe\n";
                            }
                            echo "\n";

                            // 5. Cron-reminders log (si lo configuraste con > log)
                            echo "5️⃣  Log específico del cron (cron-reminders.log):\n";
                            $cronLog = storage_path('logs/cron-reminders.log');
                            if (file_exists($cronLog)) {
                                $content = file_get_contents($cronLog);
                                $tail = array_slice(explode("\n", $content), -20);
                                echo "   ".implode("\n   ", $tail)."\n";
                            } else {
                                echo "   ℹ️  No existe (opcional). Configúralo en cron de cPanel con: > storage/logs/cron-reminders.log 2>&1\n";
                            }
                            echo "\n";

                            echo str_repeat('=', 60)."\n";
                            echo "✅ Diagnóstico completo. Si ves citas con 24h sent: ❌\n";
                            echo "   y el horario está dentro de la ventana correcta, prueba el botón\n";
                            echo "   ▶️ Ejecutar recordatorios ahora para forzar el envío.\n";
                        }
                        if ($step === 'reminders-run') {
                            echo "▶️  EJECUTANDO COMANDO send-appointment-reminders MANUALMENTE\n";
                            echo str_repeat('=', 60)."\n\n";

                            Artisan::call('appointments:send-reminders', [], new Symfony\Component\Console\Output\BufferedOutput);
                            $output = Artisan::output();

                            if (trim($output) === '') {
                                echo "✅ Comando ejecutado sin output. Revisa storage/logs/laravel.log para detalles.\n";
                            } else {
                                echo $output;
                            }
                            echo "\n".str_repeat('=', 60)."\n";
                            echo "✅ Hecho. Vuelve a 🔍 Diagnosticar recordatorios para ver si las citas quedan marcadas como enviadas.\n";
                        }
                        if ($step === 'push-vapid') {
                            echo "🔐 GENERANDO CLAVES VAPID PARA WEB PUSH\n";
                            echo str_repeat('=', 60)."\n\n";

                            $existingPublic = config('services.webpush.public_key');
                            $existingPrivate = config('services.webpush.private_key');

                            if ($existingPublic && $existingPrivate) {
                                echo "⚠️  Ya existen claves VAPID en tu configuración:\n\n";
                                echo "   VAPID_PUBLIC_KEY  = ".substr($existingPublic, 0, 20)."…\n";
                                echo "   VAPID_PRIVATE_KEY = (oculta — ya configurada)\n\n";
                                echo "ℹ️  Si las regeneras, TODAS las suscripciones push existentes\n";
                                echo "   dejarán de funcionar y los usuarios deberán reactivarlas.\n\n";
                                echo "   Si necesitas regenerar a la fuerza, borra las claves del .env\n";
                                echo "   primero, limpia caché y vuelve a entrar aquí.\n";
                            } elseif (! class_exists(\Minishlink\WebPush\VAPID::class)) {
                                echo "❌ La librería minishlink/web-push NO está instalada.\n";
                                echo "   El paquete debería venir con el último pull de git.\n";
                                echo "   Si no lo tiene, ejecuta en cPanel Terminal:\n";
                                echo "      cd ~/public_html && composer install --no-dev --optimize-autoloader\n";
                            } else {
                                $keys = \Minishlink\WebPush\VAPID::createVapidKeys();

                                echo "✅ Claves generadas correctamente.\n\n";
                                echo "📋 Copia estas 3 líneas y pégalas al final de tu .env:\n\n";
                                echo "----------------------------------------------------------\n";
                                echo "VAPID_PUBLIC_KEY={$keys['publicKey']}\n";
                                echo "VAPID_PRIVATE_KEY={$keys['privateKey']}\n";
                                echo "VAPID_SUBJECT=mailto:contacto@citora.com.co\n";
                                echo "----------------------------------------------------------\n\n";
                                echo "📌 Después de pegarlas:\n";
                                echo "   1. Guarda el .env\n";
                                echo "   2. Vuelve aquí y haz click en 🧹 Limpiar toda la cache\n";
                                echo "   3. Entra a /admin/notificaciones-push y prueba activar\n\n";
                                echo "⚠️  IMPORTANTE: NUNCA compartas la PRIVATE KEY ni la subas a git.\n";
                            }
                        }
                        if ($step === 'push-status') {
                            echo "📊 ESTADO DE SUSCRIPCIONES WEB PUSH\n";
                            echo str_repeat('=', 60)."\n\n";

                            // 1. Tabla existe?
                            echo "1️⃣  Tabla push_subscriptions:\n";
                            $tableExists = \Illuminate\Support\Facades\Schema::hasTable('push_subscriptions');
                            echo "   • Existe: ".($tableExists ? '✅' : '❌ FALTA — corre 📦 Ejecutar migraciones')."\n\n";

                            if (! $tableExists) {
                                echo "Ejecuta primero las migraciones para que esta sección funcione.\n";
                            } else {
                                // 2. VAPID configuradas?
                                echo "2️⃣  Configuración VAPID:\n";
                                $publicKey = config('services.webpush.public_key');
                                $privateKey = config('services.webpush.private_key');
                                $subject = config('services.webpush.subject');
                                echo "   • VAPID_PUBLIC_KEY:  ".($publicKey ? '✅ '.substr($publicKey, 0, 20).'…' : '❌ FALTA — usa botón 🔐 Generar claves VAPID')."\n";
                                echo "   • VAPID_PRIVATE_KEY: ".($privateKey ? '✅ presente' : '❌ FALTA')."\n";
                                echo "   • VAPID_SUBJECT:     ".($subject ?: '❌ FALTA')."\n\n";

                                // 3. Suscripciones registradas
                                echo "3️⃣  Suscripciones activas:\n";
                                $total = \App\Models\PushSubscription::count();
                                echo "   • Total: {$total}\n\n";

                                if ($total > 0) {
                                    $subs = \App\Models\PushSubscription::with('user')->latest()->limit(10)->get();
                                    echo "   Últimas 10:\n";
                                    foreach ($subs as $sub) {
                                        $user = $sub->user?->email ?? '(usuario eliminado)';
                                        $ua = $sub->user_agent ? substr($sub->user_agent, 0, 60) : '';
                                        $last = $sub->last_used_at?->diffForHumans() ?? 'nunca';
                                        echo "   #{$sub->id} · {$user} · usado: {$last}\n";
                                        if ($ua) {
                                            echo "          UA: {$ua}\n";
                                        }
                                    }
                                }
                            }
                        }
                        if ($step === 'gen-secret') {
                            $hex64 = bin2hex(random_bytes(32));      // 64 chars hex
                            $b64 = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
                            $alpha = Illuminate\Support\Str::random(64);

                            echo "🔐 Secrets aleatorios generados (usa cualquiera):\n\n";
                            echo "• HEX (64 chars):\n  {$hex64}\n\n";
                            echo "• Base64-url (43 chars):\n  {$b64}\n\n";
                            echo "• Alfanumérico Laravel (64 chars):\n  {$alpha}\n\n";
                            echo "📋 Pasos:\n";
                            echo "   1. Copia uno de los valores de arriba\n";
                            echo "   2. Pega en tu .env como: CRON_SECRET=<el_valor>\n";
                            echo "   3. Guarda y ejecuta '🧹 Limpiar toda la cache'\n";
                            echo "   4. Prueba: /cron/reminders?key=<el_valor>\n";
                            echo "   5. Configura el cron en cron-job.org con esa URL\n\n";
                            echo "⚠️ NO compartas estos valores en chats ni capturas.\n";
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
