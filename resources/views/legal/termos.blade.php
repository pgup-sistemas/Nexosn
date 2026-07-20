@extends('layouts.legal')

@section('title', 'Termos de Uso')
@section('description', 'Termos e condições de uso da plataforma NEXOSN · PageUp Sistemas.')

@section('hero')
<div class="legal-hero-tag">Contrato de Serviço</div>
<h1>Termos de Uso</h1>
<p>Atualizado em 19 de julho de 2026 · Versão 1.0</p>
@endsection

@section('content')

<div class="legal-toc">
    <h3>Sumário</h3>
    <ol>
        <li><a href="#t1">1. Aceitação dos termos</a></li>
        <li><a href="#t2">2. A plataforma NEXOSN</a></li>
        <li><a href="#t3">3. Cadastro e conta</a></li>
        <li><a href="#t4">4. Planos, pagamento e cancelamento</a></li>
        <li><a href="#t5">5. Uso permitido e proibido</a></li>
        <li><a href="#t6">6. Conteúdo do usuário</a></li>
        <li><a href="#t7">7. Propriedade intelectual</a></li>
        <li><a href="#t8">8. Limitação de responsabilidade</a></li>
        <li><a href="#t9">9. Suspensão e encerramento</a></li>
        <li><a href="#t10">10. Disposições gerais</a></li>
    </ol>
</div>

<div class="legal-section" id="t1">
    <h2>
        <svg data-lucide="file-check" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        1. Aceitação dos termos
    </h2>
    <p>Ao criar uma conta ou usar a plataforma <strong>NEXOSN</strong>, você declara ter lido, compreendido e concordado com estes Termos de Uso. Se você não concordar com qualquer parte destes termos, não utilize o serviço.</p>
    <p>Estes Termos constituem um contrato legalmente vinculante entre você (<strong>"Usuário"</strong>) e a <strong>PageUp Sistemas</strong> (<strong>"nós"</strong> ou <strong>"NEXOSN"</strong>).</p>
    <div class="legal-highlight">
        O uso do serviço é restrito a pessoas maiores de <strong>18 anos</strong> ou emancipadas. Ao aceitar estes termos, você confirma ter capacidade legal para contratar.
    </div>
</div>

<div class="legal-section" id="t2">
    <h2>
        <svg data-lucide="credit-card" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        2. A plataforma NEXOSN
    </h2>
    <p>A <strong>NEXOSN</strong> é uma plataforma SaaS (Software como Serviço) de identidade digital que permite criar, personalizar e compartilhar sua presença online — cartão digital, agenda, links e mais — acessíveis via link e QR Code.</p>
    <p>O serviço é fornecido nas modalidades <strong>Free</strong> (gratuita, com limitações) e <strong>Pro</strong> (paga, com recursos completos). Reservamo-nos o direito de alterar, suspender ou descontinuar qualquer funcionalidade com aviso prévio razoável.</p>
    <p>Não garantimos disponibilidade ininterrupta. Realizamos manutenções periódicas, geralmente fora do horário comercial, com comunicação antecipada quando possível.</p>
</div>

<div class="legal-section" id="t3">
    <h2>
        <svg data-lucide="user-plus" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        3. Cadastro e conta
    </h2>
    <ul>
        <li>Você deve fornecer informações verdadeiras, precisas e completas no cadastro</li>
        <li>É responsável por manter a confidencialidade de sua senha e por todas as atividades realizadas em sua conta</li>
        <li>O <strong>slug</strong> (endereço do cartão) é único e intransferível. Slugs que violem direitos de terceiros podem ser removidos sem aviso</li>
        <li>Uma conta por pessoa física ou jurídica, salvo contratação de planos múltiplos</li>
        <li>Não é permitido compartilhar, vender ou transferir contas</li>
        <li>Informe-nos imediatamente sobre qualquer uso não autorizado de sua conta: <a href="mailto:contato@pageup.net.br">contato@pageup.net.br</a></li>
    </ul>
</div>

<div class="legal-section" id="t4">
    <h2>
        <svg data-lucide="credit-card" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        4. Planos, pagamento e cancelamento
    </h2>

    <h3>4.1 Plano Free</h3>
    <p>Gratuito, sem prazo de expiração. Inclui recursos básicos com as limitações descritas na página de planos. Uma marca d'água "Criado com NEXOSN" é exibida no cartão público.</p>

    <h3>4.2 Plano Pro</h3>
    <p>Pago mensalmente ou anualmente. Os valores vigentes estão na página <a href="{{ url('/#planos') }}">Planos e Preços</a>. Ao assinar:</p>
    <ul>
        <li>A cobrança é processada pela <strong>Efi Bank</strong> (instituição financeira autorizada pelo Banco Central do Brasil)</li>
        <li>A assinatura é renovada automaticamente no vencimento</li>
        <li>Para cancelar, acesse o painel → Plano → Cancelar assinatura, com efeito ao fim do período pago</li>
    </ul>

    <h3>4.3 Trial gratuito</h3>
    <p>Novos usuários recebem <strong>14 dias do Plano Pro gratuitamente</strong> ao verificar o e-mail. Ao final do trial, a conta retorna ao Free automaticamente — sem cobranças.</p>

    <h3>4.4 Reembolso</h3>
    <p>Não oferecemos reembolso proporcional por cancelamento antecipado. Em caso de falha técnica comprovada de nossa parte, avalie-se o reembolso caso a caso em até 7 dias corridos após o ocorrido.</p>
</div>

<div class="legal-section" id="t5">
    <h2>
        <svg data-lucide="shield-alert" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        5. Uso permitido e proibido
    </h2>

    <h3>5.1 Uso permitido</h3>
    <ul>
        <li>Criar e compartilhar seu cartão de visita digital para fins profissionais e pessoais lícitos</li>
        <li>Receber mensagens e agendamentos de clientes e contatos</li>
        <li>Divulgar links para redes sociais, portfólio e serviços próprios</li>
    </ul>

    <h3>5.2 Uso proibido</h3>
    <p>É expressamente proibido usar a NEXOSN para:</p>
    <ul>
        <li>Disseminar conteúdo ilegal, difamatório, discriminatório, pornográfico ou que incite violência</li>
        <li>Praticar phishing, golpes, fraudes ou qualquer atividade enganosa</li>
        <li>Violar direitos de propriedade intelectual, privacidade ou imagem de terceiros</li>
        <li>Enviar spam ou comunicações não solicitadas em massa</li>
        <li>Usar o serviço para atividades ilegais sob a legislação brasileira</li>
        <li>Criar contas automatizadas, usar bots ou fazer scraping do serviço</li>
        <li>Tentar acessar, modificar ou interferir em contas de outros usuários</li>
        <li>Sobrecarregar nossa infraestrutura com tráfego artificial</li>
    </ul>
    <p>A violação destas proibições pode resultar em suspensão imediata da conta, sem direito a reembolso.</p>
</div>

<div class="legal-section" id="t6">
    <h2>
        <svg data-lucide="image" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        6. Conteúdo do usuário
    </h2>
    <p>Você é o único responsável pelo conteúdo que publica na NEXOSN (textos, fotos, links, dados de contato etc.). Ao carregar conteúdo, você nos concede uma <strong>licença limitada, não exclusiva, para armazenar e exibir</strong> esse conteúdo no serviço.</p>
    <p>Não reivindocamos propriedade sobre seu conteúdo. A licença termina quando você o remove ou exclui sua conta.</p>
    <p>Podemos remover conteúdo que viole estes termos ou a legislação vigente, com ou sem aviso prévio, dependendo da gravidade.</p>
</div>

<div class="legal-section" id="t7">
    <h2>
        <svg data-lucide="copyright" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        7. Propriedade intelectual
    </h2>
    <p>A plataforma NEXOSN, incluindo sua interface, código-fonte, marca, logotipo e design, é propriedade da <strong>PageUp Sistemas</strong> e protegido pela legislação brasileira de propriedade intelectual (Lei 9.279/96 e Lei 9.610/98).</p>
    <p>É proibido copiar, modificar, distribuir, vender ou sublicenciar qualquer parte do serviço sem autorização expressa e por escrito.</p>
</div>

<div class="legal-section" id="t8">
    <h2>
        <svg data-lucide="alert-triangle" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        8. Limitação de responsabilidade
    </h2>
    <p>O serviço é fornecido <strong>"como está"</strong>, sem garantias de adequação a finalidade específica ou disponibilidade ininterrupta.</p>
    <p>A PageUp Sistemas não se responsabiliza por:</p>
    <ul>
        <li>Perda de dados decorrente de falhas no dispositivo do usuário</li>
        <li>Conteúdo publicado por usuários no cartão digital</li>
        <li>Danos indiretos, lucros cessantes ou perda de oportunidades</li>
        <li>Serviços de terceiros integrados (Efi Bank, redes sociais, Google Maps etc.)</li>
    </ul>
    <p>Nossa responsabilidade máxima, em qualquer hipótese, fica limitada ao valor pago pelo usuário nos últimos 3 meses.</p>
</div>

<div class="legal-section" id="t9">
    <h2>
        <svg data-lucide="x-circle" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        9. Suspensão e encerramento
    </h2>
    <p>Podemos suspender ou encerrar sua conta, com ou sem aviso prévio, em caso de:</p>
    <ul>
        <li>Violação destes Termos de Uso</li>
        <li>Inadimplência superior a 30 dias no Plano Pro</li>
        <li>Uso do serviço para fins ilegais ou que prejudiquem terceiros</li>
        <li>Requisição de autoridade judicial ou administrativa competente</li>
    </ul>
    <p>Você pode encerrar sua conta a qualquer momento em <strong>Painel → Configurações → Excluir conta</strong>. Após a exclusão, os dados são removidos conforme nossa <a href="{{ route('legal.privacidade') }}">Política de Privacidade</a>.</p>
</div>

<div class="legal-section" id="t10">
    <h2>
        <svg data-lucide="gavel" style="width:20px;height:20px;color:#003049;flex-shrink:0;"></svg>
        10. Disposições gerais
    </h2>

    <h3>10.1 Lei aplicável e foro</h3>
    <p>Estes Termos são regidos pela legislação brasileira. Fica eleito o foro da comarca de <strong>Porto Velho, Rondônia</strong>, para dirimir quaisquer controvérsias, com renúncia a qualquer outro, por mais privilegiado que seja.</p>

    <h3>10.2 Alterações</h3>
    <p>Reservamo-nos o direito de atualizar estes Termos a qualquer momento. Alterações relevantes serão comunicadas por e-mail com antecedência mínima de 15 dias. O uso continuado do serviço após a vigência implica aceitação.</p>

    <h3>10.3 Contato</h3>
    <p>Dúvidas sobre estes Termos: <a href="mailto:contato@pageup.net.br">contato@pageup.net.br</a></p>
</div>

@endsection
