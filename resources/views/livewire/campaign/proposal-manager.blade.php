<div>
    @if (session('sucesso'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sucesso') }}</div>
    @endif

    @if (! $showForm)
        <button wire:click="startCreate" class="mb-4 px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">
            + Nova proposta
        </button>

        <div class="space-y-3">
            @forelse ($proposals as $proposal)
                <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $proposal->title }}</p>
                        <p class="text-xs text-gray-500">{{ $proposal->category?->name ?? 'Sem categoria' }} · {{ $proposal->is_active ? 'Ativa' : 'Inativa' }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="startEdit({{ $proposal->id }})" class="text-xs text-gray-600 hover:text-gray-900">Editar</button>
                        <button wire:click="delete({{ $proposal->id }})" wire:confirm="Remover esta proposta?" class="text-xs text-red-600 hover:text-red-800">Remover</button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhuma proposta cadastrada ainda.</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select wire:model="category_id" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="">Sem categoria</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea wire:model="description" rows="4" class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Imagem</label>
                <input type="file" wire:model="image_upload" accept="image/*" class="block w-full text-sm">
                @error('image_upload') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vídeo (YouTube ou Vimeo)</label>
                <input type="url" wire:model="video_url" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://youtube.com/watch?v=...">
                @error('video_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">PDF</label>
                <input type="file" wire:model="pdf_upload" accept="application/pdf" class="block w-full text-sm">
                @error('pdf_upload') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" wire:model="is_active"> Ativa
            </label>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">Salvar</button>
                <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700">Cancelar</button>
            </div>
        </form>
    @endif
</div>
