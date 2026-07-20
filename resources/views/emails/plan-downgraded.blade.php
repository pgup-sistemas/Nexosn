<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; color: #333; background: #f0f0ee; margin: 0; padding: 20px; }
.card { background: #fff; border-radius: 12px; padding: 32px; max-width: 520px; margin: 0 auto; }
h2 { color: #003049; margin-top: 0; }
.footer { text-align: center; color: #aaa; font-size: 12px; margin-top: 24px; }
</style>
</head>
<body>
<div class="card">
    <h2>Plano alterado para Free</h2>
    <p>Olá, {{ $user->name }}. Informamos que seu plano foi alterado para <strong>Free</strong>.</p>
    <p>Para reativar as funcionalidades Pro, acesse o painel e contrate o plano Pro.</p>
    <p style="text-align:center; margin: 20px 0;">
        <a href="{{ url('/dashboard/plan') }}"
           style="background:#003049; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">
            Ver planos
        </a>
    </p>
</div>
<div class="footer">NEXOSN · Identidade digital inteligente · <a href="{{ url('/') }}">nexosn.pageup.net.br</a></div>
</body>
</html>
