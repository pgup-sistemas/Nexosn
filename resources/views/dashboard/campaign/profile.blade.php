<x-app-layout>
    <div>
        <div class="mb-5">
            <h1 class="text-xl font-semibold text-gray-900">Perfil de Campanha</h1>
            <p class="text-sm text-gray-500 mt-1">Dados exibidos no template de campanha do seu cartão.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            @php $card = auth()->user()->card; @endphp
            <livewire:campaign.profile-editor :card="$card" />
        </div>
    </div>
</x-app-layout>
