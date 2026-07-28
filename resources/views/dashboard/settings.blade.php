<x-app-layout>
    <div class="space-y-6">

        <div>
            <h1 class="text-xl font-semibold text-gray-900">Configurações</h1>
            <p class="text-sm text-gray-500 mt-1">Gerencie suas informações de conta.</p>
        </div>

        @if (session('sucesso'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('sucesso') }}
            </div>
        @endif

        {{-- Informações do perfil --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Informações pessoais</h2>
            <livewire:profile.update-profile-information-form />
        </div>

        {{-- Alterar senha --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Alterar senha</h2>
            <livewire:profile.update-password-form />
        </div>

        {{-- Google Calendar --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#f1f3fe;">
                    <svg width="22" height="22" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M34 6H14C9.58 6 6 9.58 6 14v20c0 4.42 3.58 8 8 8h20c4.42 0 8-3.58 8-8V14c0-4.42-3.58-8-8-8z" fill="#fff"/>
                        <path d="M34 6H14C9.58 6 6 9.58 6 14v1h36v-1c0-4.42-3.58-8-8-8z" fill="#1a73e8"/>
                        <path d="M6 15h36v8H6z" fill="#1a73e8" opacity=".1"/>
                        <rect x="6" y="14" width="36" height="2" fill="#1a73e8"/>
                        <path d="M15 22h4v4h-4zm7 0h4v4h-4zm7 0h4v4h-4zM15 30h4v4h-4zm7 0h4v4h-4zm7 0h4v4h-4z" fill="#1a73e8"/>
                        <circle cx="15" cy="9" r="2" fill="#fff"/>
                        <circle cx="33" cy="9" r="2" fill="#fff"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-sm font-semibold text-gray-900">Google Calendar</h2>
                    <p class="text-xs text-gray-500 mt-0.5 mb-4">
                        Ao confirmar um agendamento, um evento é criado automaticamente no seu Google Calendar com lembrete e convite para o visitante.
                    </p>

                    @if(auth()->user()->google_calendar_token)
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-green-700 bg-green-50 border border-green-200">
                                <svg data-lucide="check-circle" class="w-3.5 h-3.5"></svg>
                                Conectado
                            </span>
                            <form method="POST" action="{{ route('dashboard.google.disconnect') }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition flex items-center gap-1.5">
                                    <svg data-lucide="unlink" class="w-3.5 h-3.5"></svg>
                                    Desconectar
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('dashboard.google.connect') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white transition"
                           style="background-color:#1a73e8;">
                            <svg data-lucide="calendar-plus" class="w-4 h-4"></svg>
                            Conectar Google Calendar
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Excluir conta (LGPD) --}}
        <div class="bg-white rounded-xl border border-red-100 p-6" x-data="{ confirmar: false }">
            <h2 class="text-sm font-semibold text-red-700 mb-1">Excluir conta</h2>
            <p class="text-xs text-gray-500 mb-4">
                Todos os seus dados serão apagados permanentemente. Esta ação é irreversível.
            </p>

            <button @click="confirmar = true" x-show="!confirmar"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition flex items-center gap-2">
                <svg data-lucide="trash-2" class="w-4 h-4"></svg>
                Excluir minha conta
            </button>

            <div x-show="confirmar" x-transition class="mt-2">
                <p class="text-sm font-medium text-red-700 mb-3">Confirme sua senha para continuar:</p>
                <form method="POST" action="{{ route('dashboard.settings.account.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center gap-3 flex-wrap">
                        <input type="password" name="password"
                               class="flex-1 min-w-[160px] px-3 py-2 text-sm border border-red-300 rounded-lg focus:outline-none focus:border-red-500"
                               placeholder="Sua senha atual" required>
                        <button type="submit"
                                class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">
                            Confirmar
                        </button>
                        <button type="button" @click="confirmar = false"
                                class="px-4 py-2 rounded-lg text-sm text-gray-600 hover:text-gray-800">
                            Cancelar
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
