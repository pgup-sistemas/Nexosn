<div>
    @if (session('sucesso'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sucesso') }}</div>
    @endif

    <form wire:submit="save" class="space-y-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do arquivo</label>
            <input type="text" wire:model="label" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ex: Plano de Gestão 2026">
            @error('label') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
            <select wire:model="category" class="w-full rounded-lg border-gray-300 text-sm">
                <option value="management_plan">Plano de Gestão</option>
                <option value="material">Material</option>
                <option value="other">Outro</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo (PDF)</label>
            <input type="file" wire:model="file_upload" accept="application/pdf" class="block w-full text-sm">
            @error('file_upload') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">Enviar</button>
    </form>

    <div class="space-y-2">
        @forelse ($files as $file)
            <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $file->label }}</p>
                    <p class="text-xs text-gray-500">{{ $file->category }}</p>
                </div>
                <button wire:click="delete({{ $file->id }})" wire:confirm="Remover este arquivo?" class="text-xs text-red-600 hover:text-red-800">Remover</button>
            </div>
        @empty
            <p class="text-sm text-gray-500">Nenhum arquivo enviado ainda.</p>
        @endforelse
    </div>
</div>
