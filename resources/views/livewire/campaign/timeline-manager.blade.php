<div>
    @if (session('sucesso'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sucesso') }}</div>
    @endif

    @if (! $showForm)
        <button wire:click="startCreate" class="mb-4 px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">
            + Novo evento
        </button>

        <div class="space-y-3">
            @forelse ($items as $item)
                <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $item->title }}</p>
                        <p class="text-xs text-gray-500">{{ $item->occurred_on->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="startEdit({{ $item->id }})" class="text-xs text-gray-600 hover:text-gray-900">Editar</button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Remover este evento?" class="text-xs text-red-600 hover:text-red-800">Remover</button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhum evento na linha do tempo ainda.</p>
            @endforelse
        </div>
    @else
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                <input type="date" wire:model="occurred_on" class="w-full rounded-lg border-gray-300 text-sm">
                @error('occurred_on') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" wire:model="title" class="w-full rounded-lg border-gray-300 text-sm">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea wire:model="description" rows="3" class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">Salvar</button>
                <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700">Cancelar</button>
            </div>
        </form>
    @endif
</div>
