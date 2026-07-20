@extends('layouts.legal')

@section('title', 'Política de Cookies')
@section('description', 'Entenda como a NEXOSN usa cookies e como você pode gerenciá-los.')

@section('hero')
<div class="legal-hero-tag">Transparência Digital</div>
<h1>Política de Cookies</h1>
<p>Atualizada em 19 de julho de 2026 · Versão 1.0</p>
@endsection

@section('content')

<div class="legal-toc">
    <h3>Sumário</h3>
    <ol>
        <li><a href="#c1">1. O que são cookies</a></li>
        <li><a href="#c2">2. Cookies que utilizamos</a></li>
        <li><a href="#c3">3. Cookies de terceiros</a></li>
        <li><a href="#c4">4. Como gerenciar cookies</a></li>
        <li><a href="#c5">5. Suas escolhas e consentimento</a></li>
    </ol>
</div>

<div class="legal-section" id="c1">
    <h2>
        <svg data-lucide="cookie" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        1. O que são cookies
    </h2>
    <p>Cookies são pequenos arquivos de texto armazenados no seu dispositivo (computador, celular ou tablet) quando você acessa um site. Eles permitem que o site reconheça seu dispositivo em visitas futuras e ofereça uma experiência personalizada.</p>
    <p>Além de cookies, podemos utilizar tecnologias similares como <em>localStorage</em> e <em>sessionStorage</em>, que funcionam de forma análoga, mas armazenadas no próprio navegador.</p>
</div>

<div class="legal-section" id="c2">
    <h2>
        <svg data-lucide="list" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        2. Cookies que utilizamos
    </h2>

    <h3>2.1 Cookies Essenciais (sempre ativos)</h3>
    <p>Necessários para o funcionamento básico do serviço. Não podem ser desativados sem comprometer a funcionalidade.</p>

    <table style="width:100%;border-collapse:collapse;font-size:13px;margin:12px 0;">
        <thead>
            <tr style="background:#f8f8f7;border-bottom:2px solid #EAE2B7;">
                <th style="text-align:left;padding:10px 12px;color:#003049;font-weight:700;">Nome</th>
                <th style="text-align:left;padding:10px 12px;color:#003049;font-weight:700;">Finalidade</th>
                <th style="text-align:left;padding:10px 12px;color:#003049;font-weight:700;">Duração</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom:1px solid #f0f0ee;">
                <td style="padding:10px 12px;color:#1a1f2e;font-weight:600;">card_session</td>
                <td style="padding:10px 12px;color:#52514E;">Sessão de autenticação do usuário logado</td>
                <td style="padding:10px 12px;color:#52514E;">Sessão / 2h</td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0ee;">
                <td style="padding:10px 12px;color:#1a1f2e;font-weight:600;">XSRF-TOKEN</td>
                <td style="padding:10px 12px;color:#52514E;">Proteção contra ataques CSRF em formulários</td>
                <td style="padding:10px 12px;color:#52514E;">Sessão</td>
            </tr>
            <tr>
                <td style="padding:10px 12px;color:#1a1f2e;font-weight:600;">remember_web_*</td>
                <td style="padding:10px 12px;color:#52514E;">Manter sessão ao marcar "lembrar-me" no login</td>
                <td style="padding:10px 12px;color:#52514E;">5 anos</td>
            </tr>
        </tbody>
    </table>

    <h3>2.2 Armazenamento local (localStorage)</h3>
    <table style="width:100%;border-collapse:collapse;font-size:13px;margin:12px 0;">
        <thead>
            <tr style="background:#f8f8f7;border-bottom:2px solid #EAE2B7;">
                <th style="text-align:left;padding:10px 12px;color:#003049;font-weight:700;">Chave</th>
                <th style="text-align:left;padding:10px 12px;color:#003049;font-weight:700;">Finalidade</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:10px 12px;color:#1a1f2e;font-weight:600;">cookie_consent</td>
                <td style="padding:10px 12px;color:#52514E;">Guarda sua escolha no banner de cookies (aceitar / apenas essenciais)</td>
            </tr>
        </tbody>
    </table>

    <div class="legal-highlight">
        Atualmente <strong>não utilizamos cookies analíticos ou de rastreamento de terceiros</strong>. As métricas de visualização do cartão são coletadas no próprio servidor sem ferramentas externas.
    </div>
</div>

<div class="legal-section" id="c3">
    <h2>
        <svg data-lucide="external-link" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        3. Cookies de terceiros
    </h2>
    <p>Utilizamos os seguintes recursos externos que podem definir seus próprios cookies:</p>
    <ul>
        <li><strong>Google Fonts</strong> — carregamento da fonte Inter. Pode gerar cookie de análise de desempenho. Consulte a <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Política de Privacidade do Google</a>.</li>
        <li><strong>unpkg.com (Lucide Icons)</strong> — biblioteca de ícones carregada via CDN. Sem cookies conhecidos.</li>
    </ul>
    <p>Não utilizamos pixels de rastreamento do Facebook, Google Analytics, Hotjar ou ferramentas similares.</p>
</div>

<div class="legal-section" id="c4">
    <h2>
        <svg data-lucide="settings" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        4. Como gerenciar cookies
    </h2>
    <p>Você pode controlar e excluir cookies diretamente no seu navegador:</p>
    <ul>
        <li><strong>Chrome:</strong> Configurações → Privacidade e segurança → Cookies e outros dados de sites</li>
        <li><strong>Firefox:</strong> Configurações → Privacidade e segurança → Cookies e dados de sites</li>
        <li><strong>Safari:</strong> Preferências → Privacidade → Gerenciar dados de sites</li>
        <li><strong>Edge:</strong> Configurações → Privacidade, pesquisa e serviços → Cookies</li>
    </ul>
    <p>A desativação de cookies essenciais pode impedir o funcionamento do login e de outras funcionalidades do painel.</p>
</div>

<div class="legal-section" id="c5">
    <h2>
        <svg data-lucide="check-circle" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        5. Suas escolhas e consentimento
    </h2>
    <p>Ao acessar a NEXOSN pela primeira vez, exibimos um banner de cookies com as opções:</p>
    <ul>
        <li><strong>"Apenas essenciais"</strong> — ativa somente os cookies necessários para o funcionamento</li>
        <li><strong>"Aceitar todos"</strong> — consente com cookies essenciais e eventuais cookies de melhoria de experiência</li>
    </ul>
    <p>Você pode alterar sua preferência a qualquer momento limpando os dados do site no seu navegador, o que fará o banner reaparecer.</p>
    <p>Para mais informações sobre como tratamos seus dados pessoais, consulte nossa <a href="{{ route('legal.privacidade') }}">Política de Privacidade</a>.</p>
</div>

@endsection
