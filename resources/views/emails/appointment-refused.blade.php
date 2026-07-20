<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; color: #333; background: #f0f0ee; margin: 0; padding: 20px; }
.card { background: #fff; border-radius: 12px; padding: 32px; max-width: 520px; margin: 0 auto; }
h2 { color: #003049; margin-top: 0; }
.badge { background: #fdecea; color: #c0392b; border-radius: 6px; padding: 8px 16px; display: inline-block; font-weight: 600; margin: 12px 0; }
.footer { text-align: center; color: #aaa; font-size: 12px; margin-top: 24px; }
</style>
</head>
<body>
<div class="card">
    <h2>Sobre sua solicitação de agendamento</h2>

    <div class="badge">✗ Não disponível</div>

    <p>Olá, {{ $appointment->visitor_name }}.</p>
    <p>Infelizmente o horário solicitado
        (<strong>{{ $appointment->appointment_date->format('d/m/Y') }}
        às {{ $appointment->appointment_time }}</strong>) não está disponível no momento.</p>

    @php $card = $appointment->schedule->card; @endphp
    <p>Acesse o cartão de <strong>{{ $card->display_name }}</strong> para verificar outros horários disponíveis:</p>

    <p style="text-align:center; margin: 20px 0;">
        <a href="{{ url('/u/' . $card->slug) }}"
           style="background:#003049; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">
            Ver outros horários
        </a>
    </p>
</div>
<div class="footer">NEXOSN · Identidade digital inteligente · <a href="{{ url('/') }}">nexosn.pageup.net.br</a></div>
</body>
</html>
