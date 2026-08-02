# Auditoria Técnica — vCard (.vcf) e Compartilhamento NFC
> Card SaaS · PageUp Sistemas · Análise read-only, nenhum código foi alterado.

---

## 1. Auditoria do vCard

### 1.1 O que existe hoje

Arquivo: `app/Services/VCardService.php` (52 linhas), acionado por `CardController.php:60-71`, rota em `routes/web.php:78`.

```php
$lines = [
    'BEGIN:VCARD',
    'VERSION:3.0',
    'FN:' . $this->escape($card->display_name),
];
if ($card->title || $card->company) {
    $lines[] = 'TITLE:' . $this->escape($card->title ?? '');
    $lines[] = 'ORG:' . $this->escape($card->company ?? '');
}
if ($card->contact_phone)    $lines[] = 'TEL;TYPE=CELL:' . preg_replace('/\D/', '', $card->contact_phone);
if ($card->contact_landline) $lines[] = 'TEL;TYPE=WORK:' . preg_replace('/\D/', '', $card->contact_landline);
if ($card->contact_email)    $lines[] = 'EMAIL:' . $card->contact_email;
if ($card->website)          $lines[] = 'URL:' . $card->website;
if ($card->address)          $lines[] = 'ADR;TYPE=WORK:;;' . $this->escape($card->address) . ';;;;';
$lines[] = 'URL:' . url('/u/' . $card->slug);
$lines[] = 'END:VCARD';
return implode("\r\n", $lines);
```

Headers HTTP (`CardController.php:60-71`):
```php
'Content-Type'        => 'text/vcard; charset=utf-8',
'Content-Disposition' => 'attachment; filename="' . $filename . '"',
```

### 1.2 Tabela de campos

| Campo do sistema | Campo do vCard | Exportado? | Compat. Android | Compat. iPhone | Observações |
|---|---|:---:|:---:|:---:|---|
| display_name (nome completo) | `FN` | ✅ | ✅ | ✅ | Falta `N:` (obrigatório de fato pela maioria dos parsers — sem ele, Nome/Sobrenome não separam nos Contatos) |
| Nome / Sobrenome separados | `N:Sobrenome;Nome;;;` | ❌ | ⚠️ | ⚠️ | **Ausente.** Google/Samsung/iOS importam tudo em "nome" só, sem sobrenome pesquisável |
| title (cargo) | `TITLE` | ✅ (só se title OU company) | ✅ | ✅ | condicional, ok |
| company | `ORG` | ✅ (mesma condição) | ✅ | ✅ | ok |
| contact_phone (celular) | `TEL;TYPE=CELL` | ✅ | ✅ | ✅ | dígitos puros, sem `+55`, pode confundir DDI ao importar |
| contact_landline (comercial) | `TEL;TYPE=WORK` | ✅ | ✅ | ✅ | ok |
| Telefone residencial | `TEL;TYPE=HOME` | ❌ | — | — | não existe campo `contact_home` no model |
| WhatsApp | — | ❌ | ❌ | ❌ | WhatsApp só existe como `CardLink` social (via `SocialLinkService`), nunca chega ao `.vcf` |
| contact_email | `EMAIL` | ✅ | ✅ | ✅ | **não passa por `escape()`** — risco se contiver `;`/`,` |
| website | `URL` | ✅ | ✅ | ✅ | **não escapado**, sem validação de esquema (`http://` pode faltar) |
| address (endereço único) | `ADR;TYPE=WORK:;;<tudo>;;;;` | ✅ (parcial) | ⚠️ | ⚠️ | endereço inteiro jogado no componente "rua"; cidade/estado/CEP/país ficam vazios |
| Cidade | `ADR` (componente 4) | ❌ | — | — | não há campo separado no model |
| Estado | `ADR` (componente 5) | ❌ | — | — | idem |
| CEP | `ADR` (componente 6) | ❌ | — | — | idem |
| País | `ADR` (componente 7) | ❌ | — | — | idem |
| bio / observações | `NOTE` | ❌ | — | — | campo existe no model mas não é lido pelo service |
| profile_photo | `PHOTO;ENCODING=b;TYPE=...` | ❌ | ❌ | ❌ | **totalmente ausente** — nenhuma foto é embutida |
| cover_photo / logo | `PHOTO` (alternativo) | ❌ | — | — | idem |
| Redes sociais (CardLink) | `X-SOCIALPROFILE` / `URL` extra | ❌ | — | — | nenhum link social exportado |
| Galeria (CardPhoto) | — | ❌ (correto) | — | — | não deveria ir no vCard mesmo |
| slug / URL do cartão | `URL` (2ª linha, sem TYPE) | ✅ | ⚠️ | ⚠️ | duas linhas `URL:` sem diferenciação — cliente mostra "Website" duas vezes |

### 1.3 Problemas técnicos encontrados

1. **Falta `N:`** — RFC 6350 exige `N` como propriedade estrutural; sem ela, Google Contacts, Samsung Contacts e Apple Contacts importam o nome inteiro como "primeiro nome", sem sobrenome pesquisável/ordenável.
2. **Sem `PHOTO`** — a foto de perfil (`profile_photo`, já salva via `ImageService`) nunca é lida por `VCardService`. É a lacuna de maior impacto perceptível pelo usuário final.
3. **Escaping incompleto** — `escape()` cobre `display_name`, `title`, `company`, `address`, mas **não** `contact_email` nem `website`. Um e-mail ou URL com `;` quebra o parsing em clientes rígidos (ex.: Outlook, alguns parsers Android mais antigos).
4. **Sem `TEL;TYPE=HOME`, sem WhatsApp** — modelo de dados não tem esses campos; WhatsApp fica isolado no card público (link `wa.me`), nunca chega ao contato salvo no telefone.
5. **`ADR` mal estruturado** — endereço completo despejado em um único componente ("rua"), quando o formato correto é `ADR;TYPE=WORK:caixa-postal;complemento;rua;cidade;estado;cep;pais`. Isso não quebra a importação, mas o endereço aparece "errado" (tudo em uma linha) em apps como Google Contacts que exibem os componentes separadamente.
6. **Duas linhas `URL:` idênticas em tipo** — sem `TYPE=` para diferenciar site pessoal do link do cartão. Não é erro fatal, mas gera UX confusa ("Website" repetido).
7. **Sem dobra de linha (line folding) a 75 octetos** — RFC 6350 §3.2 pede folding em linhas longas (`ORG`, `ADR`, `URL` extensos). A maioria dos parsers modernos tolera, mas é uma não-conformidade formal.
8. **Telefone sem DDI** — `preg_replace('/\D/', '', ...)` remove tudo que não é dígito, inclusive o `+`. Um número `+55 69 99999-9999` vira `556999999999` sem o `+`, o que pode fazer alguns parsers (principalmente iOS) interpretarem o DDI errado, especialmente se o usuário salvou sem código do país.
9. **`Content-Disposition` sem fallback RFC 5987** — não é bug prático (o filename já é ASCII via `Str::slug`), mas nomes acentuados perdem os acentos no arquivo baixado.
10. **`VERSION:3.0`** — escolha correta e mais compatível (4.0 tem suporte mais fraco em apps legados Android/Samsung); manter 3.0 é a decisão certa.

### 1.4 Compatibilidade por importador — checagem qualitativa

| Cliente | Resultado esperado ao importar hoje |
|---|---|
| Google Contacts (Android) | Importa nome, telefone, e-mail, empresa, cargo, endereço (em uma linha só), site. **Sem foto.** Nome sem sobrenome separado. |
| Samsung Contacts | Igual ao Google, historicamente mais tolerante a `ADR` malformado. Sem foto — perceptível como "contato incompleto". |
| Xiaomi (MIUI/HyperOS) | Comportamento similar ao AOSP Contacts; sensível à ausência de `N:` — pode mostrar nome truncado em algumas versões. |
| Motorola (quase-stock Android) | Igual ao Google Contacts — melhor compatibilidade da lista. |
| Apple Contacts (iPhone) | Mais rígido com escaping — como `EMAIL`/`URL` não são escapados, se contiverem caractere especial o import pode falhar silenciosamente ou truncar o campo. Sem `N:`, o Apple Contacts usa heurística para separar nome/sobrenome (nem sempre correto). |

Conclusão: a importação **funciona no caminho feliz** (dados sem caracteres especiais), mas há **perda garantida de foto**, **perda de granularidade de nome e endereço**, e **risco de quebra** em e-mails/sites com `;`/`,`.

### 1.5 Melhorias recomendadas (ordem de prioridade)

1. Adicionar `PHOTO;ENCODING=b;TYPE=JPEG:<base64>` lendo `profile_photo` via `ImageService` (redimensionar para ~200px antes de embutir, para não gerar .vcf gigante).
2. Adicionar `N:` decompondo `display_name` (heurística simples: última palavra = sobrenome, resto = nome) ou, melhor, adicionar campos `first_name`/`last_name` no cadastro.
3. Aplicar `escape()` também em `contact_email` e `website`.
4. Estruturar `ADR` corretamente, adicionando campos `city`, `state`, `zip_code`, `country` ao model `Card` (migration nova, respeitando a regra do CLAUDE.md de checar `migrate:status` antes).
5. Adicionar `NOTE:` com a `bio`.
6. Diferenciar as duas `URL:` com `TYPE=` (`URL;TYPE=WORK:` para site, deixar o link do cartão sem tipo ou usar `X-CARDURL`).
7. Preservar `+` no início do telefone ao limpar dígitos (regex ajustada: manter `+` líder, remover o resto de não-dígitos).
8. Adicionar suporte a WhatsApp como campo dedicado no `Card` (`whatsapp_number`), exportando como `TEL;TYPE=CELL,VOICE` mais um `item.TEL;X-ABLabel=WhatsApp:` (formato usado pelo Apple Contacts para rótulos customizados).

---

## 2. Compartilhamento via NFC

### 2.1 Situação do Android Beam e alternativas modernas

- **Android Beam foi descontinuado** a partir do Android 10 (2019) e removido definitivamente nas versões seguintes. Não deve ser considerado em nenhuma arquitetura nova.
- O substituto moderno para leitura/escrita de tags é a **NDEF API nativa do Android** (`android.nfc.NdefMessage`, `NfcAdapter`), disponível apenas em apps **nativos** (Kotlin/Java) — não exposta a PWAs.
- Para o **navegador/PWA**, a API relevante é a **Web NFC API** (`NDEFReader`/`NDEFWriter` do Chrome for Android). Ela permite ler e escrever tags NDEF a partir de JavaScript, mas com limitações importantes (ver 2.3).

### 2.2 Tecnologias possíveis e compatibilidade com a stack atual (Blade/Livewire/PWA)

| Tecnologia | Funciona hoje? | Observação |
|---|:---:|---|
| Web NFC (`NDEFReader`) | ⚠️ Parcial | Somente Chrome/Edge no **Android** (não existe no iOS/Safari, nem em nenhum navegador desktop). Requer HTTPS e contexto seguro, gesto do usuário, e permissão explícita. |
| NFC NDEF nativo (Kotlin) | ✅ | Só em app nativo Android; foreground dispatch + `NdefMessage` funcionam bem para device-to-device (modo P2P foi removido, mas leitura/escrita de tag e "tap to beam" via `NfcAdapter.setNdefPushMessage` também foi removida — resta somente leitura/escrita de **tags físicas**). |
| Trusted Web Activity (TWA) | ⚠️ Parcial | TWA roda a mesma engine Chrome do PWA — **herda exatamente as mesmas limitações da Web NFC**, não ganha acesso nativo a NFC P2P. Não resolve o problema central (ver 2.3). |
| Capacitor (plugin NFC) | ✅ | Plugins como `@capacitor-community/nfc` dão acesso a leitura/escrita de tags NDEF via código nativo por baixo, mantendo o PWA como base. Continua limitado a **tag física**, não a two-phone tap. |
| Cordova | ✅ (legado) | `phonegap-nfc` similar ao Capacitor; comunidade em declínio, não recomendado para projeto novo. |
| App nativo Android puro | ✅ | Única via com acesso total à pilha NFC do Android, mas ainda **sem substituto oficial ao Android Beam para P2P** desde sua remoção. |

### 2.3 O ponto central: "aproximar dois Android sem tag física" NÃO é mais suportado

Isso é a informação mais importante desta seção:

- O **Android Beam** era o único mecanismo oficial que permitia dois aparelhos Android trocarem dados (URL, vCard, texto) **apenas aproximando-os**, sem qualquer tag física. Ele usava NFC para o handshake inicial e depois trocava o payload maior via **Bluetooth/Wi-Fi Direct**.
- Ele foi **removido definitivamente** (não existe em nenhuma versão suportada — Android 12, 13, 14, 15 **não o incluem de forma alguma**, nativo ou via API pública).
- **Não existe API pública, nem em Web NFC, nem em NDEF nativo, nem em nenhuma versão do Android 12-15, que reabilite comunicação NFC "phone-to-phone" sem uma tag física intermediária.** A Web NFC API do Chrome só lê/escreve **tags NDEF físicas** — ela não implementa nem expõe modo peer-to-peer (`LLCP`/`SNEP`) entre dois telefones.
- Ou seja: **compartilhamento por "encostar dois celulares" sem adesivo/chip físico não é tecnicamente viável hoje**, nem em PWA, nem em app nativo, nem com qualquer biblioteca — o hardware/API que permitia isso (Android Beam) foi descontinuado e não tem substituto direto.
- O único caminho que continua funcionando com NFC é: usuário compra/imprime **um cartão/adesivo NFC físico** (custa poucos reais), grava nele a URL do cartão (`https://.../u/{slug}`) com Web NFC ou app de gravação NFC, e outras pessoas aproximam o **celular delas** desse adesivo — isso sim funciona em qualquer Android com NFC ligado, sem app nenhum instalado (o Android abre o link automaticamente via "Android tag reading").

### 2.4 Bibliotecas modernas relevantes

- **Web NFC**: nenhuma lib de terceiros necessária — é API do navegador (`navigator.nfc` on Chrome Android ≥89, atrás de flag/gesto). Útil apenas para **gravar tags físicas** direto do painel do usuário (ex.: "grave seu cartão em um adesivo NFC").
- **`@capacitor-community/nfc`**: melhor opção se o projeto migrar para um wrapper híbrido — mesma limitação (tags físicas).
- Nenhuma biblioteca, nativa ou não, reabilita o "tap two phones" sem tag.

### 2.5 Melhor arquitetura recomendada

Dado que o produto é hoje um **PWA Laravel/Livewire/Blade**, sem app nativo:

1. **Não vale a pena migrar para Capacitor/Flutter/React Native só por causa de NFC** — o ganho seria nulo, já que nem app nativo resolve o "tap two phones sem tag" (essa funcionalidade não existe mais em nenhuma plataforma).
2. **Melhor arquitetura = manter PWA + oferecer NFC como recurso opcional via Web NFC para gravação de tags físicas**, complementado pelo QR Code (que já existe via `QrCodeService`) como o mecanismo primário de compartilhamento por proximidade — o QR Code já cobre 100% do caso de uso "compartilhar rapidamente entre dois Androids" sem custo, sem hardware, e funcionando em iPhone também (NFC não funciona em iPhone para esse fluxo custom).
3. Se o usuário quiser mesmo uma experiência "encoste o celular", a única solução real é venda/distribuição de **cartões NFC físicos programáveis** com a URL do slug gravada — e o app pode oferecer uma tela "Gravar meu cartão em uma tag NFC" usando Web NFC (`NDEFWriter`), o que É viável e barato de implementar.

### 2.6 Melhor experiência para o usuário Android hoje

**QR Code continua sendo a melhor experiência real disponível** — funciona em qualquer smartphone (Android e iPhone), sem depender de hardware NFC, sem exigir que os dois aparelhos tenham NFC ligado, e sem limitação de navegador. Recomenda-se investir em:
- Melhorar o QR Code atual (`QrCodeService`) para abrir direto a URL do cartão.
- Adicionar Web Share API (`navigator.share()`) para compartilhar o link via qualquer app instalado (WhatsApp, SMS, etc.) com um toque — essa é a "melhor experiência Android" mais próxima do que o usuário imagina ao pedir NFC, e é 100% viável em PWA puro hoje, sem limitações.

### 2.7 Exemplo de implementação recomendada (gravação de tag NFC opcional via Web NFC)

```javascript
// Tela "Gravar cartão em tag NFC" — Chrome Android apenas
async function writeCardToNfcTag(cardUrl) {
  if (!('NDEFReader' in window)) {
    alert('Web NFC não suportado neste navegador/dispositivo.');
    return;
  }
  try {
    const writer = new NDEFReader();
    await writer.write({ records: [{ recordType: 'url', data: cardUrl }] });
    alert('Tag gravada com sucesso! Aproxime a tag de qualquer Android para abrir o cartão.');
  } catch (err) {
    alert('Erro ao gravar: aproxime uma tag NFC vazia e tente novamente.');
  }
}
```

---

## 3. Notas de maturidade (0–10)

| Funcionalidade | Nota | Justificativa |
|---|:---:|---|
| Exportação vCard | **5/10** | Estrutura básica funcional (caminho feliz ok), mas sem foto, sem `N:`, endereço mal estruturado, escaping incompleto — está longe do que um usuário esperaria de "salvar contato completo". |
| Compartilhamento NFC | **0/10 (não implementado)** — potencial real limitado a **3/10** mesmo após implementação, porque a funcionalidade que o usuário provavelmente imagina ("encostar dois celulares") não é mais tecnicamente possível em nenhuma plataforma. |
| QR Code (já existe) | Fora do escopo desta auditoria, mas é hoje o mecanismo que já cobre o caso de uso que o NFC não pode cobrir. |

---

## 4. Plano de implementação priorizado

1. **[Alta / baixo esforço] Corrigir escaping de `EMAIL` e `URL`** — risco de quebra de importação, correção de poucas linhas em `VCardService.php`.
2. **[Alta / médio esforço] Adicionar `PHOTO` embutida ao vCard** — maior ganho de percepção de qualidade pelo usuário final; requer redimensionar imagem (reaproveitar `ImageService`) antes de codificar em base64.
3. **[Alta / médio esforço] Adicionar `N:` (nome/sobrenome)** — melhora a experiência de importação em todos os apps de contatos; requer decisão de produto (separar campos no cadastro ou heurística automática).
4. **[Média / médio esforço] Estruturar `ADR` corretamente** — requer migration nova (`city`, `state`, `zip_code`, `country` em `cards`), atualização do formulário do painel e do `VCardService`.
5. **[Média / baixo esforço] Adicionar `NOTE` com a bio e diferenciar as duas `URL:` com `TYPE=`.**
6. **[Média / baixo esforço] Preservar `+` do DDI na limpeza do telefone.**
7. **[Baixa / baixo esforço] Adicionar Web Share API (`navigator.share()`) no cartão público** — melhora imediata de "compartilhamento por proximidade" sem tocar em NFC, maior ROI de UX por esforço.
8. **[Baixa / médio esforço] Adicionar campo dedicado de WhatsApp ao `Card` e exportá-lo como `TEL` com rótulo customizado.**
9. **[Opcional / baixo ROI] Web NFC apenas para "gravar cartão em tag física"** — só vale a pena se houver demanda real de clientes por cartões NFC físicos (produto complementar); não implementar compartilhamento "phone-to-phone" pois não é tecnicamente viável.
10. **Não recomendado:** migrar a arquitetura para Capacitor/Flutter/nativo apenas por causa de NFC — o ganho seria zero, pois a limitação central (ausência de Android Beam) afeta igualmente apps nativos.
