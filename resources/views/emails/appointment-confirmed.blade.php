<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; color: #333; background: #f0f0ee; margin: 0; padding: 20px; }
.card { background: #fff; border-radius: 12px; padding: 32px; max-width: 520px; margin: 0 auto; }
h2 { color: #003049; margin-top: 0; }
.detail { background: #f8f8f7; border-radius: 8px; padding: 16px; margin: 16px 0; }
.detail p { margin: 4px 0; font-size: 14px; }
.badge { background: #e6f4ea; color: #1e7e34; border-radius: 6px; padding: 8px 16px; display: inline-block; font-weight: 600; margin: 12px 0; }
.footer { text-align: center; color: #aaa; font-size: 12px; margin-top: 24px; }
</style>
</head>
<body>
<div class="card">
    <h2>Agendamento confirmado!</h2>

    <div class="badge">✓ Confirmado</div>

    <p>Olá, {{ $appointment->visitor_name }}! Seu agendamento foi confirmado.</p>

    <div class="detail">
        <p><strong>Serviço:</strong> {{ $appointment->schedule->service_name }}</p>
        <p><strong>Data:</strong> {{ $appointment->appointment_date->format('d/m/Y') }}</p>
        <p><strong>Horário:</strong> {{ $appointment->appointment_time }}</p>
        @php $card = $appointment->schedule->card; @endphp
        <p><strong>Com:</strong> {{ $card->display_name }}</p>
        @if ($card->contact_email)
        <p><strong>Contato:</strong> {{ $card->contact_email }}</p>
        @endif
        @if ($card->contact_phone)
        <p><strong>Telefone:</strong> {{ $card->contact_phone }}</p>
        @endif
    </div>
</div>
<div class="footer">NEXOSN · Identidade digital inteligente · <a href="{{ url('/') }}">nexosn.pageup.net.br</a></div>
</body>
</html>
