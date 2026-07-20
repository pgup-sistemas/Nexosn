<x-app-layout>
    <div class="max-w-2xl">
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
    </script>
</x-app-layout>
