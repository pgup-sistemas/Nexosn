<x-app-layout>
    <div>
        <div class="mb-5">
            <h1 class="text-xl font-semibold text-gray-900">Compartilhar Perfil</h1>
            <p class="text-sm text-gray-500 mt-1">Compartilhe sua identidade digital via link ou QR Code.</p>
        </div>

        <div class="space-y-4">

            {{-- Link do cartão --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4" style="color: var(--color-primary);"></i>
                    Seu link
                </h2>
                <div class="flex items-center gap-2">
                    <input type="text" value="{{ $cardUrl }}" readonly id="card-url-input"
                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-700">
                    <button onclick="copiarLink()"
                            class="shrink-0 flex items-center gap-1.5 text-sm font-medium px-3 py-2 rounded-lg text-white transition hover:opacity-90"
                            style="background-color: var(--color-primary);">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                        Copiar
                    </button>
                </div>
                <p id="copiado-msg" class="hidden text-xs text-green-600 mt-2 font-medium">✓ Link copiado!</p>

                {{-- Botões de compartilhamento --}}
                <div class="flex gap-2 mt-3 flex-wrap">
                    <a href="https://wa.me/?text={{ urlencode('Acesse meu perfil digital: ' . $cardUrl) }}"
                       target="_blank"
                       class="flex items-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg text-white transition hover:opacity-90"
                       style="background-color: #25D366;">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        WhatsApp
                    </a>
                    <a href="mailto:?subject=Meu+Perfil+Digital&body={{ urlencode('Acesse meu perfil digital: ' . $cardUrl) }}"
                       class="flex items-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg border border-gray-300 text-gray-600 transition hover:bg-gray-50">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                        E-mail
                    </a>
                </div>
            </div>

            {{-- QR Code --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i data-lucide="qr-code" class="w-4 h-4" style="color: var(--color-primary);"></i>
                    QR Code
                </h2>

                <div class="flex flex-col items-center gap-4">
                    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                        {!! $qrSvg !!}
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('card.qr.svg', $card->slug) }}"
                           download="{{ $card->slug }}-qrcode.svg"
                           class="flex items-center gap-1.5 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 text-gray-600 transition hover:bg-gray-50">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            SVG
                        </a>
                        <a href="{{ route('card.qr.png', $card->slug) }}"
                           class="flex items-center gap-1.5 text-sm font-medium px-4 py-2 rounded-lg text-white transition hover:opacity-90"
                           style="background-color: var(--color-primary);">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            PNG
                        </a>
                    </div>

                    <p class="text-xs text-gray-400 text-center">
                        Aponte a câmera do celular para o QR Code<br>para acessar seu cartão digital.
                    </p>
                </div>
            </div>

            {{-- Gravação em tag NFC --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5" x-data="nfcWriter('{{ $cardUrl }}')">
                <h2 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <i data-lucide="nfc" class="w-4 h-4" style="color: var(--color-primary);"></i>
                    Gravar em tag NFC
                </h2>

                <p class="text-xs text-gray-500 mb-3 leading-relaxed">
                    Grave o link do seu cartão em uma <strong>tag NFC física</strong> (adesivo ou cartão com chip NFC,
                    vendido separadamente em lojas de eletrônicos ou online). Depois de gravada, qualquer pessoa com um
                    celular Android pode encostar o aparelho na tag para abrir seu cartão digital — sem precisar
                    instalar nenhum aplicativo.
                </p>

                <div class="rounded-lg bg-gray-50 border border-gray-200 p-3 mb-4">
                    <p class="text-xs font-semibold text-gray-600 mb-1.5">Como usar:</p>
                    <ol class="text-xs text-gray-500 list-decimal list-inside space-y-1">
                        <li>Tenha em mãos uma tag NFC em branco (compatível com NDEF, ex: NTAG213/215/216).</li>
                        <li>Abra esta página pelo navegador <strong>Chrome no seu celular Android</strong> (com o NFC ativado nas configurações do aparelho).</li>
                        <li>Toque em "Gravar agora" e encoste a tag na parte de trás do celular quando solicitado.</li>
                        <li>Pronto! Cole a tag em cartões de visita, crachás, balcões ou onde preferir.</li>
                    </ol>
                </div>

                <button @click="write()" :disabled="writing"
                        class="flex items-center gap-1.5 text-sm font-medium px-4 py-2 rounded-lg text-white transition hover:opacity-90 disabled:opacity-60"
                        style="background-color: var(--color-primary);">
                    <i data-lucide="nfc" class="w-4 h-4"></i>
                    <span x-text="buttonLabel"></span>
                </button>

                <p class="text-xs mt-2" :class="statusClass" x-text="statusText" x-show="statusText"></p>

                <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                    <i data-lucide="info" class="w-3 h-3 inline-block align-text-bottom"></i>
                    Disponível apenas no Chrome/Edge para Android com NFC. Não funciona no iPhone nem em navegadores
                    de computador — nesses casos, use o link ou o QR Code acima. A tag, uma vez gravada, funciona em
                    qualquer celular com NFC, mesmo sem o app instalado.
                </p>
            </div>

        </div>
    </div>

    <script>
    function copiarLink() {
        navigator.clipboard.writeText('{{ $cardUrl }}').then(() => {
            const msg = document.getElementById('copiado-msg');
            msg.classList.remove('hidden');
            setTimeout(() => msg.classList.add('hidden'), 3000);
        });
    }

    function nfcWriter(cardUrl) {
        return {
            writing: false,
            buttonLabel: 'Gravar agora',
            statusText: '',
            statusClass: 'text-gray-500',
            async write() {
                if (!('NDEFReader' in window)) {
                    this.statusText = 'Seu navegador não suporta gravação NFC. Use o Chrome no Android.';
                    this.statusClass = 'text-amber-600';
                    return;
                }
                this.writing = true;
                this.buttonLabel = 'Aguardando...';
                this.statusText = 'Aproxime a tag NFC da parte de trás do celular...';
                this.statusClass = 'text-gray-500';
                try {
                    const writer = new NDEFReader();
                    await writer.write({ records: [{ recordType: 'url', data: cardUrl }] });
                    this.statusText = '✓ Tag gravada com sucesso! Aproxime qualquer celular Android dela para abrir seu cartão.';
                    this.statusClass = 'text-green-600';
                } catch (err) {
                    this.statusText = 'Não foi possível gravar. Verifique se o NFC está ativado e aproxime uma tag vazia.';
                    this.statusClass = 'text-red-600';
                } finally {
                    this.writing = false;
                    this.buttonLabel = 'Gravar agora';
                }
            }
        };
    }
    </script>
</x-app-layout>
