# NEXOSN — Roadmap de Produto
> Atualizado: 2026-08-02 | PageUp Sistemas

---

## 🗳️ Template de Campanha — Roadmap de Implementação (2026-08)

> Ver auditoria completa em [`docs/auditoria-template-campanha.md`](./auditoria-template-campanha.md).
> Decisões confirmadas com o usuário em 2026-08-02: (1) refatorar o sistema de templates antes de
> construir o módulo de campanha; (2) agenda de campanha (eventos com local/mapa) vira tabela
> própria `campaign_events`, separada de `card_appointments` (agendamento 1:1 com o titular).

| Fase | Entrega | Status |
|---|---|---|
| A | Registro de templates (`config/card_templates.php` + `CardTemplateResolver`) + campo `template` exposto no admin. Componentização por seção fica para a Fase G, quando houver um 2º consumidor real (evita abstração prematura) | ✅ Concluído |
| B | Perfil de Campanha (`campaign_profiles` + `CampaignProfile` + `ProfileEditor` Livewire + gate `check.plan:campanha`) | ✅ Concluído |
| C | Propostas + categorias (`campaign_proposals`) + arquivos genéricos (`card_files`), com validação de MIME real de PDF e allow-list de domínio de vídeo (YouTube/Vimeo) | ✅ Concluído |
| D | Notícias (`campaign_news`) + Linha do Tempo (`campaign_timeline_items`) | ✅ Concluído |
| E | Equipe / Chapa (`campaign_team_members`) | ✅ Concluído |
| F | Agenda estendida (`campaign_events`, tabela própria — decisão R3 da auditoria) + formulário com finalidade configurável (`contact_messages.purpose`) | ✅ Concluído |
| G | 7 templates visuais de campanha completos (hero, institucional, retrato, banner, minimalista, chapa, moderno), todos sobre a mesma biblioteca de 9 componentes de seção reutilizáveis. Verificados manualmente no navegador contra MySQL real, não só via `php artisan test` (SQLite não pega bugs de tamanho de coluna) | ✅ Concluído |
| H | Admin (Filament): 6 novos Relation Managers em `CardResource` (Propostas, Notícias, Linha do Tempo, Equipe, Agenda, Arquivos) + template exposto no form. Testes automatizados cobrindo todos. QA mobile-first e checklist de deploy (`optimize`/`build`) ficam para quando você decidir fazer o deploy | 🔶 Parcial |

Cada fase termina com `php artisan test` e aprovação do usuário antes de avançar (fluxo padrão do
[`CLAUDE.md`](../CLAUDE.md) §7).

**Conformidade jurídica para uso comercial (2026-08-03)**: adicionados (1) seção 10 "Uso em
campanhas eleitorais e eleições internas" em [Termos de Uso](../resources/views/legal/termos.blade.php),
deixando explícito que o titular é responsável pelo conteúdo e que eleições oficiais devem observar
a legislação e as normas do TSE; (2) campo opcional `legal_responsible_name`/`legal_responsible_document`
em `campaign_profiles`, exibido no rodapé do cartão público quando preenchido; (3) aviso de
conformidade no editor de perfil do dashboard, com link direto para a seção dos Termos. Nesse
processo também foi corrigido um bug pré-existente em `termos.blade.php`: o arquivo continha duas
cópias completas do documento coladas em sequência (duas declarações `@section('hero')`/`@section('content')`),
das quais só a segunda realmente renderizava — a primeira era código morto, removido nesta limpeza.

**Bug encontrado e corrigido durante homologação visual (2026-08-02)**: `cards.template` era
`varchar(20)`; a chave `campaign-institucional` (22 caracteres) truncava/falhava no MySQL real, mas
passava despercebida em `php artisan test` porque a suíte roda em SQLite in-memory
(`phpunit.xml`), que não impõe limite de VARCHAR. Corrigido com a migration
`2026_08_02_000009_widen_template_column_on_cards_table.php` (coluna ampliada para `varchar(40)`) e
coberto por `tests/Feature/CardTemplateColumnLengthTest.php`, que inspeciona a config diretamente
em vez de depender do banco de teste. **Lição**: mudanças de schema que envolvem strings/enums
devem ser conferidas contra o MySQL real antes de dar como concluídas — a suíte automatizada sozinha
não é suficiente para essa classe de bug.

---

## ✅ Entregues nesta sprint (2026-07-20)

### Galeria com Lightbox + Slideshow
**Arquivo:** `resources/views/card/show.blade.php`
- Grid 3 colunas com cursor zoom-in nas fotos
- Lightbox fullscreen com overlay escuro ao clicar
- Navegação por setas prev/next + dots indicadores de posição
- Swipe touch (deslizar no celular)
- Teclado: ← → para navegar, Esc para fechar
- Caption da foto exibido abaixo
- Contador "X / Y" no topo
- Transição suave de opacidade entre fotos

### Analytics Avançado
**Arquivos:**
- `database/migrations/2026_07_20_225339_add_click_count_to_card_links_table.php`
- `database/migrations/2026_07_20_225340_add_source_to_card_views_table.php`
- `app/Http/Controllers/CardController.php` — método `detectSource()` e `trackClick()`
- `app/Livewire/Dashboard/Overview.php` — gráfico 30 dias, origem, top links
- `resources/views/livewire/dashboard/overview.blade.php` — UI completa

**Funcionalidades:**
- Gráfico de barras: visitas nos últimos 30 dias (agrupado por dia)
- Origem do tráfego: direto / WhatsApp / Instagram / Google / Facebook / LinkedIn / TikTok / Telegram / Outros
- Ranking de clicks por link (barra horizontal proporcional)
- Rota `/u/{slug}/link/{linkId}` → incrementa `click_count` → redireciona para URL

### Comparativo na Home
**Arquivo:** `resources/views/welcome.blade.php`
- Seção "Por que NEXOSN?" antes do FAQ
- Scorecards animados (IntersectionObserver) para NEXOSN, Linktree, Beacons, HiHello, Blinq
- Tabela comparativa com categorias: Links, Cartão, Comunicação, Agenda, Pagamentos, Analytics
- Badge "único" / "BR único" nas funcionalidades exclusivas

---

## 🔜 Próximas entregas sugeridas

### P1 — Alta prioridade

| # | Funcionalidade | Impacto | Esforço |
|---|---|---|---|
| 1 | **Página dedicada de Analytics** (`/dashboard/analytics`) com filtros por período | Alto | Médio |
| 2 | **Mapa de calor** — highlight visual de quais seções do cartão são mais acessadas | Alto | Alto |
| 3 | **NFC / Passkit** — gerar Apple Wallet / Google Wallet pass do cartão | Alto | Alto |
| 4 | **Notificação push** no painel quando chega mensagem nova | Médio | Médio |
| 5 | **Multi-cartão** — usuário Pro pode ter mais de um cartão | Alto | Alto |

### P2 — Médio prazo

| # | Funcionalidade | Impacto | Esforço |
|---|---|---|---|
| 6 | **Temas visuais** — 5 templates prontos para o cartão público | Alto | Médio |
| 7 | **Integração com Google Calendar** para confirmação automática de agendamentos | Médio | Alto |
| 8 | **Link com contador regressivo** (promoção, evento) | Médio | Baixo |
| 9 | **Botão "Indicar amigo"** com cupom de desconto | Médio | Médio |
| 10 | **Exportação de leads** (CSV) das mensagens e agendamentos | Médio | Baixo |

### P3 — Futuro / Diferencial competitivo

| # | Funcionalidade |
|---|---|
| 11 | **IA para bio** — geração automática de bio profissional |
| 12 | **NEXOSN Business** — cartão corporativo com múltiplos colaboradores |
| 13 | **Pixel de rastreamento** — integração Facebook Pixel / GTM |
| 14 | **Agendamento recorrente** — eventos fixos na agenda |
| 15 | **Loja simples** — produtos/serviços com link de pagamento PIX |

---

## 🏗️ Arquitetura das funcionalidades implementadas

### Fluxo de click em link
```
Visitante clica no link
    → GET /u/{slug}/link/{linkId}
    → CardController@trackClick
    → card_links.click_count++
    → redirect()->away($link->url)
```

### Fluxo de detecção de origem
```
Visitante acessa /u/{slug}
    → CardController@show
    → detectSource($referer)
        → analisa HTTP Referer
        → retorna: direct | whatsapp | instagram | google | facebook | linkedin | tiktok | telegram | outros
    → card_views.create(['source' => ...])
```

### Fluxo do lightbox
```
Usuário clica em foto da galeria
    → nexosnGallery.open(index)
    → Lightbox fullscreen visível
    → Navegação: prev/next/dots/swipe/teclado
    → Esc ou click no overlay fecha
```

---

## 📋 Banco de dados — colunas adicionadas

```sql
ALTER TABLE card_links ADD COLUMN click_count BIGINT UNSIGNED DEFAULT 0;
ALTER TABLE card_views ADD COLUMN source VARCHAR(40) DEFAULT 'direct';
```

---

*NEXOSN Roadmap · PageUp Sistemas · Porto Velho, RO*
