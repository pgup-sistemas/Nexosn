<div>
    @if (session('sucesso'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sucesso') }}</div>
    @endif

    @if (! $showForm)
        <button wire:click="startCreate" class="mb-4 px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">
            + Novo membro
        </button>

        <div class="space-y-3">
            @forelse ($members as $member)
                <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        @if ($member->photo)
                            <img src="{{ Storage::url($member->photo) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $member->name }}">
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->role }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="startEdit({{ $member->id }})" class="text-xs text-gray-600 hover:text-gray-900">Editar</button>
                        <button wire:click="delete({{ $member->id }})" wire:confirm="Remover este membro?" class="text-xs text-red-600 hover:text-red-800">Remover</button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhum membro cadastrado ainda.</p>
            @endforelse
        </div>
    @else
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 text-sm">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Função</label>
                <input type="text" wire:model="role" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ex: Vice, Secretário(a)...">
                @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                <input type="file" wire:model="photo_upload" accept="image/*" class="block w-full text-sm">
                @error('photo_upload') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" wire:model="is_active"> Ativo
            </label>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">Salvar</button>
                <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700">Cancelar</button>
            </div>
        </form>
    @endif
</div>
