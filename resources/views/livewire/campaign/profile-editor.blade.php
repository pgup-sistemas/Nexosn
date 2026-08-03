<div>
    @if (session('sucesso'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
            {{ session('sucesso') }}
        </div>
    @endif

    <div class="mb-5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs px-4 py-3 leading-relaxed">
        <strong>Importante:</strong> você é o único responsável pelo conteúdo publicado neste cartão.
        Se for usar em uma <strong>eleição oficial</strong> (municipal, estadual ou federal), observe a
        legislação eleitoral e as normas vigentes do <strong>TSE</strong> — a NEXOSN não substitui
        assessoria jurídico-eleitoral. Leia os
        <a href="{{ route('legal.termos') }}#t10" target="_blank" class="underline font-medium">Termos de Uso — seção 10</a>.
    </div>

    <form wire:submit="save" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto de retrato</label>
            <input type="file" wire:model="portrait_photo_upload" accept="image/*"
                   class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-100 file:text-sm">
            @if ($card->campaignProfile?->portrait_photo)
                <img src="{{ Storage::url($card->campaignProfile->portrait_photo) }}" class="mt-2 w-24 h-24 rounded-lg object-cover" alt="Retrato atual">
            @endif
            @error('portrait_photo_upload') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome de campanha</label>
                <input type="text" wire:model="campaign_name" class="w-full rounded-lg border-gray-300 text-sm">
                @error('campaign_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                <input type="text" wire:model="role_title" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ex: Vereador, Presidente da CIPA...">
                @error('role_title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                <input type="text" wire:model="ballot_number" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Opcional">
                @error('ballot_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Organização</label>
                <input type="text" wire:model="organization_name" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ex: Sindicato X, Empresa Y...">
                @error('organization_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Afiliação (opcional)</label>
                <input type="text" wire:model="affiliation" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Partido, chapa, coligação...">
                @error('affiliation') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data-alvo (contador regressivo)</label>
                <input type="datetime-local" wire:model="countdown_target_at" class="w-full rounded-lg border-gray-300 text-sm">
                @error('countdown_target_at') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slogan</label>
            <input type="text" wire:model="slogan" class="w-full rounded-lg border-gray-300 text-sm">
            @error('slogan') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Endereço do comitê</label>
            <input type="text" wire:model="hq_address" class="w-full rounded-lg border-gray-300 text-sm">
            @error('hq_address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="border-t border-gray-200 pt-4">
            <p class="text-sm font-medium text-gray-700 mb-1">Responsável legal pelo conteúdo (opcional)</p>
            <p class="text-xs text-gray-500 mb-3">Preencha se exigido pela legislação eleitoral aplicável (ex: identificação do responsável em propaganda eleitoral). Quando preenchido, aparece no rodapé do cartão público.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do responsável</label>
                    <input type="text" wire:model="legal_responsible_name" class="w-full rounded-lg border-gray-300 text-sm">
                    @error('legal_responsible_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Documento (CPF/CNPJ)</label>
                    <input type="text" wire:model="legal_responsible_document" class="w-full rounded-lg border-gray-300 text-sm">
                    @error('legal_responsible_document') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background-color: var(--card-button, #D62828)">
            Salvar perfil de campanha
        </button>
    </form>
</div>
