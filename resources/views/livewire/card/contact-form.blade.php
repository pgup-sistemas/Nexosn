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
        <form wire:submit="submit" class="space-y-2">

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
