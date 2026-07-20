<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') · NEXOSN</title>
<meta name="description" content="@yield('description', 'Documentos legais — NEXOSN · PageUp Sistemas')">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:site_name" content="NEXOSN">
<meta property="og:title" content="@yield('title') · NEXOSN">
<meta property="og:description" content="@yield('description', 'Documentos legais — NEXOSN · PageUp Sistemas')">
<meta property="og:type" content="website">
<meta property="og:locale" content="pt_BR">
<meta name="theme-color" content="#003049">
<link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192.png">
<link rel="apple-touch-icon" href="/images/icon-192.png">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #F0F0EE; color: #1A1F2E; }

.legal-nav {
    background: #003049;
    padding: 0 24px;
    height: 56px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
}
.legal-nav-brand {
    font-size: 18px; font-weight: 800; color: #fff;
    display: flex; align-items: center; gap: 6px;
    text-decoration: none;
}
.legal-nav-brand span { color: #FCBF49; }
.legal-nav-link {
    font-size: 13px; color: rgba(255,255,255,0.65);
    text-decoration: none; transition: color .15s;
    display: flex; align-items: center; gap: 5px;
}
.legal-nav-link:hover { color: #fff; }

.legal-hero {
    background: linear-gradient(135deg, #003049 0%, #005073 100%);
    padding: 52px 24px 48px;
    text-align: center;
}
.legal-hero-tag {
    display: inline-block; background: rgba(252,191,73,0.15);
    border: 1px solid rgba(252,191,73,0.3);
    color: #FCBF49; font-size: 11px; font-weight: 700;
    letter-spacing: .5px; text-transform: uppercase;
    padding: 4px 12px; border-radius: 100px; margin-bottom: 16px;
}
.legal-hero h1 {
    font-size: clamp(22px, 4vw, 36px); font-weight: 900;
    color: #fff; line-height: 1.2; margin-bottom: 10px;
}
.legal-hero p { font-size: 14px; color: rgba(255,255,255,0.6); }

.legal-body {
    max-width: 800px; margin: 0 auto;
    padding: 48px 24px 80px;
}
.legal-toc {
    background: #fff; border-radius: 14px; padding: 24px 28px;
    border: 1px solid #E0E0DE; margin-bottom: 40px;
}
.legal-toc h3 { font-size: 13px; font-weight: 700; color: #003049; margin-bottom: 14px; text-transform: uppercase; letter-spacing: .5px; }
.legal-toc ol { padding-left: 18px; display: flex; flex-direction: column; gap: 6px; }
.legal-toc ol li a { font-size: 13px; color: #003049; text-decoration: none; transition: opacity .15s; }
.legal-toc ol li a:hover { opacity: .7; }

.legal-section {
    background: #fff; border-radius: 14px; padding: 32px 36px;
    border: 1px solid #E0E0DE; margin-bottom: 16px;
}
@media(max-width: 600px) { .legal-section { padding: 24px 20px; } }
.legal-section h2 {
    font-size: 18px; font-weight: 800; color: #003049;
    margin-bottom: 16px; padding-bottom: 12px;
    border-bottom: 2px solid #EAE2B7;
    display: flex; align-items: center; gap: 10px;
}
.legal-section h3 {
    font-size: 15px; font-weight: 700; color: #1A1F2E;
    margin: 20px 0 8px;
}
.legal-section p {
    font-size: 14px; color: #52514E; line-height: 1.75; margin-bottom: 12px;
}
.legal-section p:last-child { margin-bottom: 0; }
.legal-section ul, .legal-section ol {
    padding-left: 20px; display: flex; flex-direction: column; gap: 6px;
    margin-bottom: 12px;
}
.legal-section ul li, .legal-section ol li {
    font-size: 14px; color: #52514E; line-height: 1.6;
}
.legal-section strong { color: #1A1F2E; font-weight: 600; }
.legal-section a { color: #003049; text-decoration: underline; }
.legal-highlight {
    background: rgba(0,48,73,0.05); border-left: 3px solid #003049;
    border-radius: 0 8px 8px 0; padding: 14px 18px; margin: 16px 0;
    font-size: 14px; color: #003049; line-height: 1.6;
}

.legal-footer {
    background: #003049; padding: 32px 24px;
    text-align: center;
}
.legal-footer p { font-size: 13px; color: rgba(255,255,255,0.5); margin-bottom: 12px; }
.legal-footer-links { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
.legal-footer-links a { font-size: 12px; color: rgba(255,255,255,0.5); text-decoration: none; transition: color .15s; }
.legal-footer-links a:hover { color: rgba(255,255,255,0.9); }
.legal-footer-links a.active { color: #FCBF49; font-weight: 600; }
</style>
</head>
<body>

<nav class="legal-nav">
    <a href="{{ url('/') }}" class="legal-nav-brand">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="color:#FCBF49">
            <line x1="5" y1="5"  x2="5"  y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
            <line x1="5" y1="5"  x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
            <line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
            <circle cx="5"  cy="5"  r="2.75" fill="currentColor"/>
            <circle cx="5"  cy="19" r="2.75" fill="currentColor"/>
            <circle cx="19" cy="5"  r="2.75" fill="currentColor"/>
            <circle cx="19" cy="19" r="2.75" fill="currentColor"/>
        </svg>
        NEX<span style="opacity:.6;color:#fff;">OSN</span><span>.</span>
    </a>
    <a href="{{ url('/') }}" class="legal-nav-link">
        <svg data-lucide="arrow-left" style="width:14px;height:14px;"></svg>
        Voltar ao site
    </a>
</nav>

<div class="legal-hero">
    @yield('hero')
</div>

<div class="legal-body">
    @yield('content')
</div>

<footer class="legal-footer">
    <p>© {{ date('Y') }} NEXOSN · PageUp Sistemas · Porto Velho, RO</p>
    <div class="legal-footer-links">
        <a href="{{ route('legal.privacidade') }}" class="{{ request()->routeIs('legal.privacidade') ? 'active' : '' }}">Política de Privacidade</a>
        <a href="{{ route('legal.cookies') }}"     class="{{ request()->routeIs('legal.cookies')     ? 'active' : '' }}">Política de Cookies</a>
        <a href="{{ route('legal.termos') }}"      class="{{ request()->routeIs('legal.termos')      ? 'active' : '' }}">Termos de Uso</a>
        <a href="mailto:privacidade@pageup.net.br">privacidade@pageup.net.br</a>
    </div>
</footer>

<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
</body>
</html>
