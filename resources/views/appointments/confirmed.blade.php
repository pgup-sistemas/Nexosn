<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agendamento confirmado · NEXOSN</title>
<style>
@font-face { font-family: 'Inter'; font-weight: 400; font-display: swap; src: url('/fonts/inter-400.woff2') format('woff2'); }
@font-face { font-family: 'Inter'; font-weight: 500; font-display: swap; src: url('/fonts/inter-500.woff2') format('woff2'); }
@font-face { font-family: 'Inter'; font-weight: 600; font-display: swap; src: url('/fonts/inter-600.woff2') format('woff2'); }
</style><style>
*, *::before, *::after { box-sizing: border-box; }
body { font-family: Inter, sans-serif; background: #F0F0EE; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; }
.card { background: white; border-radius: 16px; padding: 40px 32px; max-width: 480px; width: 100%; text-align: center; }
.icon { width: 56px; height: 56px; border-radius: 50%; background: #e6f4ea; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 24px; }
h1 { font-size: 20px; font-weight: 600; color: #1a1a1a; margin: 0 0 8px; }
p { font-size: 14px; color: #666; line-height: 1.5; margin: 0 0 16px; }
.detail { background: #f8f8f7; border-radius: 10px; padding: 16px; margin: 20px 0; text-align: left; }
.detail p { margin: 4px 0; font-size: 13px; color: #555; }
.btn { display: inline-block; margin-top: 16px; padding: 12px 24px; border-radius: 10px; background: #003049; color: white; text-decoration: none; font-size: 14px; font-weight: 500; }
</style>
</head>
<body>
<div class="card">
    <div class="icon">✓</div>
    <h1>Agendamento confirmado!</h1>
    <p>O visitante receberá um e-mail de confirmação.</p>

    <div class="detail">
        <p><strong>Visitante:</strong> {{ $appointment->visitor_name }}</p>
        <p><strong>Data:</strong> {{ $appointment->appointment_date->format('d/m/Y') }}</p>
        <p><strong>Horário:</strong> {{ $appointment->appointment_time }}</p>
    </div>

    <a href="/dashboard/appointments" class="btn">Ver agendamentos</a>
</div>
</body>
</html>
