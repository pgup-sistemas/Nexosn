# Auditoria Técnica — Template de Campanha (Eleições/Chapas/Associações)
> Auditoria do projeto Card SaaS para avaliar implementação de um **template especializado**
> reutilizando a infraestrutura existente. Nenhuma regra exclusiva de política — tudo configurável.
> Gerado em 2026-08-02.

---

## 1. Resumo Executivo

O projeto Card hoje tem uma única abstração de "template": a coluna `cards.template`
(`'default'|'dark'`), que o `CardController::show` usa para escolher entre dois arquivos Blade
**monolíticos e duplicados** (`card/show.blade.php` e `card/show-dark.blade.php`, ~900-1100 linhas
cada, com CSS inline próprio). Não existe registro de templates, não existe composição por seções,
e o campo `template` nem aparece no `CardResource` do admin.

Isso significa que **o pré-requisito real para o Template de Campanha não é "adicionar mais um
template à lista" — é primeiro refatorar o sistema de 1 campo string / 2 arquivos monolíticos para
uma arquitetura de template registrável e componível por seções**. Sem essa refatoração, qualquer
novo template (campanha, imobiliário, médico, etc.) viraria um terceiro arquivo de 1000 linhas
duplicado, o que trava exatamente a extensibilidade que o requisito pede ("preparado para futuros
templates").

A boa notícia: **todo o domínio de dados já existe e é reutilizável quase sem alteração** — links,
redes sociais, agenda/agendamento, formulário de contato, galeria, QR Code, vCard, compartilhamento,
estatísticas de visualização, planos/permissões. O trabalho novo concentra-se em: (a) a refatoração
de templates, (b) um pequeno número de tabelas novas para conteúdo estruturado que não existe hoje
(propostas, linha do tempo, equipe/chapa, notícias, comitês/eventos com mapa), e (c) uma camada de
"perfil de campanha" com campos específicos (cargo, número, partido opcional, slogan) que não fazem
sentido forçar dentro da tabela `cards` genérica.

**Recomendação central**: tratar "Campanha" como um **perfil de dados adicional (1:1 com Card)** +
um **template registrável** que consome os dados existentes do Card (links, agenda, galeria,
formulário, QR) mais os dados novos do perfil de campanha — nunca como um sistema paralelo.

---

## 2. Estado Atual (fatos, não hipótese)

| Área | Estado atual |
|---|---|
| `cards.template` | string, default `'default'`, único uso: `if ($card->template === 'dark') view('card.show-dark') else view('card.show')` em `CardController::show` |
| Templates | 2 arquivos Blade monolíticos, sem partials compartilhados, sem componentização por seção |
| Admin (`CardResource`) | não expõe `template` no formulário — só slug, nome, ativo, watermark |
| Links (`CardLink`) | `type: custom\|social\|pix\|schedule`, ordenável, click_count — **reutilizável 100%** |
| Agenda (`CardSchedule`/`Slot`/`Appointment`) | 1 agenda por card, slots semanais, status pending/confirmed/refused/cancelled, token público — **reutilizável ~90%**, falta local/mapa/"como chegar" por evento |
| Formulário (`ContactMessage` + `ContactForm` Livewire) | genérico nome/email/telefone/mensagem — **reutilizável, precisa de campo de "tipo"** (voluntário/apoiador/sugestão) |
| Galeria (`CardPhoto`) | fotos com legenda e ordem — **reutilizável**; vídeo não suportado |
| QR/vCard/compartilhamento | `QrCodeService`, `VCardService` — **reutilizável 100%** |
| Serviços PIX (`CardService`) | preço + descrição + ícone — não aplicável a campanha (doações políticas fora de escopo Efi Bank atual), mas o padrão de CRUD é reaproveitável para "Propostas" |
| Planos/permissões | `CheckPlan` middleware com feature strings hardcoded (`pro`, `agenda`, `messages`) — **precisa extensão**, não suporta granularidade por template |
| Multi-tenant | 1 User → 1 Card (HasOne) — **campanha em chapa (múltiplos membros de equipe) exige nova tabela**, não múltiplos Cards |

---

## 3. Funcionalidades Reutilizadas (sem alteração de schema)

| Funcionalidade | Origem | Complexidade de integração |
|---|---|---|
| Links personalizados + redes sociais | `CardLink`, `LinkManager.php`, `SocialLinkService` | Baixa |
| QR Code (PNG/SVG) | `QrCodeService` | Baixa |
| vCard (.vcf) | `VCardService` | Baixa |
| Compartilhamento (rota `/share`) | já existe no dashboard | Baixa |
| Estatísticas de visualização | `CardView`, `RecordCardView` job | Baixa |
| Galeria de fotos | `CardPhoto`, `PhotoManager.php` | Baixa |
| Upload/crop de imagem | `ImageService` | Baixa |
| Cores dinâmicas via CSS vars | `layouts/card.blade.php`, accessors `primary_color`/`button_color` | Baixa |
| Watermark Free vs Pro | `Card::show_watermark` | Baixa |
| Autenticação, slug, trial | módulo Auth existente | Baixa |
| Middleware de plano | `CheckPlan` (adaptar, não recriar) | Média |

## 4. Funcionalidades que Precisam ser Adaptadas

| Funcionalidade | O que muda | Motivo | Complexidade |
|---|---|---|---|
| **Sistema de template** | de string+2 arquivos → registro de templates + Blade Components por seção | Pré-requisito arquitetural, ver §7 | **Alta** |
| **Agenda / `CardAppointment`** | adicionar `location`, `map_url`/lat-lng, `description` por evento (hoje agenda é só slots de horário para agendamento, não "eventos" com local) | Comitê/eventos de campanha precisam local+mapa, o que é conceitualmente diferente de "marcar horário com o titular" | Média |
| **`ContactForm` / `ContactMessage`** | adicionar `purpose` enum (`voluntario\|apoiador\|sugestao\|contato`) configurável por template | Requisito pede formulários diferenciados por finalidade, sem hardcode político | Baixa |
| **`CheckPlan` middleware** | trocar strings hardcoded por lookup em config/tabela de features, permitindo novo feature `campanha` | Extensibilidade para futuros templates sem editar o middleware a cada novo módulo | Média |
| **`CardResource` (Filament)** | expor `template` e (se aplicável) campos do perfil de campanha em admin, com form condicional | Admin hoje não vê nem o campo `template` existente | Baixa |
| **Galeria** | suportar vídeo (campo `type: photo\|video`, ou `video_url` para embed) | Requisito pede fotos E vídeos | Média |

## 5. Funcionalidades Novas (não existem hoje)

| Módulo | Descrição | Tabela nova? |
|---|---|---|
| **Perfil de Campanha** | nome de campanha, cargo, número, organização, partido (opcional), slogan, foto retrato grande separada do avatar padrão | Sim — `campaign_profiles` |
| **Propostas** | categoria, imagem, vídeo, texto, PDF | Sim — `campaign_proposals` (+ `campaign_proposal_categories`) |
| **Plano de Gestão** | documentos/PDFs anexos | Sim — reaproveita padrão de arquivo genérico `campaign_documents` (ou tabela `card_files` genérica reutilizável por outros templates futuros) |
| **Notícias** | feed de publicações com data, imagem, texto | Sim — `campaign_news` |
| **Linha do Tempo** | eventos históricos (data, título, descrição, ícone/imagem) | Sim — `campaign_timeline_items` |
| **Equipe / Chapa** | membros com foto, nome, função, ordem | Sim — `campaign_team_members` |
| **Materiais/Downloads** | arquivos para download (reaproveita `campaign_documents`/`card_files`) | Reaproveita acima |
| **Contador regressivo** | data-alvo (ex.: dia da eleição), configurável | Não — campo simples `countdown_target_at` no perfil |
| **Botão "Votar"/CTA destacado + número em destaque** | apenas configuração visual do template, sem tabela nova | Não |
| **Mapa/Localização do comitê** | endereço + lat/lng do comitê central | Reaproveita `city/state/zip_code/country` do Card + novo `campaign_profiles.hq_address/lat/lng`, ou reaproveita eventos da agenda estendida |

**Princípio de design obrigatório**: nenhuma dessas tabelas/campos deve ter nome ou enum que assuma
política. Usar nomenclatura neutra: `campaign_profiles` não `political_profiles`; campo `party`
(nullable) não `partido_politico`; `campaign_proposals` serve igualmente para "pauta reivindicativa"
de sindicato ou "programa de gestão" de uma chapa de condomínio.

---

## 6. Estrutura de Banco de Dados

### Novas tabelas

```
campaign_profiles
  id, card_id (FK unique → cards, 1:1)
  campaign_name, role_title, ballot_number, organization_name,
  affiliation (nullable, ex: partido/sindicato/chapa),
  slogan, portrait_photo, countdown_target_at (nullable datetime),
  hq_address, hq_lat, hq_lng (nullable),
  timestamps

campaign_proposal_categories
  id, card_id (FK), name, order, timestamps

campaign_proposals
  id, card_id (FK), category_id (FK nullable),
  title, description, image, video_url, pdf_path,
  order, is_active, timestamps

campaign_news
  id, card_id (FK), title, body, cover_image,
  published_at, is_active, order, timestamps

campaign_timeline_items
  id, card_id (FK), occurred_on (date), title, description,
  icon, image, order, timestamps

campaign_team_members
  id, card_id (FK), name, role, photo, order, is_active, timestamps

card_files   -- genérica, reutilizável por qualquer template futuro (não só campanha)
  id, card_id (FK), category (enum: management_plan|material|other),
  label, file_path, file_type, order, timestamps
```

### Alterações em tabelas existentes

```sql
-- card_appointments (agenda como "eventos" além de agendamento 1:1)
ALTER TABLE card_appointments ADD COLUMN location VARCHAR(255) NULL;
ALTER TABLE card_appointments ADD COLUMN map_url VARCHAR(255) NULL;
ALTER TABLE card_appointments ADD COLUMN description TEXT NULL;
-- (avaliar se cabe aqui ou se "eventos de campanha" merece tabela própria
--  campaign_events, ver §7 nota de risco)

-- contact_messages
ALTER TABLE contact_messages ADD COLUMN purpose VARCHAR(30) NULL; -- voluntario|apoiador|sugestao|contato

-- card_photos
ALTER TABLE card_photos ADD COLUMN media_type VARCHAR(10) DEFAULT 'photo'; -- photo|video
ALTER TABLE card_photos ADD COLUMN video_url VARCHAR(255) NULL;

-- cards (nenhuma nova coluna necessária — template continua sendo o seletor,
--  mas seu domínio de valores passa a vir de um registro de templates, não de enum fixo)
```

Índices: `campaign_proposals(card_id, order)`, `campaign_news(card_id, published_at)`,
`campaign_timeline_items(card_id, occurred_on)`, `card_files(card_id, category)` — todos seguindo o
padrão já usado em `contact_messages` (`index(['card_id','created_at'])`).

**Nota de risco de modelagem**: usar `card_appointments` para "eventos de campanha com mapa" mistura
dois conceitos (agendamento 1:1 com o titular vs. evento público de agenda de campanha/comitê). Ver
recomendação de decisão no §14.

---

## 7. Estrutura Backend

### 7.1 Refatoração pré-requisito: sistema de templates (fazer ANTES do módulo campanha)

Estado atual → estado alvo:

```
ANTES:
  cards.template = 'default' | 'dark'
  CardController::show() → view('card.show') | view('card.show-dark')
  card/show.blade.php        (tudo hardcoded, 1000+ linhas)
  card/show-dark.blade.php   (cópia quase idêntica)

DEPOIS:
  config/card_templates.php  → registro: chave, label, classe de dados exigidos,
                                lista de seções suportadas, plano mínimo exigido
  app/Services/CardTemplateResolver.php → resolve template + valida se card tem
                                perfil de dados necessário (ex: campanha exige campaign_profiles)
  resources/views/card/templates/{template}/show.blade.php → view fina que só
                                monta a ordem de <x-card.section.*> components
  resources/views/components/card/section/*.blade.php → um componente Blade por
                                seção (header, links, gallery, schedule, contact-form,
                                proposals, news, timeline, team, files, countdown, map)
                                — cada seção já existente (links, gallery, agenda,
                                formulário) vira componente reutilizado por QUALQUER
                                template, não só campanha
```

Isso resolve dois problemas de uma vez: elimina a duplicação atual (`show.blade.php` vs
`show-dark.blade.php` deixam de ser cópias, viram 2 registros de template usando os mesmos
componentes de seção) e cria o ponto de extensão pedido no item 15 ("preparado para futuros
templates").

Arquivos a **criar**:
```
config/card_templates.php
app/Services/CardTemplateResolver.php
app/View/Components/Card/Section/*.php  (Header, LinksList, Gallery, ScheduleBooking,
                                          ContactForm, Proposals, News, Timeline,
                                          Team, Files, Countdown, MapLocation)
resources/views/components/card/section/*.blade.php
resources/views/card/templates/default/show.blade.php
resources/views/card/templates/dark/show.blade.php
resources/views/card/templates/campaign-hero/show.blade.php
resources/views/card/templates/campaign-institutional/show.blade.php
... (uma view fina por layout de campanha: hero, retrato, banner, institucional,
     minimalista, chapa, moderno — todas consumindo os MESMOS componentes de seção,
     apenas reordenando/reestilizando)
```

Arquivos a **modificar**:
```
app/Http/Controllers/CardController.php   → trocar branch if/else por
                                             CardTemplateResolver::resolve($card)
app/Models/Card.php                        → cast/accessor para template continua,
                                             + relação campaignProfile()
app/Filament/Resources/CardResource.php    → expor campo template (select) no form
docs/arquitetura.md, docs/design-system.md → documentar novo sistema de templates
```

### 7.2 Módulo Campanha em si

Models novos: `CampaignProfile`, `CampaignProposal`, `CampaignProposalCategory`,
`CampaignNews`, `CampaignTimelineItem`, `CampaignTeamMember`, `CardFile`.
Todos seguem o padrão já estabelecido por `CardPhoto`/`CardLink` (fillable explícito, cast bool,
`belongsTo(Card::class)`, scope `active()`/`ordered()`).

Services novos:
```
app/Services/CampaignService.php     — orquestra criação/edição do perfil de campanha,
                                        valida partido/afiliação opcional, calcula contagem
                                        regressiva
```
Nenhum service novo é necessário para propostas/notícias/timeline/equipe — CRUD simples via
Livewire + Form Request, seguindo exatamente o padrão de `PhotoManager.php`/`ServiceManager.php`.

Controllers: nenhum controller HTTP novo é necessário fora do já existente `CardController` (a
seção pública renderiza via componentes Blade lendo relações eager-loaded). Rotas de download de
PDF (`card_files`) podem reaproveitar um único `CardFileController@download` genérico.

Livewire novos (dashboard):
```
app/Livewire/Campaign/ProfileEditor.php
app/Livewire/Campaign/ProposalManager.php
app/Livewire/Campaign/NewsManager.php
app/Livewire/Campaign/TimelineManager.php
app/Livewire/Campaign/TeamManager.php
app/Livewire/Campaign/FileManager.php
```
Todos mount(Card $card), seguem exatamente o padrão de `LinkManager.php`/`PhotoManager.php`
(wire:model.live, wire:confirm em ações destrutivas, `#[Validate]`).

Form Requests novos: `StoreCampaignProfileRequest`, `StoreCampaignProposalRequest`,
`StoreCampaignNewsRequest`, `StoreTimelineItemRequest`, `StoreTeamMemberRequest` — todos seguindo
padrão de `StoreCardRequest`.

### 7.3 Rotas

```php
// routes/web.php — dentro do grupo auth já existente
Route::prefix('painel')->middleware(['auth', 'plan:campanha'])->group(function () {
    Route::get('/campanha/perfil', ...)->name('dashboard.campaign.profile');
    Route::get('/campanha/propostas', ...)->name('dashboard.campaign.proposals');
    Route::get('/campanha/noticias', ...)->name('dashboard.campaign.news');
    Route::get('/campanha/linha-do-tempo', ...)->name('dashboard.campaign.timeline');
    Route::get('/campanha/equipe', ...)->name('dashboard.campaign.team');
    Route::get('/campanha/arquivos', ...)->name('dashboard.campaign.files');
});
Route::get('/arquivo/{file}/download', [CardFileController::class,'download'])->name('card.file.download');
```
`plan:campanha` exige que `CheckPlan` pare de ter a lista de features hardcoded e passe a ler de
`config/plan_features.php` (pequena refatoração, ver §4).

---

## 8. Estrutura Frontend

Novos Blade components (`resources/views/components/card/section/`):
`header.blade.php` (retrato grande + capa + slogan + número + contador regressivo),
`proposals.blade.php`, `news.blade.php`, `timeline.blade.php`, `team.blade.php`,
`files.blade.php`, `map-location.blade.php`, `countdown.blade.php`.

Componentes **reaproveitados sem alteração**: `links.blade.php`, `gallery.blade.php` (após suporte
a vídeo), `schedule-booking.blade.php`, `contact-form.blade.php`, `x-icon` (Lucide), QR/share modal.

7 templates visuais (hero, retrato, banner, institucional, minimalista, chapa, moderno) = 7 arquivos
`show.blade.php` **finos** (montagem + ordem de seções + classes de layout), não 7 cópias completas.
Isso é o ganho direto da refatoração do §7.1.

CSS: todas as cores de marca continuam via `var(--card-primary)`/`var(--card-button)` — nenhuma
mudança na regra do design-system. Layouts diferentes = diferença de grid/ordem/tipografia via
Tailwind, nunca cor hardcoded.

JS: nenhuma lib nova necessária. Contador regressivo = pequeno Alpine.js component inline (padrão já
usado no projeto). Mapa = link "Como chegar" (`https://maps.google.com/?q=lat,lng`), sem SDK de mapa
embarcado — evita custo de API key de Maps e mantém consistente com "Nunca use subdomínio
wildcard"-style de simplicidade do MVP.

---

## 9. Painel Administrativo (Filament)

Telas a criar/alterar:
- `CardResource`: adicionar campo `template` (select, populado a partir de `config('card_templates')`) — hoje ausente.
- Nova `RelationManager` opcional em `CardResource` para visualizar `CampaignProfile` (somente leitura, para suporte ao cliente) — não precisa CRUD completo no admin, pois é auto-gerenciado pelo titular no dashboard.
- Nenhum novo `Resource` de topo é estritamente necessário — os dados de campanha vivem por-card, não por-tenant, então cabem como Relation Managers dentro de `CardResource` se o time de suporte precisar visualizar.

---

## 10. Componentes Compartilhados (evitar duplicação)

| Componente | Reuso |
|---|---|
| `<x-icon>` (Lucide) | todos os templates, todas as seções novas |
| `layouts/card.blade.php` (CSS vars) | todos os templates |
| `ImageService` | upload de retrato, capa, fotos de propostas/notícias/equipe |
| Padrão Livewire CRUD (`LinkManager`, `PhotoManager`, `ServiceManager`) | modelo replicado por `ProposalManager`, `NewsManager`, `TimelineManager`, `TeamManager` |
| `QrCodeService`, `VCardService`, `SocialLinkService` | inalterados |
| `CheckPlan` (após refatoração de config) | gate de features `campanha`, `agenda`, `pro` de forma extensível |
| `CardTemplateResolver` (novo) | ponto único de extensão para **qualquer** template futuro (imobiliário, médico, jurídico) |
| `card_files` (nova tabela genérica) | reutilizável por templates futuros que precisem de anexos, não exclusiva de campanha |

---

## 11. Lista de Arquivos a Modificar

```
app/Http/Controllers/CardController.php
app/Models/Card.php
app/Filament/Resources/CardResource.php
app/Http/Middleware/CheckPlan.php
app/Livewire/Card/ContactForm.php
app/Models/CardPhoto.php               (media_type/video_url)
app/Models/CardAppointment.php         (location/map_url/description, se optar por reaproveitar)
database/migrations/2026_07_18_185424_create_cards_table.php  -- NÃO editar; usar nova migration
docs/arquitetura.md
docs/design-system.md
docs/tasks.md
routes/web.php
```

## 12. Novos Arquivos a Criar (resumo — lista completa nas seções 7-9)

```
config/card_templates.php
config/plan_features.php
app/Services/CardTemplateResolver.php
app/Services/CampaignService.php
app/Models/CampaignProfile.php
app/Models/CampaignProposal.php
app/Models/CampaignProposalCategory.php
app/Models/CampaignNews.php
app/Models/CampaignTimelineItem.php
app/Models/CampaignTeamMember.php
app/Models/CardFile.php
app/Http/Controllers/CardFileController.php
app/Http/Requests/StoreCampaignProfileRequest.php (+ demais Store*Request)
app/Livewire/Campaign/{ProfileEditor,ProposalManager,NewsManager,TimelineManager,TeamManager,FileManager}.php
app/View/Components/Card/Section/*.php  (12 componentes)
resources/views/components/card/section/*.blade.php (12 arquivos)
resources/views/card/templates/{default,dark,campaign-hero,campaign-retrato,campaign-banner,
  campaign-institucional,campaign-minimalista,campaign-chapa,campaign-moderno}/show.blade.php
resources/views/livewire/campaign/*.blade.php
database/migrations/*_create_campaign_profiles_table.php (+ 6 demais)
tests/Feature/Campaign/*Test.php
```

---

## 13. Fluxograma da Implementação

```
┌─────────────────────────────┐
│ FASE A — Refatoração base    │
│ Sistema de templates          │
│ registrável + componentização │
└──────────────┬────────────────┘
               │
               ▼
┌─────────────────────────────┐      ┌──────────────────────────┐
│ FASE B — Perfil de Campanha  │─────▶│ FASE C — Propostas +      │
│ (campaign_profiles + editor) │      │ Plano de Gestão (arquivos)│
└──────────────┬────────────────┘      └─────────────┬─────────────┘
               │                                       │
               ▼                                       ▼
┌─────────────────────────────┐      ┌──────────────────────────┐
│ FASE D — Notícias + Timeline │      │ FASE E — Equipe/Chapa      │
└──────────────┬────────────────┘      └─────────────┬─────────────┘
               │                                       │
               └───────────────────┬───────────────────┘
                                    ▼
               ┌─────────────────────────────────┐
               │ FASE F — Agenda estendida         │
               │ (local/mapa/"como chegar")        │
               │ + Formulário com "purpose"         │
               └────────────────┬────────────────────┘
                                 ▼
               ┌─────────────────────────────────┐
               │ FASE G — 7 templates visuais       │
               │ (hero, retrato, banner,            │
               │ institucional, minimalista,        │
               │ chapa, moderno)                    │
               └────────────────┬────────────────────┘
                                 ▼
               ┌─────────────────────────────────┐
               │ FASE H — Admin (Filament) +         │
               │ plan gating + QA + testes           │
               └─────────────────────────────────┘
```

## 14. Ordem Ideal de Desenvolvimento (fases)

1. **Fase A — Refatoração do sistema de templates** (pré-requisito, bloqueia tudo). Sem aprovação
   explícita do usuário aqui, o resto do módulo herda a duplicação atual.
2. **Fase B — Perfil de Campanha** (`campaign_profiles`, editor Livewire, exibição no header do
   template). Entrega valor demonstrável mínimo.
3. **Fase C — Propostas + Arquivos (Plano de Gestão/Materiais)**, reaproveitando `card_files`
   genérica desde já pensada para reuso futuro.
4. **Fase D — Notícias + Linha do Tempo**.
5. **Fase E — Equipe/Chapa**.
6. **Fase F — Agenda estendida + Formulário com finalidade** — **decisão de arquitetura necessária
   antes de codar**: criar `campaign_events` separada de `card_appointments`, ou estender
   `card_appointments`? Ver risco R3 abaixo — recomendo perguntar/decidir com o usuário antes desta
   fase.
7. **Fase G — 7 templates visuais**, um a um, sobre os componentes de seção já prontos.
8. **Fase H — Admin, plan gating, testes, QA mobile-first, documentação**.

Cada fase termina com `php artisan test` e aprovação do usuário antes de avançar (conforme
CLAUDE.md §7).

---

## 15. Complexidade por Item

| Item | Complexidade |
|---|---|
| Refatoração sistema de templates (Fase A) | **Alta** |
| Perfil de Campanha (model+migration+editor) | Baixa |
| Propostas (CRUD + categorias + PDF/vídeo) | Média |
| Arquivos genéricos (`card_files`) | Baixa |
| Notícias | Baixa |
| Linha do Tempo | Baixa |
| Equipe/Chapa | Baixa |
| Agenda estendida (local/mapa) — decisão de modelagem | **Média-Alta** |
| Formulário com finalidade | Baixa |
| 7 templates visuais (design + Blade) | **Alta** (esforço de design, não técnico) |
| Contador regressivo | Baixa |
| Extensão do `CheckPlan`/config de features | Média |
| Admin Filament (expor template + relation managers) | Baixa |
| Testes automatizados de todos os módulos | Média |

---

## 16. Riscos

**R1 — Duplicação perpetuada.** Se a Fase A (refatoração) for pulada "para ir mais rápido", os 7
templates de campanha viram 7 cópias de 1000 linhas, e qualquer bug de segurança/formatação precisa
ser corrigido 9 vezes (2 atuais + 7 novos). *Mitigação:* bloquear início da Fase B até A estar
mesclada e testada.

**R2 — Vazamento de terminologia política em nomes genéricos.** Requisito exige zero regra
exclusiva de política, mas é fácil um dev nomear coluna/enum com termo político sob pressão de prazo
eleitoral. *Mitigação:* nomenclatura definida neste documento (`affiliation` não `partido`,
`campaign_profiles` genérico) deve ser revisada em code review antes do merge.

**R3 — Sobrecarga de `card_appointments`.** Agendamento 1:1 (marcar horário com o titular) e
"eventos de campanha com local/mapa" são conceitos de negócio diferentes (cardinalidade e
semântica). Misturar no mesmo model cria `nullable` excessivo e status confusos. *Mitigação:*
decisão explícita antes da Fase F — recomendo tabela nova `campaign_events` reaproveitando apenas o
padrão de UI de calendário (`AppointmentCalendar.php`), não a tabela.

**R4 — Upload de PDF/vídeo sem validação.** Propostas em PDF e vídeo (upload ou URL) são superfícies
de risco: upload malicioso disfarçado de PDF, URLs de vídeo que fazem SSRF se o backend tentar
buscar metadata. *Mitigação:* validar MIME real (não extensão) via `finfo`, limitar tamanho,
armazenar fora de `public/` com download controlado (`CardFileController`), e para vídeo aceitar
apenas embeds de domínios permitidos (YouTube/Vimeo `oembed`), nunca upload de arquivo de vídeo cru
no MVP (custo de storage/transcoding fora de escopo).

**R5 — Performance de N+1 no card público.** Com 7 seções novas (propostas, notícias, timeline,
equipe, arquivos) cada uma consultando o banco, o `CardController::show` pode virar 10+ queries.
*Mitigação:* eager-load todas as relações campaign* em um único `Card::with([...])->firstOrFail()`,
seguindo o padrão já usado para `links`/`photos`.

**R6 — Gating de plano inconsistente.** Se `campanha` virar feature Pro mas o middleware continuar
hardcoded, cada novo template futuro exige editar `CheckPlan.php` de novo. *Mitigação:* fazer a
extração para `config/plan_features.php` na Fase A, não depois.

**R7 — XSS em campos ricos (notícias, propostas em texto longo).** Se o editor permitir HTML livre
(WYSIWYG) para notícias/propostas, abre XSS refletido no card público. *Mitigação:* sanitizar com
`Purify`/`HTMLPurifier` no save, nunca confiar em `{!! !!}` sem sanitização, ou restringir a
Markdown renderizado server-side.

**R8 — CSRF/rate limit no formulário com nova finalidade.** Já mitigado pela infra existente
(`ContactForm` Livewire tem honeypot + rate limit conforme skill `form.md`) — apenas confirmar que
o novo campo `purpose` não abre bypass de validação.

---

## 17. Recomendações para Escalabilidade (preparar para futuros templates)

1. **Consolidar `CardTemplateResolver` + `config/card_templates.php` como a única fonte de verdade**
   de quais seções cada template usa e quais dados exige — isso é o alicerce que qualquer template
   futuro (imobiliário, médico, jurídico, eventos, empresarial) vai herdar sem tocar em
   `CardController`.
2. **Padronizar "perfil de domínio" como tabela 1:1 opcional com Card** (`campaign_profiles` hoje;
   `real_estate_profiles`, `medical_profiles` amanhã) em vez de inchar a tabela `cards` com dezenas
   de colunas nullable específicas de nicho — mantém `cards` enxuta e genérica.
3. **`card_files` genérica (não `campaign_files`)** desde o início, com coluna `category` livre —
   evita recriar a mesma tabela por template.
4. **Extrair `CheckPlan` para config orientado a dados** (Fase A) — cada template futuro só adiciona
   uma linha de config, não um `if` novo no middleware.
5. **Documentar o contrato de "seção de template"** (Blade component recebendo `Card $card`,
   podendo checar `$card->relationLoaded(...)`) em `docs/design-system.md`, para que qualquer
   template futuro só precise implementar as seções que fizer sentido, reusando o resto.
6. **Considerar, a médio prazo, mover a escolha de template para um catálogo com preview**, exibido
   no `CardEditor.php` do dashboard (hoje é apenas texto), o que também abre caminho comercial para
   vender "temas" como add-on futuramente — mas isso é melhoria de produto, não bloqueador deste
   módulo.

---

## 18. Decisão Pendente do Usuário

Antes de qualquer código: **aprovar a Fase A (refatoração do sistema de templates)** como
pré-requisito, e **decidir R3** (agenda: reaproveitar `card_appointments` com campos extras vs.
criar `campaign_events` dedicada). Recomendo a segunda opção por clareza de domínio, mas é uma
escolha de produto/prazo, não só técnica.
