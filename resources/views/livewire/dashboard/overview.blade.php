<div class="space-y-6">

    {{-- Banner de Trial --}}
    @if ($isPro && $trialEndsAt)
    <div class="flex items-center gap-3 p-4 rounded-xl text-white"
         style="background: linear-gradient(135deg, var(--color-primary), #004f7a);">
        <i data-lucide="zap" class="w-5 h-5 shrink-0 text-yellow-300"></i>
        <div class="flex-1">
            <p class="text-sm font-semibold">Trial Pro ativo</p>
            <p class="text-xs opacity-80 mt-0.5">Expira em {{ $trialEndsAt->diffForHumans() }} · {{ $trialEndsAt->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('dashboard.plan') }}"
           class="shrink-0 text-xs font-medium px-3 py-1.5 rounded-lg bg-white transition hover:bg-gray-100"
           style="color: var(--color-primary);">
            Assinar Pro
        </a>
    </div>
    @elseif (!$isPro)
    <div class="flex items-center gap-3 p-4 rounded-xl border border-amber-200 bg-amber-50">
        <i data-lucide="zap" class="w-5 h-5 shrink-0 text-amber-500"></i>
        <div class="flex-1">
            <p class="text-sm font-semibold text-amber-800">Plano Free</p>
            <p class="text-xs text-amber-700 mt-0.5">Faça upgrade para cores personalizadas, galeria ilimitada e agenda.</p>
        </div>
        <a href="{{ route('dashboard.plan') }}"
           class="shrink-0 text-xs font-medium px-3 py-1.5 rounded-lg text-white transition hover:opacity-90"
           style="background-color: var(--color-highlight);">
            Upgrade
        </a>
    </div>
    @endif

    {{-- Cartão em destaque --}}
    @if ($card)
    <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-200">
        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-lg font-bold shrink-0"
             style="background-color: var(--color-primary);">
            {{ strtoupper(substr($card->display_name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-gray-900 truncate">{{ $card->display_name }}</p>
            <p class="text-sm text-gray-500 truncate">{{ $card->title }}{{ $card->title && $card->company ? ' · ' : '' }}{{ $card->company }}</p>
            <div class="flex items-center gap-1.5 mt-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full {{ $card->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                <span class="text-xs text-gray-500">{{ $card->is_active ? 'Ativo' : 'Inativo' }} · /u/{{ $card->slug }}</span>
            </div>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('card.show', $card->slug) }}" target="_blank"
               class="text-gray-400 hover:text-gray-600 transition p-1.5 rounded-lg hover:bg-gray-100">
                <i data-lucide="external-link" class="w-4 h-4"></i>
            </a>
            <a href="{{ route('dashboard.card') }}"
               class="text-gray-400 hover:text-gray-600 transition p-1.5 rounded-lg hover:bg-gray-100">
                <i data-lucide="pencil" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
    @endif

    {{-- Métricas --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Visualizações totais</p>
            <p class="text-2xl font-bold mt-1" style="color: var(--color-primary);">{{ number_format($stats['views_total']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Últimos 7 dias</p>
            <p class="text-2xl font-bold mt-1" style="color: var(--color-primary);">{{ number_format($stats['views_7days']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Links ativos</p>
            <p class="text-2xl font-bold mt-1" style="color: var(--color-primary);">{{ $stats['links_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Fotos</p>
            <p class="text-2xl font-bold mt-1" style="color: var(--color-primary);">{{ $stats['photos_count'] }}</p>
        </div>
    </div>

    {{-- Acesso rápido --}}
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('dashboard.card') }}"
           class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition group">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 group-hover:opacity-90 transition"
                 style="background-color: var(--color-primary);">
                <i data-lucide="credit-card" class="w-4 h-4 text-white"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">Editar cartão</p>
                <p class="text-xs text-gray-500">Nome, bio, contato</p>
            </div>
        </a>
        <a href="{{ route('dashboard.links') }}"
           class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition group">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 group-hover:opacity-90 transition"
                 style="background-color: var(--color-action);">
                <i data-lucide="link" class="w-4 h-4 text-white"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">Links</p>
                <p class="text-xs text-gray-500">{{ $stats['links_count'] }} de {{ $isPro ? '∞' : '5' }} links</p>
            </div>
        </a>
        <a href="{{ route('dashboard.photos') }}"
           class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition group">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 group-hover:opacity-90 transition"
                 style="background-color: var(--color-highlight);">
                <i data-lucide="image" class="w-4 h-4 text-white"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">Galeria</p>
                <p class="text-xs text-gray-500">{{ $stats['photos_count'] }} foto{{ $stats['photos_count'] != 1 ? 's' : '' }}</p>
            </div>
        </a>
        <a href="{{ route('dashboard.plan') }}"
           class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition group">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 group-hover:opacity-90 transition"
                 style="background-color: var(--color-accent);">
                <i data-lucide="zap" class="w-4 h-4" style="color: var(--color-primary);"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">Plano</p>
                <p class="text-xs text-gray-500">{{ $planLabel }}</p>
            </div>
        </a>
    </div>

</div>
