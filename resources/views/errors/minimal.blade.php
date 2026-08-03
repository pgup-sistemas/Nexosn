<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $code }} — NEXOSN</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: #F0F0EE; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; padding: 24px;
        }
        .card {
            background: #fff; border-radius: 20px; max-width: 420px; width: 100%;
            padding: 40px 32px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,.06);
        }
        .code { font-size: 13px; font-weight: 700; letter-spacing: .05em; color: #D62828; margin-bottom: 12px; }
        .icon {
            width: 56px; height: 56px; border-radius: 9999px; background: #FFF4E5;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
        }
        h1 { font-size: 19px; font-weight: 600; color: #1a1f2e; margin: 0 0 10px; }
        p { font-size: 14px; color: #666; line-height: 1.6; margin: 0 0 28px; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            background: #003049; color: #fff; font-size: 14px; font-weight: 600;
            text-decoration: none; border-radius: 10px; padding: 12px 24px; transition: opacity .15s;
        }
        .btn:hover { opacity: .9; }
        .logo { font-size: 13px; font-weight: 700; color: #003049; margin-top: 28px; letter-spacing: .02em; }
    </style>
</head>
<body>
    <div class="card">
        <div class="code">Erro {{ $code }}</div>
        <div class="icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#D62828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/></svg>
        </div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a href="{{ url('/') }}" class="btn">Voltar ao início</a>
        <div class="logo">NEXOSN</div>
    </div>
</body>
</html>
