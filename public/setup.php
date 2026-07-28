<?php
/**
 * Setup pós-deploy — bootstrap direto do Laravel (sem shell_exec)
 * APAGAR APÓS USO: public/setup.php
 */

define('SENHA', 'NexosnSetup2026');
define('ROOT',  dirname(__DIR__));

if (($_GET['senha'] ?? '') !== SENHA) {
    http_response_code(403);
    die('Acesso negado.');
}

header('Content-Type: text/html; charset=utf-8');
@ini_set('max_execution_time', 300);

echo '<pre style="font-family:monospace;background:#111;color:#0f0;padding:20px;font-size:13px;">';
echo "=== NEXOSN — Setup pós-deploy ===\n\n";

// ── 1. Copiar .env ───────────────────────────────────────────────────────────
$envProd = ROOT . '/.env.production';
$env     = ROOT . '/.env';

if (file_exists($envProd)) {
    copy($envProd, $env);
    echo "✅ .env.production → .env copiado\n\n";
} else {
    echo "⚠️  .env.production não encontrado\n\n";
}

// ── 2. Bootstrap Laravel ─────────────────────────────────────────────────────
echo "Inicializando Laravel...\n";

require ROOT . '/vendor/autoload.php';

$app = require ROOT . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "✅ Laravel inicializado\n\n";

// ── 3. Rodar comandos artisan ─────────────────────────────────────────────────
function artisan(string $cmd, array $args = []): void
{
    echo '<span style="color:#ff0">▶ php artisan ' . $cmd . '</span>' . "\n";

    $output = new Symfony\Component\Console\Output\BufferedOutput();

    try {
        $code = Artisan::call($cmd, $args, $output);
        $text = trim($output->fetch());
        if ($text) echo htmlspecialchars($text) . "\n";
        echo ($code === 0
            ? '<span style="color:#0f0">✅ OK</span>'
            : '<span style="color:#f00">❌ código ' . $code . '</span>') . "\n\n";
    } catch (Throwable $e) {
        echo '<span style="color:#f00">❌ ' . htmlspecialchars($e->getMessage()) . '</span>' . "\n\n";
    }
}

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// ── Teste de conexão com o banco ─────────────────────────────────────────────
echo '<span style="color:#ff0">▶ Testando conexão com o banco...</span>' . "\n";
try {
    DB::select('SELECT 1');
    echo "✅ Banco conectado com sucesso\n\n";
} catch (Throwable $e) {
    echo '<span style="color:#f00">❌ Falha na conexão: ' . htmlspecialchars($e->getMessage()) . '</span>' . "\n\n";
}

// ── Ordem: migrate ANTES de qualquer config:clear ────────────────────────────
artisan('migrate', ['--force' => true]);

// storage:link — resolvido via rewrite no public/.htaccess
echo '<span style="color:#ff0">▶ storage:link</span>' . "\n";
echo "  ✅ Resolvido via RewriteRule no public/.htaccess (sem symlink)\n\n";
flush();

artisan('config:clear');
artisan('key:generate', ['--force' => true]);
artisan('optimize');
artisan('view:cache');
artisan('queue:restart');

echo '<span style="color:#ff0;font-size:15px">✅ Tudo pronto! APAGUE este arquivo: public/setup.php</span>' . "\n";
echo '</pre>';
