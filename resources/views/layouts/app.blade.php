<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Painel' }} | NEXOSN</title>
    <meta name="description" content="Painel NEXOSN — gerencie sua identidade digital, links, agenda e muito mais.">
    <meta name="application-name" content="NEXOSN">
    <meta name="theme-color" content="#003049">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192.png">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
        document.addEventListener('livewire:navigated', () => lucide.createIcons());
        document.addEventListener('livewire:update', () => lucide.createIcons());
    </script>
    <style>
        /* Transição suave da sidebar */
        #app-sidebar { transition: width 220ms cubic-bezier(.4,0,.2,1); }
        #app-sidebar .sidebar-label { transition: opacity 150ms ease, width 150ms ease; }
        #app-sidebar .sidebar-logo-text { transition: opacity 150ms ease, max-width 150ms ease; }

        /* Tooltip ao recolher — aparece ao lado do ícone */
        #app-sidebar.collapsed .sidebar-tooltip {
            display: flex !important;
        }
        .sidebar-tooltip {
            display: none;
            position: absolute;
            left: calc(100% + 10px);
            top: 50%; transform: translateY(-50%);
            background: #1a2f3e;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
        }
        .sidebar-tooltip::before {
            content: '';
            position: absolute;
            left: -5px; top: 50%; transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1a2f3e;
            border-left: 0;
        }
    </style>
</head>
<body class="min-h-screen antialiased" style="background-color: #f0f2f5; font-family: 'Inter', sans-serif;"
      x-data="{
          mobileOpen: false,
          collapsed: localStorage.getItem('sidebar_collapsed') === 'true',
          toggleCollapse() {
              this.collapsed = !this.collapsed;
              localStorage.setItem('sidebar_collapsed', this.collapsed);
          }
      }">

    {{-- Mobile overlay --}}
    <div x-show="mobileOpen"
         x-transition.opacity
         class="fixed inset-0 z-20 bg-black/50 md:hidden"
         @click="mobileOpen = false"></div>

    @if (session('impersonator_id'))
    <div class="sticky top-0 z-40 flex items-center justify-center gap-3 px-4 py-2 text-xs font-medium text-white"
         style="background-color:#D62828;">
        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
        Você está navegando como <strong>{{ auth()->user()->name }}</strong>
        <form method="POST" action="{{ route('impersonate.stop') }}" class="inline">
            @csrf
            <button class="underline hover:opacity-80">Voltar para admin</button>
        </form>
    </div>
    @endif

    <div class="flex min-h-screen">

        {{-- ── SIDEBAR ── --}}
        <aside id="app-sidebar"
               :class="[
                   mobileOpen ? 'translate-x-0' : '-translate-x-full',
                   collapsed ? 'md:w-[64px]' : 'md:w-64',
               ]"
               :data-collapsed="collapsed"
               class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col
                      transform transition-transform duration-200 ease-in-out
                      md:relative md:translate-x-0"
               style="background-color:#003049;">

            {{-- Logo + botão recolher --}}
            <div class="h-16 flex items-center shrink-0 border-b border-white/10 relative"
                 :class="collapsed ? 'justify-center px-0' : 'px-4'">

                {{-- Logo (some quando recolhido) --}}
                <a href="{{ route('dashboard') }}" x-show="!collapsed"
                   class="flex items-center gap-2 text-white font-black tracking-widest shrink-0 flex-1"
                   style="font-size:15px;letter-spacing:.12em;text-decoration:none;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="color:#FCBF49;flex-shrink:0;">
                        <line x1="5" y1="5"  x2="5"  y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                        <line x1="5" y1="5"  x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                        <line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                        <circle cx="5"  cy="5"  r="2.75" fill="currentColor"/>
                        <circle cx="5"  cy="19" r="2.75" fill="currentColor"/>
                        <circle cx="19" cy="5"  r="2.75" fill="currentColor"/>
                        <circle cx="19" cy="19" r="2.75" fill="currentColor"/>
                    </svg>
                    NEX<span style="opacity:.55;font-weight:700;">OSN</span>
                </a>

                {{-- Botão recolher (só desktop) --}}
                <button @click="toggleCollapse()"
                        class="hidden md:flex items-center justify-center rounded-lg transition-colors shrink-0"
                        :class="collapsed ? 'w-9 h-9 hover:bg-white/10' : 'w-8 h-8 hover:bg-white/10'"
                        :title="collapsed ? 'Expandir menu' : 'Recolher menu'">
                    <svg x-show="!collapsed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.55)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18"/><path d="m14 9-3 3 3 3"/>
                    </svg>
                    <svg x-show="collapsed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18"/><path d="m11 15 3-3-3-3"/>
                    </svg>
                </button>
            </div>

            {{-- Nav --}}
            @php
                $unreadMessages = auth()->user()->card
                    ? auth()->user()->card->messages()->whereNull('read_at')->count()
                    : 0;
            @endphp
            <nav class="flex-1 py-2 overflow-y-auto overflow-x-hidden"
                 :class="collapsed ? 'px-0' : 'px-2'">

                @php
                $navItems = [
                    ['route' => 'dashboard',              'routeIs' => 'dashboard',              'icon' => 'layout-dashboard', 'label' => 'Visão Geral'],
                    ['route' => 'dashboard.card',         'routeIs' => 'dashboard.card',         'icon' => 'credit-card',      'label' => 'Meu Cartão'],
                    ['route' => 'dashboard.links',        'routeIs' => 'dashboard.links',        'icon' => 'link',             'label' => 'Links'],
                    ['route' => 'dashboard.photos',       'routeIs' => 'dashboard.photos',       'icon' => 'image',            'label' => 'Galeria'],
                    ['route' => 'dashboard.messages',     'routeIs' => 'dashboard.messages',     'icon' => 'mail',             'label' => 'Mensagens', 'badge' => $unreadMessages ?: null],
                    ['route' => 'dashboard.schedule',     'routeIs' => 'dashboard.schedule',     'icon' => 'calendar',         'label' => 'Agenda'],
                    ['route' => 'dashboard.appointments', 'routeIs' => 'dashboard.appointments', 'icon' => 'clock',            'label' => 'Agendamentos'],
                    ['route' => 'dashboard.services',     'routeIs' => 'dashboard.services',     'icon' => 'receipt',          'label' => 'Serviços / PIX'],
                    ['route' => 'dashboard.share',        'routeIs' => 'dashboard.share',        'icon' => 'share-2',          'label' => 'Compartilhar'],
                    ['route' => 'dashboard.plan',         'routeIs' => 'dashboard.plan',         'icon' => 'zap',              'label' => 'Plano'],
                    ['route' => 'dashboard.settings',     'routeIs' => 'dashboard.settings',     'icon' => 'settings',         'label' => 'Configurações'],
                ];
                @endphp

                @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['routeIs']); @endphp
                <a href="{{ route($item['route']) }}" wire:navigate
                   class="relative flex items-center gap-3 rounded-lg text-sm font-medium transition-colors group mb-0.5"
                   :class="collapsed ? 'justify-center mx-auto' : 'px-3 py-2 mx-0'"
                   :style="collapsed ? 'width:40px;height:40px;' : ''"
                   style="{{ $isActive ? 'background:rgba(255,255,255,.15);color:#fff;' : 'color:rgba(255,255,255,.65);' }}"
                   @mouseenter="$el.style.color='#fff'; if(!{{ $isActive ? 'true' : 'false' }}) $el.style.background='rgba(255,255,255,.08)'"
                   @mouseleave="$el.style.color='{{ $isActive ? '#fff' : 'rgba(255,255,255,.65)' }}'; if(!{{ $isActive ? 'true' : 'false' }}) $el.style.background='transparent'">

                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>

                    {{-- Label (visível quando expandido) --}}
                    <span class="sidebar-label flex-1 whitespace-nowrap overflow-hidden"
                          :style="collapsed ? 'opacity:0;width:0;overflow:hidden;position:absolute' : 'opacity:1;width:auto;position:static'">
                        {{ $item['label'] }}
                    </span>

                    {{-- Badge --}}
                    @if (!empty($item['badge']))
                    <span class="ml-auto min-w-[18px] h-[18px] flex items-center justify-center rounded-full text-[10px] font-bold text-white px-1 shrink-0"
                          :style="collapsed ? 'display:none' : ''"
                          style="background-color:#D62828;">
                        {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                    </span>
                    {{-- Badge colapsado — pontinho vermelho --}}
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-red-500"
                          :style="collapsed ? '' : 'display:none'"></span>
                    @endif

                    {{-- Tooltip quando recolhido --}}
                    <span class="sidebar-tooltip">{{ $item['label'] }}</span>
                </a>
                @endforeach
            </nav>

            {{-- Ver cartão --}}
            <div class="shrink-0 border-t border-white/10 p-2">
                <a href="/u/{{ auth()->user()->card?->slug }}" target="_blank"
                   class="relative flex items-center gap-2 rounded-lg text-xs text-white/60 hover:text-white hover:bg-white/10 transition-colors group"
                   :class="collapsed ? 'justify-center w-10 h-10 mx-auto' : 'px-3 py-2.5'">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0"></i>
                    <span :style="collapsed ? 'opacity:0;width:0;overflow:hidden;position:absolute' : 'opacity:1;width:auto;position:static'">Ver meu cartão</span>
                    <span class="sidebar-tooltip">Ver meu cartão</span>
                </a>
            </div>
        </aside>

        {{-- ── CONTEÚDO ── --}}
        <div class="flex flex-col flex-1 min-w-0">

            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 sticky top-0 z-10 shrink-0">
                {{-- Botão mobile --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-500 hover:text-gray-700 -ml-1">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div class="flex-1"></div>

                <div class="flex items-center gap-3" x-data="{ open: false }">
                    @if(auth()->user()->plan === 'free')
                    <a href="{{ route('dashboard.plan') }}"
                       class="hidden sm:flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full text-white"
                       style="background: linear-gradient(100deg, #F77F00, #FCBF49); color: #431900;">
                        <i data-lucide="zap" class="w-3 h-3"></i>
                        Upgrade Pro
                    </a>
                    @endif

                    <div class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-2 rounded-full hover:ring-2 hover:ring-offset-1 transition-all"
                                style="--tw-ring-color: #003049;">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                 style="background-color:#003049;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 top-11 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard.settings') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                                Configurações
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 flex items-center gap-2">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 md:p-6 w-full max-w-[1440px] mx-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
