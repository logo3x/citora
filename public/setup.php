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
