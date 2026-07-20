<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">

    <title>@yield('title') | NEXOSN — Identidade Digital</title>
    <meta name="description" content="@yield('description', 'Identidade digital única, segura e inteligente. Conecte pessoas, empresas e oportunidades com a NEXOSN.')">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    {{-- Open Graph (WhatsApp, Facebook, LinkedIn) --}}
    <meta property="og:site_name" content="NEXOSN">
    <meta property="og:title" content="@yield('title') | NEXOSN">
    <meta property="og:description" content="@yield('description', 'Identidade digital única, segura e inteligente. Conecte pessoas, empresas e oportunidades com a NEXOSN.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="pt_BR">

    {{-- Twitter / X Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@nexosn">
    <meta name="twitter:title" content="@yield('title') | NEXOSN">
    <meta name="twitter:description" content="@yield('description', 'Identidade digital única, segura e inteligente.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.png'))">

    @yield('card_colors')

    @vite(['resources/css/app.css', 'resources/js/card.js'])

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        /* Fundo da página com padrão sutil */
        .card-page-bg {
            min-height: 100vh;
            background-color: #EBEBEA;
            background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.06) 1px, transparent 0);
            background-size: 24px 24px;
            padding: 0;
        }
        @media (min-width: 480px) {
            .card-page-bg { padding: 32px 16px 48px; }
        }
        .card-shell {
            margin: 0 auto;
            max-width: 420px;
            min-height: 100vh;
            background: #fff;
            position: relative;
        }
        @media (min-width: 480px) {
            .card-shell {
                min-height: unset;
                border-radius: 24px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 4px 16px rgba(0,0,0,0.08);
            }
        }
    </style>

    {{-- Lucide Icons: inicializados via resources/js/card.js (bundled pelo Vite) --}}
</head>
<body>
    <div class="card-page-bg">
        <main class="card-shell">
            @yield('content')
        </main>
    </div>
</body>
</html>
