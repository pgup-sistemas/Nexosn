<x-app-layout>
    <div class="max-w-2xl">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Meu Cartão</h1>
                <p class="text-sm text-gray-500 mt-1">Edite as informações do seu cartão de visita digital.</p>
            </div>
            @if (auth()->user()->card)
                <a href="{{ route('card.show', auth()->user()->card->slug) }}" target="_blank"
                   class="hidden sm:flex items-center gap-1.5 text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-50 transition">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Ver cartão
                </a>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <livewire:card.card-editor />
        </div>
    </div>
</x-app-layout>
