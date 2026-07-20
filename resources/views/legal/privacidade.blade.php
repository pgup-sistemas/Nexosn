@extends('layouts.legal')

@section('title', 'Política de Privacidade')
@section('description', 'Saiba como a NEXOSN coleta, usa e protege seus dados pessoais conforme a LGPD.')

@section('hero')
<div class="legal-hero-tag">LGPD — Lei 13.709/2018</div>
<h1>Política de Privacidade</h1>
<p>Atualizada em 19 de julho de 2026 · Versão 1.0</p>
@endsection

@section('content')

<div class="legal-toc">
    <h3>Sumário</h3>
    <ol>
        <li><a href="#s1">1. Quem somos — o Controlador</a></li>
        <li><a href="#s2">2. Quais dados coletamos</a></li>
        <li><a href="#s3">3. Por que coletamos — bases legais</a></li>
        <li><a href="#s4">4. Por quanto tempo mantemos os dados</a></li>
        <li><a href="#s5">5. Com quem compartilhamos</a></li>
        <li><a href="#s6">6. Seus direitos como titular</a></li>
        <li><a href="#s7">7. Segurança dos dados</a></li>
        <li><a href="#s8">8. Transferência internacional</a></li>
        <li><a href="#s9">9. Encarregado (DPO)</a></li>
        <li><a href="#s10">10. Alterações nesta política</a></li>
    </ol>
</div>

<div class="legal-section" id="s1">
    <h2>
        <svg data-lucide="building-2" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        1. Quem somos — o Controlador
    </h2>
    <p>A <strong>NEXOSN</strong> é uma plataforma de identidade digital operada por <strong>PageUp Sistemas</strong>, empresa com sede em Porto Velho, Rondônia, Brasil.</p>
    <p>Para fins da Lei Geral de Proteção de Dados Pessoais (LGPD — Lei nº 13.709/2018), a PageUp Sistemas é a <strong>Controladora</strong> dos dados pessoais tratados neste serviço.</p>
    <div class="legal-highlight">
        <strong>Contato do Controlador:</strong><br>
        E-mail geral: contato@pageup.net.br<br>
        E-mail de privacidade: privacidade@pageup.net.br<br>
        Endereço: Porto Velho, RO, Brasil
    </div>
</div>

<div class="legal-section" id="s2">
    <h2>
        <svg data-lucide="database" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        2. Quais dados coletamos
    </h2>

    <h3>2.1 Dados fornecidos pelo Titular da conta</h3>
    <ul>
        <li>Nome completo, endereço de e-mail, senha (armazenada com hash bcrypt)</li>
        <li>Dados do cartão digital: nome de exibição, cargo, empresa, bio, telefone, endereço</li>
        <li>Fotos de perfil e capa (armazenadas em servidor seguro)</li>
        <li>Chave PIX (armazenada para exibição no cartão, não processamos pagamentos)</li>
        <li>Informações de agenda e horários de atendimento</li>
    </ul>

    <h3>2.2 Dados de Visitantes do cartão público</h3>
    <ul>
        <li>Endereço IP (para contagem de visualizações, deduplificado a cada 24h)</li>
        <li>User-agent do navegador</li>
        <li>Referenciador (URL de origem da visita)</li>
        <li>Dados preenchidos no formulário de contato: nome, e-mail, telefone e mensagem</li>
        <li>Dados de solicitação de agendamento: nome, e-mail, telefone, data e horário</li>
    </ul>

    <h3>2.3 Dados coletados automaticamente</h3>
    <ul>
        <li>Logs de acesso (conforme exigência do Marco Civil da Internet — Lei 12.965/2014)</li>
        <li>Cookies de sessão e autenticação (ver <a href="{{ route('legal.cookies') }}">Política de Cookies</a>)</li>
    </ul>

    <p><strong>Não coletamos</strong> dados sensíveis (saúde, origem racial, convicção religiosa, biometria etc.) nem dados de menores de 18 anos.</p>
</div>

<div class="legal-section" id="s3">
    <h2>
        <svg data-lucide="scale" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        3. Por que coletamos — bases legais (LGPD Art. 7º)
    </h2>
    <ul>
        <li><strong>Execução de contrato (Art. 7º, V):</strong> criação de conta, manutenção do cartão digital e processamento de assinatura</li>
        <li><strong>Legítimo interesse (Art. 7º, IX):</strong> contagem de visualizações agregada, prevenção a fraudes e segurança do serviço</li>
        <li><strong>Cumprimento de obrigação legal (Art. 7º, II):</strong> guarda de logs conforme Marco Civil da Internet</li>
        <li><strong>Consentimento (Art. 7º, I):</strong> cookies analíticos não essenciais, quando aplicável</li>
    </ul>
</div>

<div class="legal-section" id="s4">
    <h2>
        <svg data-lucide="clock" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        4. Por quanto tempo mantemos os dados
    </h2>
    <ul>
        <li><strong>Dados da conta ativa:</strong> enquanto a conta existir</li>
        <li><strong>Após exclusão da conta:</strong> apagamento completo em até 30 dias corridos</li>
        <li><strong>Logs de acesso:</strong> 6 meses, conforme Art. 15 do Marco Civil da Internet</li>
        <li><strong>Dados fiscais e de cobrança:</strong> 5 anos, conforme legislação tributária</li>
        <li><strong>Mensagens de contato e agendamentos:</strong> apagados junto com a conta</li>
    </ul>
</div>

<div class="legal-section" id="s5">
    <h2>
        <svg data-lucide="share-2" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        5. Com quem compartilhamos
    </h2>
    <p>Não vendemos seus dados. Compartilhamos apenas com fornecedores essenciais para operar o serviço:</p>
    <ul>
        <li><strong>Efi Bank (Gerencianet S.A.):</strong> processamento de pagamentos de assinatura</li>
        <li><strong>Resend / provedor de e-mail:</strong> envio de e-mails transacionais (confirmações, notificações)</li>
        <li><strong>Provedor de hospedagem:</strong> armazenamento dos dados em servidores no Brasil</li>
    </ul>
    <p>Todos os fornecedores são contratados com cláusulas de proteção de dados e só acessam o mínimo necessário para a execução do serviço.</p>
    <p>Podemos divulgar dados em cumprimento a ordem judicial ou requisição de autoridade competente, conforme previsto em lei.</p>
</div>

<div class="legal-section" id="s6">
    <h2>
        <svg data-lucide="shield-check" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        6. Seus direitos como titular (LGPD Art. 18)
    </h2>
    <p>Você tem direito a, a qualquer momento:</p>
    <ul>
        <li><strong>Confirmação e acesso:</strong> saber quais dados temos sobre você</li>
        <li><strong>Correção:</strong> atualizar dados incompletos, inexatos ou desatualizados</li>
        <li><strong>Anonimização, bloqueio ou eliminação:</strong> de dados desnecessários ou tratados em desconformidade</li>
        <li><strong>Portabilidade:</strong> receber seus dados em formato estruturado (CSV/JSON)</li>
        <li><strong>Eliminação:</strong> excluir sua conta e todos os dados pelo painel em <em>Configurações → Excluir conta</em></li>
        <li><strong>Revogação do consentimento:</strong> retirar autorização dada anteriormente</li>
        <li><strong>Oposição:</strong> contestar tratamento baseado em legítimo interesse</li>
        <li><strong>Informação sobre compartilhamento:</strong> saber com quais entidades compartilhamos dados</li>
    </ul>
    <div class="legal-highlight">
        Para exercer qualquer direito, envie um e-mail para <strong>privacidade@pageup.net.br</strong> com identificação. Responderemos em até <strong>15 dias úteis</strong>.<br><br>
        Você também pode registrar reclamação na <strong>ANPD</strong> (Autoridade Nacional de Proteção de Dados): <a href="https://www.gov.br/anpd" target="_blank" rel="noopener">www.gov.br/anpd</a>
    </div>
</div>

<div class="legal-section" id="s7">
    <h2>
        <svg data-lucide="lock" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        7. Segurança dos dados
    </h2>
    <ul>
        <li>Senhas armazenadas com hash <strong>bcrypt</strong> (sem armazenamento em texto puro)</li>
        <li>Comunicação protegida por <strong>HTTPS/TLS</strong> em produção</li>
        <li>Dados de cartão de crédito <strong>nunca</strong> armazenados em nossos servidores</li>
        <li>Acesso ao banco de dados restrito ao servidor de aplicação</li>
        <li>Backups regulares com retenção controlada</li>
    </ul>
    <p>Em caso de incidente de segurança que possa afetar seus dados, notificaremos a ANPD e os titulares afetados nos prazos previstos no Art. 48 da LGPD.</p>
</div>

<div class="legal-section" id="s8">
    <h2>
        <svg data-lucide="globe" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        8. Transferência internacional
    </h2>
    <p>Alguns fornecedores de serviços (como provedores de e-mail) podem processar dados fora do Brasil. Nesses casos, exigimos contratualmente que os dados recebam proteção equivalente à exigida pela LGPD, conforme Art. 33.</p>
</div>

<div class="legal-section" id="s9">
    <h2>
        <svg data-lucide="user-check" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        9. Encarregado de Proteção de Dados (DPO)
    </h2>
    <p>Conforme Art. 41 da LGPD, o encarregado responsável pelo tratamento de dados pessoais pode ser contatado pelo e-mail <strong><a href="mailto:privacidade@pageup.net.br">privacidade@pageup.net.br</a></strong>.</p>
</div>

<div class="legal-section" id="s10">
    <h2>
        <svg data-lucide="refresh-cw" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        10. Alterações nesta política
    </h2>
    <p>Podemos atualizar esta política periodicamente. Alterações relevantes serão comunicadas por e-mail ou aviso no painel com antecedência mínima de 15 dias. A data da última atualização está indicada no topo desta página.</p>
    <p>O uso continuado do serviço após a vigência das alterações implica aceitação da nova versão.</p>
</div>

@endsection
