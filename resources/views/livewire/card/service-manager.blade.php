<div class="space-y-4">

    @if (session('sucesso'))
    <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 font-medium">
        {{ session('sucesso') }}
    </div>
    @endif

    @if (session('erro'))
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 font-medium">
        {{ session('erro') }}
    </div>
    @endif

    {{-- Banner de limite Free --}}
    @if (!$this->canAddService())
    <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 flex items-start gap-3">
        <i data-lucide="zap" class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0"></i>
        <div>
            <p class="text-sm font-semibold text-amber-800">Limite de 3 serviços atingido</p>
            <p class="text-xs text-amber-700 mt-0.5">Faça upgrade para o plano <strong>Pro</strong> e cadastre serviços ilimitados.</p>
            <a href="{{ route('dashboard.plan') }}"
               class="inline-block mt-2 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 px-3 py-1.5 rounded-lg transition-colors">
                Ver planos
            </a>
        </div>
    </div>
    @endif

    {{-- Aviso chave PIX --}}
    @if (!$card->pix_key)
    <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800">
        <strong>Chave PIX não configurada.</strong>
        Configure a chave PIX no painel do cartão para que os visitantes possam pagar pelos serviços.
    </div>
    @endif

    {{-- Formulário --}}
    @if ($showForm)
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">
            {{ $editingId ? 'Editar serviço' : 'Novo serviço' }}
        </h3>

        <div class="space-y-3">
            {{-- Nome --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nome do serviço *</label>
                <input wire:model="name" type="text" maxlength="60" placeholder="Ex.: Consultoria, Corte de cabelo..."
                       class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-[#003049] focus:ring-2 focus:ring-[#003049]/10">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Descrição --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Descrição <span class="text-gray-400">(opcional)</span></label>
                <textarea wire:model="description" rows="2" maxlength="160" placeholder="Breve descrição do serviço..."
                          class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-[#003049] focus:ring-2 focus:ring-[#003049]/10 resize-none"></textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Valor + Ícone --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Valor (R$) *</label>
                    <input wire:model="price" type="number" step="0.01" min="0.01" max="99999.99" placeholder="0,00"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-[#003049] focus:ring-2 focus:ring-[#003049]/10">
                    @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Ícone Lucide</label>
                    <input wire:model="lucide_icon" type="text" placeholder="tag, scissors, wrench..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-[#003049] focus:ring-2 focus:ring-[#003049]/10">
                </div>
            </div>

            {{-- Ativo --}}
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input wire:model="is_active" type="checkbox"
                       class="rounded border-gray-300 text-[#003049] focus:ring-[#003049]">
                <span class="text-sm text-gray-700">Serviço visível no cartão</span>
            </label>
        </div>

        <div class="flex gap-2 mt-4">
            <button wire:click="save"
                    class="flex-1 rounded-xl bg-[#003049] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#00243a] transition-colors">
                {{ $editingId ? 'Salvar alterações' : 'Criar serviço' }}
            </button>
            <button wire:click="cancel"
                    class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                Cancelar
            </button>
        </div>
    </div>
    @endif

    {{-- Lista de serviços --}}
    @if ($services->isEmpty() && !$showForm)
    <div class="text-center py-10">
        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
            <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
        </div>
        <p class="text-sm text-gray-500">Nenhum serviço cadastrado.</p>
        <p class="text-xs text-gray-400 mt-1">Adicione serviços com valores para que clientes paguem via PIX.</p>
    </div>
    @endif

    @foreach ($services as $service)
    <div class="flex items-start gap-3 rounded-2xl border border-gray-200 bg-white p-4"
         wire:key="svc-{{ $service->id }}">

        {{-- Ícone --}}
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background-color: #003049;">
            <i data-lucide="{{ $service->lucide_icon }}" class="w-5 h-5" style="color:#fff;" wire:ignore></i>
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $service->name }}</p>
                @if (!$service->is_active)
                <span class="text-[10px] font-semibold bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full">Inativo</span>
                @endif
            </div>
            @if ($service->description)
            <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $service->description }}</p>
            @endif
            <p class="text-sm font-bold mt-1" style="color:#003049;">{{ $service->formatted_price }}</p>
        </div>

        {{-- Ações --}}
        <div class="flex flex-col gap-1.5">
            <button wire:click="startEdit({{ $service->id }})"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <i data-lucide="pencil" class="w-3.5 h-3.5" wire:ignore></i>
            </button>
            <button wire:click="toggleActive({{ $service->id }})"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                    title="{{ $service->is_active ? 'Desativar' : 'Ativar' }}">
                <i data-lucide="{{ $service->is_active ? 'eye-off' : 'eye' }}" class="w-3.5 h-3.5" wire:ignore></i>
            </button>
            <button wire:click="delete({{ $service->id }})"
                    wire:confirm="Remover o serviço '{{ $service->name }}'?"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                <i data-lucide="trash-2" class="w-3.5 h-3.5" wire:ignore></i>
            </button>
        </div>
    </div>
    @endforeach

    {{-- Botão adicionar --}}
    @if (!$showForm)
    <button wire:click="startCreate"
            class="w-full flex items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-gray-200
                   py-3.5 text-sm font-semibold text-gray-400 hover:border-[#003049] hover:text-[#003049]
                   transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Adicionar serviço
    </button>
    @endif

</div>

<script>
// Reinicializa ícones Lucide após renders do Livewire
document.addEventListener('livewire:updated', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>
