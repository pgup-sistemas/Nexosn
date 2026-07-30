<?php
/**
 * Script de configuração Efi Bank — NEXOSN
 * Acesse: https://nexosn.pageup.net.br/efi-setup.php?s=nx2026
 * REMOVA após configurar!
 */

define('ACCESS_KEY', 'nx2026');
if (($_GET['s'] ?? '') !== ACCESS_KEY) {
    http_response_code(403);
    die('Acesso negado.');
}

// Boot Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use App\Models\AppSetting;
use App\Services\EfiBankService;
use Illuminate\Support\Facades\Config;

$action  = $_GET['action'] ?? 'status';
$results = [];
$errors  = [];

// ── Status atual ───────────────────────────────────────────────────────────
$status = [
    'APP_URL'               => config('app.url'),
    'EFI_SANDBOX'           => env('EFI_SANDBOX', 'não definido'),
    'EFI_CLIENT_ID'         => env('EFI_CLIENT_ID') ? substr(env('EFI_CLIENT_ID'), 0, 20) . '...' : '❌ NÃO DEFINIDO',
    'EFI_CLIENT_SECRET'     => env('EFI_CLIENT_SECRET') ? '✓ definido' : '❌ NÃO DEFINIDO',
    'EFI_CERTIFICATE_PATH'  => env('EFI_CERTIFICATE_PATH', 'não definido'),
    'cert_exists'           => env('EFI_CERTIFICATE_PATH') ? file_exists(env('EFI_CERTIFICATE_PATH')) : false,
    'efi_sandbox_db'        => AppSetting::get('efi_sandbox', '(não salvo no banco)'),
    'efi_plan_id_monthly'   => AppSetting::get('efi_plan_id_monthly', '❌ vazio'),
    'efi_plan_id_annual'    => AppSetting::get('efi_plan_id_annual', '❌ vazio'),
    'plan_price_monthly'    => AppSetting::get('plan_price_monthly', '19.90 (padrão)'),
    'app_url_db'            => AppSetting::get('app_url', '(não salvo — usando config)'),
];

// ── Ação: criar planos ──────────────────────────────────────────────────────
if ($action === 'criar_planos') {
    try {
        $efi = app(EfiBankService::class);

        $monthly = $efi->createPlan('NEXOSN Pro — Mensal', 1);
        $annual  = $efi->createPlan('NEXOSN Pro — Anual', 12);

        $monthlyId = $monthly['data']['plan_id'] ?? null;
        $annualId  = $annual['data']['plan_id'] ?? null;

        if ($monthlyId) {
            AppSetting::set('efi_plan_id_monthly', (string) $monthlyId);
            $results[] = "✓ Plano mensal criado: ID = {$monthlyId}";
        } else {
            $errors[] = '✗ Plano mensal: ID não retornado. Resposta: ' . json_encode($monthly);
        }

        if ($annualId) {
            AppSetting::set('efi_plan_id_annual', (string) $annualId);
            $results[] = "✓ Plano anual criado: ID = {$annualId}";
        } else {
            $errors[] = '✗ Plano anual: ID não retornado. Resposta: ' . json_encode($annual);
        }

        if ($monthlyId && $annualId) {
            $results[] = "✓ IDs salvos no banco. Sistema pronto para cobranças!";
        }
    } catch (Throwable $e) {
        $errors[] = '✗ Erro: ' . $e->getMessage();
    }

    // Atualiza status após ação
    $status['efi_plan_id_monthly'] = AppSetting::get('efi_plan_id_monthly', '❌ vazio');
    $status['efi_plan_id_annual']  = AppSetting::get('efi_plan_id_annual', '❌ vazio');
}

// ── Ação: salvar app_url ───────────────────────────────────────────────────
if ($action === 'salvar_url' && isset($_POST['app_url'])) {
    $url = rtrim(trim($_POST['app_url']), '/');
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        AppSetting::set('app_url', $url);
        $results[] = "✓ URL do sistema salva: {$url}";
        $status['app_url_db'] = $url;
    } else {
        $errors[] = "✗ URL inválida: {$url}";
    }
}

// ── Ação: salvar sandbox ───────────────────────────────────────────────────
if ($action === 'salvar_sandbox' && isset($_POST['sandbox'])) {
    $sandbox = $_POST['sandbox'] === '1';
    AppSetting::set('efi_sandbox', $sandbox);
    $results[] = $sandbox ? '✓ Modo sandbox ATIVADO (homologação)' : '✓ Modo produção ATIVADO (sandbox desligado)';
    $status['efi_sandbox_db'] = $sandbox;
}

$plansOk = $status['efi_plan_id_monthly'] !== '❌ vazio' && $status['efi_plan_id_annual'] !== '❌ vazio';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NEXOSN · Setup Efi Bank</title>
<style>
body{font-family:system-ui,sans-serif;background:#0f1117;color:#e2e8f0;margin:0;padding:20px;}
.wrap{max-width:720px;margin:0 auto;}
h1{color:#C9A96E;font-size:22px;margin-bottom:4px;}
.sub{color:#6b7280;font-size:13px;margin-bottom:28px;}
.card{background:#1a1a2e;border:1px solid #2d2d3d;border-radius:12px;padding:20px;margin-bottom:18px;}
.card h2{font-size:14px;font-weight:700;color:#a0a0b8;margin:0 0 14px;text-transform:uppercase;letter-spacing:.05em;}
.row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid #2d2d3d;font-size:13px;}
.row:last-child{border:none;}
.key{color:#8a8aa0;}
.val{color:#e2e8f0;font-family:monospace;font-size:12px;max-width:400px;word-break:break-all;text-align:right;}
.val.ok{color:#4ade80;}
.val.bad{color:#f87171;}
.val.warn{color:#fbbf24;}
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none;}
.btn-gold{background:linear-gradient(100deg,#7A5520,#C9A96E);color:#1a0e00;}
.btn-red{background:#dc2626;color:#fff;}
.btn-gray{background:#2d2d3d;color:#a0a0b8;}
form{display:inline;}
.result{padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:8px;}
.result.ok{background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.3);color:#4ade80;}
.result.err{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.3);color:#f87171;}
input[type=text],input[type=url]{background:#0d0d1a;border:1px solid #3d3d50;border-radius:8px;padding:9px 12px;color:#e2e8f0;font-size:13px;width:100%;box-sizing:border-box;margin-bottom:10px;}
.step{display:flex;align-items:flex-start;gap:10px;margin-bottom:12px;font-size:13px;}
.num{background:#C9A96E;color:#1a0e00;border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px;flex-shrink:0;margin-top:2px;}
</style>
</head>
<body>
<div class="wrap">
    <h1>NEXOSN · Setup Efi Bank</h1>
    <p class="sub">Configure a integração de pagamentos. <strong style="color:#f87171;">Remova este arquivo após concluir.</strong></p>

    <?php foreach ($results as $r): ?>
    <div class="result ok"><?= htmlspecialchars($r) ?></div>
    <?php endforeach; ?>
    <?php foreach ($errors as $e): ?>
    <div class="result err"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <!-- Status -->
    <div class="card">
        <h2>Status das Configurações</h2>
        <?php foreach ($status as $k => $v): ?>
        <?php
            $isOk   = !in_array($v, ['❌ NÃO DEFINIDO', '❌ vazio', false, '(não salvo no banco)'], true)
                      && $v !== false && $v !== null;
            $isCert = $k === 'cert_exists';
        ?>
        <?php if ($isCert): ?>
        <div class="row">
            <span class="key">Certificado (.p12) existe?</span>
            <span class="val <?= $v ? 'ok' : 'bad' ?>"><?= $v ? '✓ Encontrado' : '✗ NÃO ENCONTRADO no path acima' ?></span>
        </div>
        <?php elseif ($k !== 'cert_exists'): ?>
        <div class="row">
            <span class="key"><?= htmlspecialchars($k) ?></span>
            <span class="val <?= $isOk ? 'ok' : 'bad' ?>"><?= htmlspecialchars((string)$v) ?></span>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Passo 1: URL -->
    <div class="card">
        <h2>Passo 1 — URL do Sistema (webhook)</h2>
        <p style="font-size:13px;color:#8a8aa0;margin-bottom:14px;">
            A URL é usada para gerar o <code>notification_url</code> que a Efi Bank usa para notificar pagamentos.
            Deve ser a URL pública do servidor (HTTPS).
        </p>
        <form method="POST" action="?s=<?= ACCESS_KEY ?>&action=salvar_url">
            <input type="url" name="app_url" placeholder="https://nexosn.pageup.net.br" value="<?= htmlspecialchars(AppSetting::get('app_url', config('app.url'))) ?>">
            <button type="submit" class="btn btn-gold">Salvar URL</button>
        </form>
    </div>

    <!-- Passo 2: Sandbox -->
    <div class="card">
        <h2>Passo 2 — Ambiente (Sandbox / Produção)</h2>
        <p style="font-size:13px;color:#8a8aa0;margin-bottom:14px;">
            Em <strong>homologação</strong>, use sandbox com o certificado <code>homologacao-*.p12</code>.
            Em <strong>produção</strong>, desative o sandbox e use o certificado <code>producao-*.p12</code>.
            Configure também <code>EFI_CERTIFICATE_PATH</code> e as credenciais corretas no <code>.env</code> do servidor.
        </p>
        <form method="POST" action="?s=<?= ACCESS_KEY ?>&action=salvar_sandbox" style="display:flex;gap:8px;">
            <input type="hidden" name="sandbox" value="1">
            <button type="submit" class="btn btn-gray">Modo Homologação (sandbox=true)</button>
        </form>
        &nbsp;
        <form method="POST" action="?s=<?= ACCESS_KEY ?>&action=salvar_sandbox" style="display:inline;">
            <input type="hidden" name="sandbox" value="0">
            <button type="submit" class="btn btn-gold">Modo Produção (sandbox=false)</button>
        </form>
    </div>

    <!-- Passo 3: Criar planos -->
    <div class="card">
        <h2>Passo 3 — Criar Planos na Efi Bank</h2>
        <?php if ($plansOk): ?>
        <div class="result ok">✓ Planos já configurados. Mensal: <?= htmlspecialchars($status['efi_plan_id_monthly']) ?> · Anual: <?= htmlspecialchars($status['efi_plan_id_annual']) ?></div>
        <?php else: ?>
        <p style="font-size:13px;color:#8a8aa0;margin-bottom:14px;">
            Cria os planos de assinatura na sua conta Efi Bank e salva os IDs automaticamente.
            Execute <strong>uma única vez</strong> por ambiente (sandbox e produção são ambientes separados).
        </p>
        <a href="?s=<?= ACCESS_KEY ?>&action=criar_planos" class="btn btn-gold">Criar Planos Agora</a>
        <?php endif; ?>
    </div>

    <!-- Instruções .env servidor -->
    <div class="card">
        <h2>Configuração do .env no Servidor</h2>
        <div style="font-size:12px;color:#8a8aa0;line-height:1.8;">
            <div class="step"><div class="num">1</div><div>Edite o arquivo <code>.env</code> na raiz do projeto no servidor</div></div>
            <div class="step"><div class="num">2</div><div>Atualize:<br>
                <code style="color:#C9A96E;">APP_URL=https://nexosn.pageup.net.br</code><br>
                <code style="color:#C9A96E;">EFI_SANDBOX=false</code> (produção) ou <code style="color:#C9A96E;">EFI_SANDBOX=true</code> (homologação)<br>
                <code style="color:#C9A96E;">EFI_CERTIFICATE_PATH=/home/pageup/nexosn/producao-573055-nexosn.p12</code><br>
                <code style="color:#C9A96E;">EFI_PLAN_ID_MONTHLY=</code> (preenchido pelo Passo 3 acima via banco)<br>
                <code style="color:#C9A96E;">EFI_PLAN_ID_ANNUAL=</code> (idem)
            </div></div>
            <div class="step"><div class="num">3</div><div>Execute <code>php artisan config:clear && php artisan cache:clear</code> após editar o .env</div></div>
            <div class="step"><div class="num">4</div><div><strong style="color:#f87171;">Delete este arquivo!</strong> <code>public/efi-setup.php</code></div></div>
        </div>
    </div>

    <p style="font-size:11px;color:#4a4a58;text-align:center;margin-top:32px;">NEXOSN · PageUp Sistemas · <?= date('Y') ?></p>
</div>
</body>
</html>
