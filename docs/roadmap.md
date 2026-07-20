# NEXOSN — Roadmap de Produto
> Atualizado: 2026-07 | PageUp Sistemas

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
