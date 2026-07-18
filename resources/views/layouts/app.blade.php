<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Painel' }} | Card</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script>document.addEventListener('DOMContentLoaded', () => { if (window.lucide) lucide.createIcons(); });</script>
</head>
<body class="min-h-screen antialiased" style="background-color: #f9fafb; font-family: 'Inter', sans-serif;" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         class="fixed inset-0 z-20 bg-black/50 md:hidden"
         @click="sidebarOpen = false"></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-200 ease-in-out md:relative md:translate-x-0 md:flex md:flex-col"
               style="background-color: var(--color-primary, #003049);">

            <div class="flex h-16 items-center px-6 shrink-0">
                <a href="{{ route('dashboard') }}" class="text-white text-xl font-semibold tracking-tight">Card</a>
            </div>

            <nav class="flex-1 mt-2 px-3 space-y-0.5 overflow-y-auto">
                <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="layout-dashboard" label="Visão Geral" />
                <x-sidebar-link href="{{ route('dashboard.card') }}" :active="request()->routeIs('dashboard.card')" icon="credit-card" label="Meu Cartão" />
                <x-sidebar-link href="{{ route('dashboard.links') }}" :active="request()->routeIs('dashboard.links')" icon="link" label="Links" />
                <x-sidebar-link href="{{ route('dashboard.photos') }}" :active="request()->routeIs('dashboard.photos')" icon="image" label="Galeria" />
                <x-sidebar-link href="{{ route('dashboard.messages') }}" :active="request()->routeIs('dashboard.messages')" icon="mail" label="Mensagens" />
                <x-sidebar-link href="{{ route('dashboard.schedule') }}" :active="request()->routeIs('dashboard.schedule')" icon="calendar" label="Agenda" />
                <x-sidebar-link href="{{ route('dashboard.appointments') }}" :active="request()->routeIs('dashboard.appointments')" icon="clock" label="Agendamentos" />
                <x-sidebar-link href="{{ route('dashboard.plan') }}" :active="request()->routeIs('dashboard.plan')" icon="zap" label="Plano" />
                <x-sidebar-link href="{{ route('dashboard.settings') }}" :active="request()->routeIs('dashboard.settings')" icon="settings" label="Configurações" />
            </nav>

            <div class="p-3 shrink-0">
                <a href="/u/{{ auth()->user()->card?->slug }}" target="_blank"
                   class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-xs text-white/60 hover:text-white hover:bg-white/10 transition-colors">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    Ver meu cartão
                </a>
            </div>
        </aside>

        {{-- Conteúdo --}}
        <div class="flex flex-col flex-1 min-w-0">
            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 sticky top-0 z-10 shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div class="flex items-center gap-3 ml-auto" x-data="{ open: false }">
                    @if(auth()->user()->plan === 'free')
                    <a href="{{ route('dashboard.plan') }}"
                       class="hidden sm:flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full text-white"
                       style="background-color: var(--color-highlight, #F77F00);">
                        <i data-lucide="zap" class="w-3 h-3"></i>
                        Upgrade Pro
                    </a>
                    @endif

                    <button @click="open = !open" class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0"
                             style="background-color: var(--color-primary, #003049);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-4 top-14 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 flex items-center gap-2">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
