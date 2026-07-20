<div class="px-5 py-5 bg-gray-50 border-t border-gray-100">
    <div class="flex items-center gap-2 text-sm font-semibold mb-4" style="color: var(--card-primary);">
        <i data-lucide="send" class="w-4 h-4"></i>
        Enviar mensagem
    </div>

    @if ($sent)
        <div class="flex flex-col items-center gap-2 py-6 text-center">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
            </div>
            <p class="text-sm font-medium text-gray-800">Mensagem enviada!</p>
            <p class="text-xs text-gray-500">{{ $card->display_name }} receberá em breve.</p>
            <button wire:click="$set('sent', false)" class="text-xs mt-2 underline" style="color: var(--card-primary);">
                Enviar outra mensagem
            </button>
        </div>
    @else
        {{-- Banner offline --}}
        <div id="nexosn-form-offline-banner" style="display:none;background:#FFF3CD;border:1px solid #F77F00;
             border-radius:8px;padding:10px 12px;font-size:12px;color:#7a4600;margin-bottom:10px;line-height:1.5;">
            📶 <strong>Você está offline.</strong> Sua mensagem será salva e enviada automaticamente quando a conexão voltar.
        </div>
        {{-- Confirmação de mensagem salva offline --}}
        <div id="nexosn-form-saved-banner" style="display:none;background:#e6f4ea;border:1px solid #16a34a;
             border-radius:8px;padding:10px 12px;font-size:12px;color:#14532d;margin-bottom:10px;line-height:1.5;">
            ✓ <strong>Mensagem salva!</strong> Será enviada assim que você reconectar.
        </div>

        <form wire:submit="submit" class="space-y-2"
              x-data="{}"
              @submit.prevent="nexosnFormSubmit($el, $event)">

            {{-- Honeypot (oculto de humanos) --}}
            <div class="hidden" aria-hidden="true">
                <input wire:model="website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            @error('limit')
            <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg p-2.5">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0"></i>
                <p class="text-xs text-amber-800">{{ $message }}</p>
            </div>
            @enderror

            <input wire:model="senderName" type="text" placeholder="Seu nome *"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-gray-400 @error('senderName') border-red-400 @enderror">
            @error('senderName')<p class="text-xs text-red-600">{{ $message }}</p>@enderror

            <input wire:model="senderEmail" type="email" placeholder="Seu e-mail *"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-gray-400 @error('senderEmail') border-red-400 @enderror">
            @error('senderEmail')<p class="text-xs text-red-600">{{ $message }}</p>@enderror

            <input wire:model="senderPhone" type="tel" placeholder="Telefone (opcional)"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-gray-400">

            <textarea wire:model="message" placeholder="Sua mensagem *" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-gray-400 resize-none @error('message') border-red-400 @enderror"></textarea>
            @error('message')<p class="text-xs text-red-600">{{ $message }}</p>@enderror

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-2.5 rounded-xl text-sm font-medium text-white transition hover:opacity-90 disabled:opacity-60"
                    style="background-color: var(--card-button);">
                <span wire:loading.remove>Enviar mensagem</span>
                <span wire:loading>Enviando...</span>
            </button>
        </form>
    @endif
</div>

<script>
(function () {
    const STORAGE_KEY = 'nexosn_pending_msg_{{ $card->slug }}';

    // Mostrar/ocultar banner offline no formulário
    function updateOfflineBanner() {
        const banner = document.getElementById('nexosn-form-offline-banner');
        if (banner) banner.style.display = navigator.onLine ? 'none' : 'block';
    }
    updateOfflineBanner();
    window.addEventListener('offline', updateOfflineBanner);
    window.addEventListener('online',  updateOfflineBanner);

    // Verificar e reenviar mensagem pendente quando voltar online
    window.addEventListener('online', function () {
        const pending = localStorage.getItem(STORAGE_KEY);
        if (!pending) return;
        try {
            const d = JSON.parse(pending);
            // Preencher os campos e disparar o submit Livewire
            const form = document.querySelector('[id^="nexosn-contact-form"]') || document.querySelector('form');
            if (!form) return;
            const setField = (wire, val) => {
                const el = form.querySelector('[wire\\:model="' + wire + '"]');
                if (el) { el.value = val; el.dispatchEvent(new Event('input')); }
            };
            setField('senderName',  d.senderName);
            setField('senderEmail', d.senderEmail);
            setField('senderPhone', d.senderPhone || '');
            setField('message',     d.message);
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) {}
    });

    // Submit interceptor: salva offline, envia online
    window.nexosnFormSubmit = function (formEl, event) {
        if (navigator.onLine) {
            // Deixar Livewire processar normalmente
            formEl.dispatchEvent(new CustomEvent('livewire-submit'));
            return;
        }
        // Offline: salvar no localStorage
        const get = (wire) => {
            const el = formEl.querySelector('[wire\\:model="' + wire + '"]');
            return el ? el.value : '';
        };
        const data = {
            senderName:  get('senderName'),
            senderEmail: get('senderEmail'),
            senderPhone: get('senderPhone'),
            message:     get('message'),
        };
        if (!data.senderName || !data.message) return; // deixar validação Livewire agir
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        const savedBanner = document.getElementById('nexosn-form-saved-banner');
        if (savedBanner) {
            savedBanner.style.display = 'block';
            setTimeout(() => savedBanner.style.display = 'none', 8000);
        }
    };
})();
</script>
