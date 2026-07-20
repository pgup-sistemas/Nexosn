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
    <h2>Seu plano Pro vence em {{ $daysLeft }} {{ $daysLeft === 1 ? 'dia' : 'dias' }}</h2>
    <p>Olá, {{ $user->name }}! Seu plano Pro vence em <strong>{{ $daysLeft }} {{ $daysLeft === 1 ? 'dia' : 'dias' }}</strong>.</p>
    <p>Renove agora para continuar com todas as vantagens Pro: cores personalizadas, agenda, galeria completa e sem marca d'água.</p>
    <p style="text-align:center; margin: 20px 0;">
        <a href="{{ url('/dashboard/plan') }}"
           style="background:#D62828; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">
            Renovar plano Pro
        </a>
    </p>
</div>
<div class="footer">NEXOSN · Identidade digital inteligente · <a href="{{ url('/') }}">nexosn.pageup.net.br</a></div>
</body>
</html>
