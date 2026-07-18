<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="link" class="w-4 h-4" style="color: var(--color-primary);"></i>
            Links e redes sociais
        </h2>
        <button wire:click="$set('showForm', true)"
                class="flex items-center gap-1.5 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-90"
                style="background-color: var(--color-action);">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            Adicionar
        </button>
    </div>

    {{-- Indicador de limite (Free) --}}
    @if (!$isPro)
    <div class="mb-3">
        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
            <span>{{ $count }} de 5 links</span>
            @if ($count >= 5)
                <a href="{{ route('dashboard.plan') }}" class="text-purple-600 font-medium">Fazer upgrade →</a>
            @endif
        </div>
        <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all" style="width: {{ min(($count / 5) * 100, 100) }}%; background-color: var(--color-primary);"></div>
        </div>
    </div>
    @endif

    @error('limit')
    <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg p-3 mb-3">
        <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0"></i>
        <p class="text-xs text-amber-800">{{ $message }}</p>
    </div>
    @enderror

    {{-- Formulário de adição --}}
    @if ($showForm)
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4 space-y-3">
        <p class="text-xs font-semibold text-gray-700">Novo link</p>

        <div class="space-y-1">
            <label class="text-xs font-medium text-gray-500">URL</label>
            <input wire:model.live="newUrl"
                   type="url"
                   placeholder="https://..."
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            @error('newUrl')<p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-end gap-2">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                 style="background-color: var(--color-primary);">
                <i data-lucide="{{ $newIcon }}" class="w-4 h-4" style="color: #EAE2B7;"></i>
            </div>
            <div class="flex-1 space-y-1">
                <label class="text-xs font-medium text-gray-500">Nome do link</label>
                <input wire:model="newLabel"
                       type="text"
                       placeholder="Ex: Meu Instagram"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('newLabel')<p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="addLink"
                    class="flex-1 text-white text-sm font-medium rounded-lg py-2 transition hover:opacity-90"
                    style="background-color: var(--color-primary);">
                Adicionar
            </button>
            <button wire:click="$set('showForm', false)"
                    class="px-4 border border-gray-300 text-gray-600 text-sm rounded-lg py-2 transition hover:bg-gray-100">
                Cancelar
            </button>
        </div>
    </div>
    @endif

    {{-- Lista de links (drag-and-drop via SortableJS) --}}
    <div id="sortable-links" class="space-y-2">
        @forelse ($links as $link)
        <div data-id="{{ $link->id }}"
             class="flex items-center gap-2.5 p-3 bg-white border border-gray-200 rounded-xl transition {{ !$link->is_active ? 'opacity-50' : '' }}">
            <button class="cursor-grab touch-none text-gray-300 hover:text-gray-500 shrink-0">
                <i data-lucide="grip-vertical" class="w-4 h-4"></i>
            </button>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                 style="background-color: var(--color-primary);">
                <i data-lucide="{{ $link->icon ?? 'link' }}" class="w-4 h-4" style="color: #EAE2B7;"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $link->label }}</p>
                <p class="text-xs text-gray-400 truncate">{{ $link->url }}</p>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button wire:click="toggleLink({{ $link->id }})"
                        title="{{ $link->is_active ? 'Desativar' : 'Ativar' }}">
                    <i data-lucide="{{ $link->is_active ? 'toggle-right' : 'toggle-left' }}"
                       class="w-5 h-5 {{ $link->is_active ? '' : 'text-gray-300' }}"
                       style="{{ $link->is_active ? 'color: var(--color-primary);' : '' }}"></i>
                </button>
                <button wire:click="deleteLink({{ $link->id }})"
                        wire:confirm="Excluir este link?"
                        class="text-gray-300 hover:text-red-500 transition ml-1">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center gap-2 py-10 text-center">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                <i data-lucide="link" class="w-6 h-6 text-gray-400"></i>
            </div>
            <p class="text-sm font-medium text-gray-600">Nenhum link ainda</p>
            <p class="text-xs text-gray-400">Adicione links de redes sociais ou personalizados</p>
        </div>
        @endforelse
    </div>
</div>
