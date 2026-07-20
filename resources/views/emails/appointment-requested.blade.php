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
.btn { display: inline-block; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; margin: 6px 4px; }
.btn-confirm { background: #003049; color: #fff; }
.btn-refuse { background: #D62828; color: #fff; }
.footer { text-align: center; color: #aaa; font-size: 12px; margin-top: 24px; }
</style>
</head>
<body>
<div class="card">
    <h2>Nova solicitação de agendamento</h2>
    <p>Você recebeu uma nova solicitação pelo seu cartão digital.</p>

    <div class="detail">
        <p><strong>Solicitante:</strong> {{ $appointment->visitor_name }}</p>
        <p><strong>E-mail:</strong> {{ $appointment->visitor_email }}</p>
        @if ($appointment->visitor_phone)
        <p><strong>Telefone:</strong> {{ $appointment->visitor_phone }}</p>
        @endif
        <p><strong>Data:</strong> {{ $appointment->appointment_date->format('d/m/Y') }}</p>
        <p><strong>Horário:</strong> {{ $appointment->appointment_time }}</p>
        <p><strong>Serviço:</strong> {{ $appointment->schedule->service_name }}</p>
        @if ($appointment->notes)
        <p><strong>Observações:</strong> {{ $appointment->notes }}</p>
        @endif
    </div>

    <p>Confirme ou recuse clicando nos botões abaixo:</p>

    <div style="text-align:center; margin: 20px 0;">
        <a href="{{ url('/appointments/' . $appointment->token . '/confirm') }}" class="btn btn-confirm">
            ✓ Confirmar agendamento
        </a>
        <a href="{{ url('/appointments/' . $appointment->token . '/refuse') }}" class="btn btn-refuse">
            ✗ Recusar
        </a>
    </div>

    <p style="font-size:12px; color:#999; text-align:center;">
        Links válidos por 7 dias · {{ now()->format('d/m/Y H:i') }}
    </p>
</div>
<div class="footer">NEXOSN · Identidade digital inteligente · <a href="{{ url('/') }}">nexosn.pageup.net.br</a></div>
</body>
</html>
