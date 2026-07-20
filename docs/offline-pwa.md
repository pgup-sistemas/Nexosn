# Offline & PWA — Plano de Implementação

> NEXOSN · PageUp Sistemas · Atualizado: 2026-07

## Contexto

O perfil público `/u/{slug}` é renderizado server-side pelo Laravel. Sem internet o browser não consegue buscar o HTML, os ícones (Lucide via CDN unpkg) somem e a fonte Inter cai para o sistema. Este documento registra o plano para tornar o perfil funcional offline após a primeira visita.

---

## Diagnóstico — O que quebra sem internet

| Dependência | Efeito | Criticidade |
|---|---|---|
| `fonts.googleapis.com` — `@import` no `app.css` | Fonte cai para sistema; layout e espaçamentos mudam | 🔴 Alto |
| `unpkg.com/lucide@latest` — CDN em `layouts/card.blade.php` | Todos os ícones `<i data-lucide>` ficam invisíveis | 🔴 Alto |
| HTML da página `/u/{slug}` — SSR Laravel | Página não carrega; tela em branco | 🔴 Crítico |
| `/build/assets/app-*.css` e `app-*.js` — Vite | Sem CSS: sem estilo. Sem JS: Alpine inerte | 🔴 Crítico |
| `/storage/*` — fotos de perfil, capa, galeria | Imagens quebradas | 🟠 Médio |
| `livewire:appointment-calendar` | Agenda não renderiza | 🟠 Médio |
| `livewire:contact-form` | Formulário não renderiza/envia | 🟠 Médio |
| `GET /u/{slug}/agendar/slots` | Horários não carregam | 🟠 Médio |
| `GET /u/{slug}/contato.vcf` | Download do vCard falha | 🟠 Médio |
| `GET /u/{slug}/qr.png` e `qr.svg` | Download do QR falha | 🟡 Baixo |
| `CardView::create()` — analytics | Visualização não registrada | 🟡 Baixo |

## O que funciona offline (sem mudanças)

- CSS inline das cores dinâmicas (`--card-primary`, `--card-button`)
- HTML completo do perfil (nome, bio, cargo, links, PIX, endereço) — se cacheado
- Seções colapsáveis Alpine.js — se `app.js` cacheado
- Cópia da chave PIX (`navigator.clipboard` é local)
- Texto do endereço legível no HTML

---

## Compartilhamento sem internet

| Método | Status | Solução |
|---|---|---|
| QR Code na tela | ⚡ Implementar | SVG inline no HTML (não rota separada) |
| QR Code impresso | ✓ Já funciona | Papel não precisa de internet |
| Copiar link do perfil | ⚡ Implementar | `navigator.clipboard.writeText(url)` |
| Baixar QR PNG | ⚡ Implementar | Canvas API (sem servidor) |
| Salvar contato (vCard) | ⚡ Implementar | Gerar VCF no JS via Blob |
| Web Share API | ⚠ Limitado | Destino precisa de internet |

---

## Estratégias de Cache do Service Worker

| Rota / Recurso | Estratégia | TTL |
|---|---|---|
| `/build/assets/*` | Cache First | Permanente (hash no nome) |
| `/fonts/*` | Cache First | 1 ano |
| `/offline.html` | Cache First | Pré-cacheada na instalação |
| `/u/{slug}` | Stale-While-Revalidate | 24h |
| `/storage/*` (imagens) | Stale-While-Revalidate | 7 dias |
| `/u/{slug}/agendar/slots` | Network First | 1h fallback |
| `/dashboard/*` | Network Only | — |
| `/webhook/*` | Network Only | — |
| `/appointments/*/confirm` | Network Only | — |
| Rota desconhecida | Fallback → `/offline.html` | — |

---

## 17 Tarefas Atômicas — 4 Fases

### Fase 1 — Assets Locais (2–3h)

- [ ] **T1** Auto-hospedar Inter: baixar `.woff2` pesos 400/500/600/700/800 → `public/fonts/`; substituir `@import` Google Fonts por `@font-face` no `app.css`; remover `<link>` Google dos layouts
- [ ] **T2** Criar `resources/js/card.js` com `import { createIcons, icons } from 'lucide'`; adicionar ao `vite.config.js`; remover CDN unpkg do `layouts/card.blade.php`; atualizar `@vite()` no layout do cartão
- [ ] **T3** Separar bundle do cartão do bundle do painel (card.js enxuto, sem SortableJS)

### Fase 2 — PWA + Service Worker (3–4h)

- [ ] **T4** Criar `public/manifest.json` com metadados NEXOSN; gerar ícones PNG 192×192 e 512×512; adicionar `<link rel="manifest">` no `layouts/card.blade.php`
- [ ] **T5** Criar `public/sw.js` com Cache First / Stale-While-Revalidate / Network Only
- [ ] **T6** Criar `public/offline.html` sem dependências externas
- [ ] **T7** Registrar SW em `resources/js/card.js`
- [ ] **T8** Banner de status offline: `window.addEventListener('offline' / 'online')`

### Fase 3 — Compartilhamento Client-Side (3–4h)

- [ ] **T9** Embutir QR Code SVG inline: `QrCodeService::generateSvg($card)` passado ao Blade; renderizar com `{!! $qrSvg !!}` no `card/show.blade.php`
- [ ] **T10** Botão "Baixar QR PNG" via Canvas API (sem servidor)
- [ ] **T11** Botão "Copiar link do perfil" com `navigator.clipboard`
- [ ] **T12** vCard client-side: dados em `<script type="application/json">`; função JS gera VCF e usa `Blob + URL.createObjectURL`

### Fase 4 — Degradação Elegante (2–3h)

- [ ] **T13** Formulário de contato: detectar `navigator.onLine`; salvar em `localStorage` se offline; reenviar ao voltar online
- [ ] **T14** Agenda: cachear slots disponíveis no `localStorage` por 1h; badge "pode estar desatualizado" quando offline
- [ ] **T15** Background Sync para analytics de visualização (Chrome only)

---

## Arquivos Criados/Modificados por Fase

```
Fase 1:
  resources/css/app.css              ← @font-face Inter local
  resources/js/card.js               ← NOVO — bundle do cartão
  vite.config.js                     ← adicionar card.js ao input
  resources/views/layouts/card.blade.php   ← remover CDN Lucide
  resources/views/layouts/welcome.blade.php ← remover CDN Google Fonts
  public/fonts/inter-*.woff2         ← NOVO

Fase 2:
  public/manifest.json               ← NOVO
  public/sw.js                       ← NOVO
  public/offline.html                ← NOVO
  public/images/icon-192.png         ← NOVO
  public/images/icon-512.png         ← NOVO
  resources/views/layouts/card.blade.php   ← link manifest
  resources/js/card.js               ← registro SW + banner offline

Fase 3:
  app/Http/Controllers/CardController.php  ← $qrSvg no show()
  resources/views/card/show.blade.php      ← SVG inline, vCard JS, botões

Fase 4:
  resources/views/livewire/card/contact-form.blade.php
  resources/views/livewire/schedule/appointment-calendar.blade.php
```

---

## Limitações Aceitas

- **Agendamento real** — impossível offline; mostrar mensagem clara
- **Submit do formulário** — apenas enfileirado no localStorage; perde se o usuário limpar dados antes de reconectar
- **Links externos** (WhatsApp, Maps) — ficam visíveis mas o destino exige internet
- **Primeiro acesso** — sempre exige internet; SW só cacheia após a 1ª visita com conexão

## Compatibilidade

| Recurso | Chrome Android | Safari iOS | Samsung Internet |
|---|---|---|---|
| Service Worker + Cache | ✓ 86+ | ✓ 16.4+ | ✓ 14+ |
| Web App Manifest | ✓ | ✓ | ✓ |
| navigator.clipboard | ✓ | ✓ | ✓ |
| Canvas API (QR PNG) | ✓ | ✓ | ✓ |
| Background Sync | ✓ | ✗ | ✓ |
| beforeinstallprompt | ✓ | ✗ (próprio fluxo) | ✓ |

**Cobertura core (cache + vCard + QR inline): ~97% dos usuários mobile no Brasil.**
