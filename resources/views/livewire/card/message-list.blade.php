<div class="space-y-3">

    @forelse ($messages as $msg)
    <div class="p-4 rounded-xl border {{ $msg->isUnread() ? 'border-blue-200 bg-blue-50' : 'border-gray-200 bg-white' }} transition">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    @if ($msg->isUnread())
                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                    @endif
                    <p class="text-sm font-semibold text-gray-900">{{ $msg->sender_name }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $msg->sender_email }}
                    @if ($msg->sender_phone) · {{ $msg->sender_phone }} @endif
                </p>
                <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ $msg->message }}</p>
            </div>
            <div class="shrink-0 text-right">
                <p class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</p>
                @if ($msg->isUnread())
                <button wire:click="markRead({{ $msg->id }})"
                        class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                    Marcar lida
                </button>
                @endif
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <a href="mailto:{{ $msg->sender_email }}?subject=Re: Mensagem do Card"
               class="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition flex items-center gap-1">
                <i data-lucide="reply" class="w-3 h-3"></i>
                Responder
            </a>
            @if ($msg->sender_phone)
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $msg->sender_phone) }}"
               target="_blank"
               class="text-xs font-medium px-3 py-1.5 rounded-lg text-white transition hover:opacity-90 flex items-center gap-1"
               style="background-color: #25D366;">
                <i data-lucide="message-circle" class="w-3 h-3"></i>
                WhatsApp
            </a>
            @endif
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center gap-2 py-12 text-center">
        <i data-lucide="inbox" class="w-10 h-10 text-gray-300"></i>
        <p class="text-sm font-medium text-gray-600">Nenhuma mensagem ainda</p>
        <p class="text-xs text-gray-400">As mensagens enviadas pelo seu cartão aparecerão aqui.</p>
    </div>
    @endforelse

    @if (method_exists($messages, 'links'))
        {{ $messages->links() }}
    @endif

</div>
