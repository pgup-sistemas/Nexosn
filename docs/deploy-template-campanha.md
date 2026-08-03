# Pacote de Deploy — Template de Campanha
> Gerado em 2026-08-03 · commit `d1e50c3` (branch `main`, já no GitHub)
> Servidor: Locaweb (compartilhado, PHP 8.2) · sem Docker, sem Redis, fila via driver `database`

---

## 0. Antes de tudo

- [ ] Faça backup do banco de dados de produção (`mysqldump`) antes de rodar migrations.
- [ ] Confirme que o servidor está em manutenção/baixo tráfego no horário do deploy (evita corrida entre requests e migration).
- [ ] Troque as senhas SSH/FTP que foram compartilhadas em texto puro nesta conversa, assim que possível.

---

## 1. Método A — via SSH + Git (não aplicável agora — sem acesso SSH neste servidor; mantido caso mude no futuro)

Rode isso **no servidor**, dentro do diretório do projeto (ex: `~/nexosn` ou o caminho configurado no domínio):

```bash
# 1. Backup do banco antes de qualquer coisa
mysqldump -u SEU_USER -p SEU_BANCO > backup_pre_campanha_$(date +%Y%m%d_%H%M).sql

# 2. Ativar modo de manutenção (mostra página amigável em vez de erro)
php artisan down --secret="deploy-campanha-2026"

# 3. Buscar o código novo
git fetch origin
git checkout main
git pull origin main

# 4. Dependências
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# 5. Migrations — NOVAS TABELAS/COLUNAS, ver lista completa na seção 3
php artisan migrate --force

# 6. Cache de produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Link de storage (se ainda não existir)
php artisan storage:link

# 8. Sair do modo de manutenção
php artisan up
```

Se o processo de fila (`queue:work`) roda via cron/supervisor no servidor, não precisa reiniciar — o driver é `database`, sem estado em memória a perder.

---

## 2. Método B — via FTP (o que você vai usar, sem SSH / sem Git no servidor)

Caso não haja acesso SSH funcional, envie **apenas os arquivos abaixo** (lista exata do commit `d1e50c3`), preservando os caminhos relativos ao diretório raiz da aplicação. **Não** suba os arquivos soltos da raiz do seu projeto local que não fazem parte deste commit (`client_secret_*.json`, `*.p12`, scripts em `public/` como `reset-admin.php`, `rm-tmp.php` etc. — não fazem parte deste módulo e não devem ir para produção).

### Arquivos novos (criar no servidor)

```
app/Filament/Resources/CardResource/RelationManagers/CampaignEventsRelationManager.php
app/Filament/Resources/CardResource/RelationManagers/CampaignNewsRelationManager.php
app/Filament/Resources/CardResource/RelationManagers/CampaignProposalsRelationManager.php
app/Filament/Resources/CardResource/RelationManagers/CampaignTeamRelationManager.php
app/Filament/Resources/CardResource/RelationManagers/CampaignTimelineRelationManager.php
app/Filament/Resources/CardResource/RelationManagers/CardFilesRelationManager.php
app/Http/Controllers/CardFileController.php
app/Livewire/Campaign/EventManager.php
app/Livewire/Campaign/FileManager.php
app/Livewire/Campaign/NewsManager.php
app/Livewire/Campaign/ProfileEditor.php
app/Livewire/Campaign/ProposalManager.php
app/Livewire/Campaign/TeamManager.php
app/Livewire/Campaign/TimelineManager.php
app/Models/CampaignEvent.php
app/Models/CampaignNews.php
app/Models/CampaignProfile.php
app/Models/CampaignProposal.php
app/Models/CampaignProposalCategory.php
app/Models/CampaignTeamMember.php
app/Models/CampaignTimelineItem.php
app/Models/CardFile.php
app/Services/CampaignMediaService.php
app/Services/CardTemplateResolver.php
config/card_templates.php
config/plan_features.php
database/migrations/2026_08_02_000002_create_campaign_profiles_table.php
database/migrations/2026_08_02_000003_create_campaign_proposals_tables.php
database/migrations/2026_08_02_000004_create_card_files_table.php
database/migrations/2026_08_02_000005_create_campaign_news_and_timeline_tables.php
database/migrations/2026_08_02_000006_create_campaign_team_members_table.php
database/migrations/2026_08_02_000007_create_campaign_events_table.php
database/migrations/2026_08_02_000008_add_purpose_to_contact_messages_table.php
database/migrations/2026_08_02_000009_widen_template_column_on_cards_table.php
database/migrations/2026_08_03_000001_add_legal_fields_to_campaign_profiles_table.php
resources/views/card/templates/_campaign-styles.blade.php
resources/views/card/templates/campaign-banner/show.blade.php
resources/views/card/templates/campaign-chapa/show.blade.php
resources/views/card/templates/campaign-hero/show.blade.php
resources/views/card/templates/campaign-institucional/show.blade.php
resources/views/card/templates/campaign-minimalista/show.blade.php
resources/views/card/templates/campaign-moderno/show.blade.php
resources/views/card/templates/campaign-retrato/show.blade.php
resources/views/components/card/section/contact-form.blade.php
resources/views/components/card/section/events.blade.php
resources/views/components/card/section/files.blade.php
resources/views/components/card/section/header.blade.php
resources/views/components/card/section/legal-footer.blade.php
resources/views/components/card/section/links.blade.php
resources/views/components/card/section/news.blade.php
resources/views/components/card/section/proposals.blade.php
resources/views/components/card/section/team.blade.php
resources/views/components/card/section/timeline.blade.php
resources/views/dashboard/campaign/events.blade.php
resources/views/dashboard/campaign/files.blade.php
resources/views/dashboard/campaign/news.blade.php
resources/views/dashboard/campaign/profile.blade.php
resources/views/dashboard/campaign/proposals.blade.php
resources/views/dashboard/campaign/team.blade.php
resources/views/dashboard/campaign/timeline.blade.php
resources/views/livewire/campaign/event-manager.blade.php
resources/views/livewire/campaign/file-manager.blade.php
resources/views/livewire/campaign/news-manager.blade.php
resources/views/livewire/campaign/profile-editor.blade.php
resources/views/livewire/campaign/proposal-manager.blade.php
resources/views/livewire/campaign/team-manager.blade.php
resources/views/livewire/campaign/timeline-manager.blade.php
```

### Arquivos existentes (sobrescrever no servidor)

```
app/Filament/Resources/CardResource.php
app/Http/Controllers/CardController.php
app/Http/Middleware/CheckPlan.php
app/Livewire/Card/CardEditor.php
app/Livewire/Card/ContactForm.php
app/Models/Card.php
app/Models/ContactMessage.php
resources/views/legal/termos.blade.php
resources/views/livewire/card/card-editor.blade.php
resources/views/livewire/card/contact-form.blade.php
routes/web.php
```

> Os arquivos em `tests/` e `docs/` **não precisam ir para produção** — só interessam ao repositório/CI.

### Depois de subir os arquivos por FTP (sem SSH)

Sem terminal, use o script de uso único `deploy-scripts/migrate-campanha-deploy.php` (gerado
junto com este guia, **não está no Git** por conter um token):

**Estrutura confirmada deste servidor**: `public_html/nexosn/` é a raiz do Laravel (tem o
`artisan`); `public_html/nexosn/public/` é a pasta pública que o domínio realmente serve.

1. Envie **só esse arquivo** por FTP para `public_html/nexosn/public/deploy-scripts/migrate-campanha-deploy.php`
   (crie a pasta `deploy-scripts` dentro de `public` se não existir).
2. Acesse pelo navegador: `https://SEU_DOMINIO/deploy-scripts/migrate-campanha-deploy.php?token=<o token dentro do arquivo>`
3. Leia a saída na tela — deve terminar em `=== TUDO OK ===`.
4. **Apague o arquivo (e a pasta `deploy-scripts`) do servidor via FTP imediatamente depois.** Ele
   roda migrations e libera quem souber a URL a rodar comandos no seu banco — não pode ficar
   publicado.

**Sem rodar isso, o módulo de campanha não funciona** — as tabelas novas (`campaign_profiles`,
`campaign_proposals` etc.) não existirão e o cartão de campanha vai dar erro 500 para quem usar
esse template.

---

## 3. Migrations — ordem exata (9 novas)

```
2026_08_02_000002_create_campaign_profiles_table.php
2026_08_02_000003_create_campaign_proposals_tables.php       (2 tabelas: categorias + propostas)
2026_08_02_000004_create_card_files_table.php
2026_08_02_000005_create_campaign_news_and_timeline_tables.php  (2 tabelas: notícias + linha do tempo)
2026_08_02_000006_create_campaign_team_members_table.php
2026_08_02_000007_create_campaign_events_table.php
2026_08_02_000008_add_purpose_to_contact_messages_table.php  (ALTER em tabela existente)
2026_08_02_000009_widen_template_column_on_cards_table.php   (ALTER: cards.template varchar(20)→(40))
2026_08_03_000001_add_legal_fields_to_campaign_profiles_table.php  (ALTER: 2 colunas novas)
```

`php artisan migrate --force` roda todas na ordem correta automaticamente — a lista acima é só para conferência caso algo precise ser aplicado manualmente.

**Atenção especial à migration `000009`**: ela usa `->change()` (requer `doctrine/dbal`, já está no `composer.lock`). Se o `composer install --no-dev` não tiver puxado essa dependência por algum motivo, essa migration específica vai falhar — confira `vendor/doctrine/dbal` existe antes de rodar.

---

## 4. Checklist pós-deploy (smoke test manual)

- [ ] `https://SEU_DOMINIO/legal/termos` carrega e mostra a seção "10. Uso em campanhas eleitorais e eleições internas"
- [ ] Login no painel (`/dashboard`) funciona normalmente para uma conta existente
- [ ] Um usuário Pro consegue abrir `/dashboard/campanha/perfil` sem erro 500
- [ ] Selecionar um template `campaign-*` no editor do cartão (`/dashboard/card`) e ver o cartão público (`/u/{slug}`) renderizar sem erro
- [ ] `/admin/cards/{id}/edit` abre e mostra as abas novas (Propostas, Notícias, Linha do Tempo, Equipe, Agenda, Arquivos)
- [ ] Nenhum erro novo nos logs (`storage/logs/laravel.log`) nos minutos seguintes ao deploy

## 5. Rollback, se algo der errado

```bash
git log --oneline -5          # confirme o commit anterior
git checkout c4376fd -- .     # commit anterior ao deploy da campanha
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan config:cache
```

As migrations novas são aditivas (criam tabelas/colunas, não removem nada da estrutura anterior) — **não é necessário rodar `migrate:rollback`** para reverter o código; as tabelas novas simplesmente ficam sem uso. Só reverta as migrations manualmente se precisar liberar espaço ou se houver conflito de nome.

---

*Gerado a partir do commit `d1e50c3` · Card SaaS · PageUp Sistemas*
