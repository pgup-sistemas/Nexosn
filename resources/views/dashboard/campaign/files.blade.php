<x-app-layout>
    <div>
        <div class="mb-5">
            <h1 class="text-xl font-semibold text-gray-900">Plano de Gestão e Materiais</h1>
            <p class="text-sm text-gray-500 mt-1">Documentos e materiais para download no seu cartão de campanha.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            @php $card = auth()->user()->card; @endphp
            <livewire:campaign.file-manager :card="$card" />
        </div>
    </div>
</x-app-layout>
