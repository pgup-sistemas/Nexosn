# Card — Documento de Requisitos v1.1
> Cartão Digital SaaS · PageUp Sistemas · Porto Velho, RO
> Data: 2026-07-09 · Atualizado: 2026-07-09 · Status: APROVADO PARA IMPLEMENTAÇÃO

---

## 1. Visão Geral do Produto

**Card** é um SaaS multi-tenant de cartão de visita digital, onde qualquer pessoa cria um cartão público personalizado acessível via link e QR Code. O titular gerencia seu cartão via painel privado. A receita do SaaS vem de assinatura (Free + Pro). PIX é uma funcionalidade do cartão do usuário, não do SaaS.

### 1.1 Posicionamento
- **Categoria:** Link-in-bio + cartão de visita digital com agenda nativa
- **Público-alvo:** Profissionais autônomos, prestadores de serviço, pequenos negócios
- **Idioma:** Português BR
- **Gateway SaaS:** Efi Bank (ex-Gerencianet) — integração existente
- **URL pública:** `dominio.com/u/{slug}`
- **Diferencial:** agenda de agendamentos nativa (sem Calendly externo) + identidade visual personalizável

---

## 2. Atores do Sistema

| Ator | Descrição |
|---|---|
| **Visitante** | Acessa o cartão público e pode solicitar agendamento |
| **Usuário/Titular** | Dono do cartão — cria conta, configura, gerencia e confirma agendamentos |
| **Admin SaaS** | Equipe PageUp — gerencia planos, usuários, métricas |

---

## 3. Módulos e Requisitos Funcionais

### M-01 · Autenticação e Conta (Laravel Breeze nativo)

| ID | Requisito | Plano |
|---|---|---|
| A-01 | Cadastro com e-mail + senha | Free/Pro |
| A-02 | Login, logout, "lembrar-me" | Free/Pro |
| A-03 | Recuperação de senha por e-mail | Free/Pro |
| A-04 | Verificação de e-mail obrigatória | Free/Pro |
| A-05 | Edição de dados da conta (nome, e-mail, senha) | Free/Pro |
| A-06 | Exclusão de conta com apagamento de dados (LGPD Art. 18) | Free/Pro |
| A-07 | OAuth Google/Facebook | Fase 2 |

---

### M-02 · Cartão Digital — Perfil Público

| ID | Requisito | Plano |
|---|---|---|
| C-01 | Foto de perfil com upload e recorte (crop circular) | Free/Pro |
| C-02 | Foto de capa/banner — ocupa 100% da largura como background do header | Free/Pro |
| C-03 | Avatar sobrepõe a borda inferior da capa com borda branca (3px) | Free/Pro |
| C-04 | Logotipo/logomarca separado da foto de perfil | Pro |
| C-05 | Nome completo, título/cargo, empresa/negócio | Free/Pro |
| C-06 | Texto "Sobre" — bio com rich text simples (negrito, itálico, quebra de linha) | Free/Pro |
| C-07 | Galeria de fotos com thumbnails | Free: até 3 · Pro: até 30 |
| C-08 | Cor primária personalizável (header, fundo) via color picker | Pro |
| C-09 | Cor de botão personalizável (CTAs) via color picker independente | Pro |
| C-10 | Preview ao vivo das cores no painel enquanto o titular edita | Pro |
| C-11 | Slug personalizado (`/u/meu-nome`) | Free/Pro |
| C-12 | Ativar/desativar cartão sem excluir | Free/Pro |
| C-13 | Marca d'água "Criado com Card" no rodapé | Free (removível no Pro) |
| C-14 | Localização — endereço exibido como botão tappable que abre Google Maps | Free/Pro |
| C-15 | Compartilhar localização — Web Share API no mobile, fallback copia link Google Maps | Free/Pro |

---

### M-03 · Links e Redes Sociais

| ID | Requisito | Plano |
|---|---|---|
| L-01 | Links de redes sociais com ícone Lucide automático detectado pela URL (Instagram, WhatsApp, LinkedIn, TikTok, YouTube, X/Twitter, Facebook, Telegram, Pinterest, Spotify) | Free/Pro |
| L-02 | Links customizados (label livre + URL) | Free: até 5 · Pro: ilimitado |
| L-03 | Link PIX — chave PIX com botão "Pagar via PIX" | Free/Pro |
| L-04 | QR Code PIX gerado dinamicamente (padrão EMV/BR Code) | Free/Pro |
| L-05 | Link de agendamento externo (Calendly, Google Agenda, Cal.com) | Free/Pro |
| L-06 | Ordenação drag-and-drop dos links no painel | Free/Pro |
| L-07 | Ativar/desativar link individualmente sem excluir | Free/Pro |

---

### M-04 · Contatos

| ID | Requisito | Plano |
|---|---|---|
| CT-01 | Telefone(s) com botão de ligação e WhatsApp direto | Free/Pro |
| CT-02 | E-mail de contato público (com mailto:) | Free/Pro |
| CT-03 | Endereço com link Google Maps | Free/Pro |
| CT-04 | Site/portfólio externo | Free/Pro |
| CT-05 | Botão "Salvar Contato" — gera e baixa arquivo .vcf (vCard 3.0) | Free/Pro |

---

### M-05 · Formulário de Contato

| ID | Requisito | Plano |
|---|---|---|
| F-01 | Formulário embutido no cartão (nome, e-mail, telefone opcional, mensagem) | Free/Pro |
| F-02 | Envio por e-mail para o titular via Resend | Free/Pro |
| F-03 | Anti-spam: honeypot + rate limiting (throttle Laravel) | Free/Pro |
| F-04 | Confirmação visual/toast ao visitante após envio | Free/Pro |
| F-05 | Histórico de mensagens recebidas no painel | Pro |

---

### M-06 · Compartilhamento e QR Code

| ID | Requisito | Plano |
|---|---|---|
| S-01 | QR Code do cartão gerado automaticamente | Free/Pro |
| S-02 | Download do QR Code em PNG e SVG | Free/Pro |
| S-03 | Botão "Copiar link" do cartão | Free/Pro |
| S-04 | Open Graph completo: título, descrição, thumbnail para preview no WhatsApp/redes | Free/Pro |
| S-05 | Meta tags SEO (title, description, canonical) | Free/Pro |

---

### M-07 · Painel do Usuário (Blade + Livewire — mobile-first)

| ID | Requisito | Plano |
|---|---|---|
| P-01 | Dashboard com total de visualizações do cartão | Free/Pro |
| P-02 | Editor do cartão — todos os campos dos módulos acima | Free/Pro |
| P-03 | Preview ao vivo do cartão enquanto edita (incluindo cores) | Free/Pro |
| P-04 | Gestão de assinatura — plano atual, vencimento, upgrade | Free/Pro |
| P-05 | Histórico de pagamentos/faturas | Free/Pro |
| P-06 | Botão de compartilhamento com QR Code | Free/Pro |

---

### M-08 · Planos e Billing

| ID | Requisito |
|---|---|
| B-01 | 2 planos no lançamento: **Free** e **Pro** |
| B-02 | Trial de 14 dias do Pro sem cartão obrigatório |
| B-03 | Cobrança via Efi Bank: PIX, boleto e cartão de crédito |
| B-04 | Recorrência mensal e anual (desconto anual) |
| B-05 | Webhook Efi Bank para ativar/suspender plano automaticamente |
| B-06 | E-mail de aviso de vencimento (3 dias antes, no dia, 3 dias depois) |
| B-07 | Downgrade automático para Free após inadimplência (sem apagar dados) |

#### Limites por plano

| Feature | Free | Pro |
|---|---|---|
| Links customizados | 5 | Ilimitado |
| Fotos na galeria | 3 | 30 |
| Logomarca | ✗ | ✅ |
| Cores de marca (2 pickers) | ✗ cores fixas Card | ✅ |
| Agenda de agendamentos | ✗ | ✅ |
| Histórico de mensagens | ✗ | ✅ |
| Analytics detalhado | ✗ | v1.1 |
| Marca d'água | ✅ obrigatório | ✗ removida |
| PIX no cartão | ✅ | ✅ |
| vCard / QR Code | ✅ | ✅ |
| Formulário de contato | ✅ | ✅ |

---

### M-09 · Admin SaaS (Filament 3)

| ID | Requisito |
|---|---|
| ADM-01 | Listagem, busca e filtro de usuários |
| ADM-02 | Ver, editar e suspender cartões |
| ADM-03 | Gestão de planos e preços (CRUD) |
| ADM-04 | Métricas: usuários ativos, MRR, cartões criados, conversão Free→Pro |
| ADM-05 | Impersonar usuário (login como) para suporte |
| ADM-06 | Logs de acesso e auditoria básica |

---

### M-10 · Agenda e Agendamentos (Pro) ← NOVO

#### Configuração pelo titular (painel)

| ID | Requisito |
|---|---|
| AG-01 | Criar serviço com nome e duração do slot (ex: "Consultoria — 1h") |
| AG-02 | Definir dias da semana disponíveis (checkboxes: Seg a Dom) |
| AG-03 | Definir faixas de horário por dia (ex: 09:00–12:00, 14:00–18:00) |
| AG-04 | Slots gerados automaticamente pela duração configurada |
| AG-05 | Ativar/desativar agenda sem excluir configuração |
| AG-06 | Visualizar lista de agendamentos recebidos com status (pendente, confirmado, recusado) |
| AG-07 | Confirmar ou recusar agendamento pelo painel |
| AG-08 | Link rápido de confirmação/recusa direto no e-mail de notificação |

#### Experiência do visitante (cartão público)

| ID | Requisito |
|---|---|
| AG-09 | Bloco "Agendar horário" visível no cartão público quando agenda está ativa |
| AG-10 | Calendário visual com dias disponíveis destacados |
| AG-11 | Dias sem disponibilidade exibidos como indisponíveis (cinza) |
| AG-12 | Ao selecionar dia, exibe slots de horário disponíveis |
| AG-13 | Slots já confirmados ficam bloqueados e não aparecem para novos visitantes |
| AG-14 | Formulário de solicitação: nome, e-mail, telefone/WhatsApp e observação opcional |
| AG-15 | Confirmação visual/toast após envio da solicitação |
| AG-16 | Visitante recebe e-mail com dados da solicitação e status |

#### Notificações (Resend)

| ID | Requisito |
|---|---|
| AG-17 | Titular recebe e-mail ao receber nova solicitação (com link confirm/recusar) |
| AG-18 | Visitante recebe e-mail de confirmação com data, hora e contato do titular |
| AG-19 | Visitante recebe e-mail de recusa com mensagem padrão |
| AG-20 | Slot é desbloqueado automaticamente se recusado (fica disponível novamente) |

#### Fase 2 (v1.1)
- Confirmação automática sem aprovação manual
- Integração com Google Calendar (link "Adicionar ao calendário" no e-mail)
- Cancelamento pelo visitante via link no e-mail de confirmação

---

## 4. Requisitos Não Funcionais

| ID | Requisito |
|---|---|
| NF-01 | Mobile-first: cartão público e painel otimizados para telas < 420px |
| NF-02 | Tempo de carregamento do cartão público < 2s (lazy load de imagens) |
| NF-03 | LGPD: política de privacidade, consentimento de cookies, exclusão de dados |
| NF-04 | HTTPS obrigatório em produção |
| NF-05 | Upload de imagens com validação de tipo e tamanho (max 5MB) |
| NF-06 | Slug único globalmente (validação no cadastro e edição) |
| NF-07 | Compatível com servidor compartilhado (sem Docker, sem wildcard obrigatório) |
| NF-08 | Backups automáticos via rotina cron |
| NF-09 | Valores HEX de cores personalizadas validados no backend antes de persistir |
| NF-10 | Tokens de confirmação/recusa de agendamento com expiração de 7 dias |

---

## 5. Stack Técnica Definida

| Camada | Tecnologia |
|---|---|
| **Backend** | PHP 8.2+ · Laravel 11 |
| **Admin SaaS** | Filament 3 |
| **Frontend público + painel** | Blade + Livewire 3 + Alpine.js |
| **CSS** | Tailwind CSS (mobile-first) |
| **Banco de dados** | MySQL 8 |
| **Fila/Jobs** | Laravel Queue (driver: database) |
| **E-mail** | Resend (API) |
| **Upload/Storage** | Laravel Storage local → S3 na v1.2 |
| **Pagamentos SaaS** | Efi Bank SDK |
| **QR Code** | `simplesoftwareio/simple-qrcode` |
| **Crop de imagem** | Cropper.js (front) + Intervention Image (back) |
| **Ícones** | Lucide (CDN + NPM) |
| **Tipografia** | Inter (Google Fonts) |
| **Slug** | `spatie/laravel-sluggable` |
| **Permissões/Planos** | `spatie/laravel-permission` + lógica de plano própria |

---

## 6. Arquitetura de Rotas

```
/                               Landing page
/register                       Cadastro
/login                          Login
/forgot-password                Recuperação de senha
/email/verify                   Verificação de e-mail

/dashboard                      Painel — visão geral
/dashboard/card                 Editor do cartão
/dashboard/links                Gerenciar links
/dashboard/photos               Gerenciar galeria
/dashboard/contacts             Gerenciar contatos
/dashboard/schedule             Configurar disponibilidade da agenda     ← NOVO
/dashboard/appointments         Gerenciar agendamentos recebidos         ← NOVO
/dashboard/messages             Histórico de mensagens (Pro)
/dashboard/plan                 Assinatura e planos
/dashboard/settings             Configurações da conta

/u/{slug}                       Cartão público
/u/{slug}/contact               POST — formulário de contato
/u/{slug}/vcf                   Download vCard
/u/{slug}/agendar               GET  — tela de agendamento              ← NOVO
/u/{slug}/agendar               POST — submissão da solicitação         ← NOVO
/u/{slug}/agendar/slots         GET  — slots disponíveis (JSON)         ← NOVO

/appointments/{token}/confirm   GET — link de confirmação pelo titular  ← NOVO
/appointments/{token}/refuse    GET — link de recusa pelo titular       ← NOVO

/admin/*                        Filament 3 — painel SaaS admin
```

---

## 7. Modelo de Dados

```
users
  id, name, email, password, email_verified_at,
  plan (enum: free|pro), plan_expires_at, trial_ends_at,
  efi_subscription_id, created_at, updated_at

cards
  id, user_id (FK), slug (unique), is_active (bool),
  display_name, title, company, bio,
  profile_photo, cover_photo, logo,
  brand_color_primary (nullable, hex),   ← NOVO
  brand_color_button  (nullable, hex),   ← NOVO
  show_watermark (bool),
  contact_email, contact_phone, address, website,
  created_at, updated_at

card_links
  id, card_id (FK), type (enum: social|custom|pix|schedule),
  label, url, icon, is_active (bool), sort_order (int)

card_photos
  id, card_id (FK), path, thumbnail_path, alt, sort_order, created_at

contact_messages
  id, card_id (FK), sender_name, sender_email, sender_phone,
  message, read_at (nullable), created_at

card_views
  id, card_id (FK), ip_hash, user_agent, viewed_at

card_schedules                            ← NOVO
  id, card_id (FK), service_name,
  duration_minutes (int), is_active (bool),
  created_at, updated_at

card_schedule_slots                       ← NOVO
  id, card_schedule_id (FK),
  day_of_week (enum: mon|tue|wed|thu|fri|sat|sun),
  start_time (time), end_time (time)

card_appointments                         ← NOVO
  id, card_id (FK), card_schedule_id (FK),
  appointment_date (date), appointment_time (time),
  visitor_name, visitor_email, visitor_phone,
  notes (nullable),
  status (enum: pending|confirmed|refused|cancelled),
  confirmation_token (unique, string),
  confirmed_at (nullable), refused_at (nullable),
  created_at, updated_at
```

---

## 8. Fases de Entrega

| Fase | Escopo | Status |
|---|---|---|
| **MVP v1.0** | Auth · Header com foto de capa · Cartão completo · Links · Contatos · QR Code · vCard · Formulário · Agenda com confirmação manual · Cores de marca 2 pickers (Pro) · Free+Pro · Efi Bank · Admin básico | A iniciar |
| **v1.1** | Agenda: confirmação automática + Google Calendar · Analytics completo · Histórico de mensagens · Múltiplos cartões (Pro) | — |
| **v1.2** | OAuth Google · Subdomínio próprio (Pro) · Storage S3 · Exportação de leads | — |
| **v2.0** | PWA / app mobile · Marketplace de temas | — |

---

## 9. Changelog

| Versão | Data | Alterações |
|---|---|---|
| v1.0 | 2026-07-09 | Documento inicial |
| v1.1 | 2026-07-09 | + C-02/C-03 header com foto de capa · + C-08/C-09/C-10 cores de marca 2 pickers (Pro) · + M-10 Agenda completo (AG-01 a AG-20) · Modelo de dados atualizado · Rotas atualizadas · Limites de plano atualizados |

---

*Documento gerado em 09/07/2026 · PageUp Sistemas · pageup.net.br*
