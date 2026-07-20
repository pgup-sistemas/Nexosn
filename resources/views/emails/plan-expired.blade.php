<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; color: #333; background: #f0f0ee; margin: 0; padding: 20px; }
.card { background: #fff; border-radius: 12px; padding: 32px; max-width: 520px; margin: 0 auto; }
h2 { color: #D62828; margin-top: 0; }
.footer { text-align: center; color: #aaa; font-size: 12px; margin-top: 24px; }
</style>
</head>
<body>
<div class="card">
    <h2>Seu plano Pro expirou</h2>
    <p>Olá, {{ $user->name }}. Seu plano Pro expirou e sua conta foi migrada para o plano Free.</p>
    <p>Você ainda pode usar a NEXOSN gratuitamente com até 5 links e 3 fotos.</p>
    <p style="text-align:center; margin: 20px 0;">
        <a href="{{ url('/dashboard/plan') }}"
           style="background:#003049; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">
            Reativar plano Pro
        </a>
    </p>
</div>
<div class="footer">NEXOSN · Identidade digital inteligente · <a href="{{ url('/') }}">nexosn.pageup.net.br</a></div>
</body>
</html>
