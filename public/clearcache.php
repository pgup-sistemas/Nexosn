<?php
if (($_GET['s'] ?? '') !== 'nx2026') { http_response_code(403); die('403'); }

define('ROOT', dirname(__DIR__));
require ROOT . '/vendor/autoload.php';
$app = require ROOT . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain; charset=utf-8');
echo "=== NEXOSN Cache Clear ===\n\n";

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ opcache_reset()\n";
} else {
    echo "⚠️  opcache não disponível\n";
}

use Illuminate\Support\Facades\Artisan;

// Migrations primeiro (antes de limpar config)
try {
    Artisan::call('migrate', ['--force' => true]);
    echo "✅ migrate\n";
    echo Artisan::output();
} catch (\Throwable $e) {
    echo "⚠️  migrate: " . $e->getMessage() . "\n";
}

foreach (['view:clear', 'cache:clear', 'config:clear', 'route:clear', 'event:clear'] as $cmd) {
    Artisan::call($cmd);
    echo "✅ {$cmd}\n";
}

Artisan::call('optimize');
echo "✅ optimize\n";

// Mostra as linhas de ERRO do log (não a stack trace)
echo "\n--- ERROS do log (últimas ocorrências) ---\n";
$log = ROOT . '/storage/logs/laravel.log';
if (file_exists($log)) {
    $lines = file($log);
    $total = count($lines);

    // Encontra todos os blocos de erro (linhas que começam com [YYYY-MM-DD)
    $errorBlocks = [];
    $currentBlock = [];
    $isError = false;

    for ($i = 0; $i < $total; $i++) {
        $line = $lines[$i];
        // Nova entrada de log
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2})/', $line)) {
            if ($isError && !empty($currentBlock)) {
                $errorBlocks[] = $currentBlock;
            }
            $isError = str_contains($line, '.ERROR') || str_contains($line, '.CRITICAL') || str_contains($line, 'ErrorException') || str_contains($line, 'Error:');
            $currentBlock = [$line];
        } else {
            $currentBlock[] = $line;
        }
    }
    if ($isError && !empty($currentBlock)) {
        $errorBlocks[] = $currentBlock;
    }

    // Mostra os últimos 3 blocos de erro
    $last3 = array_slice($errorBlocks, -3);
    foreach ($last3 as $block) {
        // Mostra só as primeiras 15 linhas do bloco (mensagem + início da stack)
        echo implode('', array_slice($block, 0, 15));
        echo "\n...\n\n";
    }

    if (empty($errorBlocks)) {
        echo "(nenhum erro encontrado no log)\n";
    }
} else {
    echo "(arquivo de log não existe)\n";
}

echo "\nPronto. Apague este arquivo após uso.\n";
