<x-app-layout>
    <div>
        <div class="mb-5">
            <h1 class="text-xl font-semibold text-gray-900">Propostas</h1>
            <p class="text-sm text-gray-500 mt-1">Cadastre as propostas exibidas no seu cartão de campanha.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            @php $card = auth()->user()->card; @endphp
            <livewire:campaign.proposal-manager :card="$card" />
        </div>
    </div>
</x-app-layout>
