<x-app-layout>
    <div class="max-w-2xl">
        <div class="mb-5">
            <h1 class="text-xl font-semibold text-gray-900">Olá, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-sm text-gray-500 mt-1">Aqui está o resumo do seu cartão.</p>
        </div>

        <livewire:dashboard.overview />
    </div>
</x-app-layout>
