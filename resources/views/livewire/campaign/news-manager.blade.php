<div>
    @if (session('sucesso'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sucesso') }}</div>
    @endif

    @if (! $showForm)
        <button wire:click="startCreate" class="mb-4 px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">
            + Nova notícia
        </button>

        <div class="space-y-3">
            @forelse ($newsItems as $news)
                <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $news->title }}</p>
                        <p class="text-xs text-gray-500">{{ optional($news->published_at)->format('d/m/Y H:i') }} · {{ $news->is_active ? 'Publicada' : 'Rascunho' }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="startEdit({{ $news->id }})" class="text-xs text-gray-600 hover:text-gray-900">Editar</button>
                        <button wire:click="delete({{ $news->id }})" wire:confirm="Remover esta notícia?" class="text-xs text-red-600 hover:text-red-800">Remover</button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhuma notícia publicada ainda.</p>
            @endforelse
        </div>
    @else
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" wire:model="title" class="w-full rounded-lg border-gray-300 text-sm">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto</label>
                <textarea wire:model="body" rows="5" class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                @error('body') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Imagem de capa</label>
                <input type="file" wire:model="cover_image_upload" accept="image/*" class="block w-full text-sm">
                @error('cover_image_upload') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data de publicação</label>
                <input type="datetime-local" wire:model="published_at" class="w-full rounded-lg border-gray-300 text-sm">
                @error('published_at') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" wire:model="is_active"> Publicada
            </label>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">Salvar</button>
                <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700">Cancelar</button>
            </div>
        </form>
    @endif
</div>
