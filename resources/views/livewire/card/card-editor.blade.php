<div class="space-y-6">

    @if (session('sucesso'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            {{ session('sucesso') }}
        </div>
    @endif

    {{-- Status + Slug --}}
    <div class="space-y-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-700">Status do perfil</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $card->is_active ? 'Visível publicamente' : 'Perfil desativado' }}
                </p>
            </div>
            <button wire:click="toggleActive"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                    style="background-color: {{ $card->is_active ? 'var(--color-primary)' : '#D1D5DB' }};">
                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow"
                      style="transform: translateX({{ $card->is_active ? '20px' : '4px' }})"></span>
            </button>
        </div>

        {{-- Editar slug --}}
        <div>
            <label class="text-xs font-medium text-gray-600">Link do perfil</label>
            <div class="flex items-center mt-1 rounded-lg border border-gray-300 bg-white overflow-hidden">
                <span class="px-3 py-2 text-xs text-gray-400 bg-gray-50 border-r border-gray-300 shrink-0 whitespace-nowrap">/u/</span>
                <input wire:model="slug" type="text"
                       class="flex-1 px-3 py-2 text-sm focus:outline-none bg-white"
                       placeholder="seu-nome">
                <a href="{{ route('card.show', $card->slug) }}" target="_blank"
                   class="px-2 py-2 text-gray-400 hover:text-gray-600 shrink-0">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-400 mt-1">Apenas letras minúsculas, números e hífens. Mín. 3 caracteres.</p>
        </div>
    </div>

    {{-- Fotos: Avatar e Capa --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="image" class="w-4 h-4" style="color: var(--color-primary);"></i>
            Fotos do cartão
        </h3>

        <div class="grid grid-cols-2 gap-4">

            {{-- Avatar --}}
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-600">Foto de perfil</p>
                <div class="relative">
                    @if ($card->profile_photo)
                        <img src="{{ Storage::url($card->profile_photo) }}"
                             alt="Foto de perfil"
                             class="w-full aspect-square object-cover rounded-xl border border-gray-200">
                        <button wire:click="removeProfilePhoto"
                                wire:confirm="Remover foto de perfil?"
                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-white shadow flex items-center justify-center hover:bg-red-50 transition">
                            <i data-lucide="x" class="w-3 h-3 text-red-500"></i>
                        </button>
                    @else
                        <div class="w-full aspect-square rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 flex flex-col items-center justify-center gap-1">
                            <i data-lucide="user-circle" class="w-8 h-8 text-gray-300"></i>
                            <span class="text-[11px] text-gray-400">Sem foto</span>
                        </div>
                    @endif
                </div>
                <label class="block">
                    <span class="sr-only">Escolher foto de perfil</span>
                    <input wire:model="profile_photo_upload" type="file" accept="image/*"
                           class="block w-full text-[11px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                </label>
                @error('profile_photo_upload') <p class="text-[11px] text-red-500">{{ $message }}</p> @enderror
                <div wire:loading wire:target="profile_photo_upload" class="text-[11px] text-gray-400">Carregando...</div>
            </div>

            {{-- Capa --}}
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-600">Foto de capa</p>
                <div class="relative">
                    @if ($card->cover_photo)
                        <img src="{{ Storage::url($card->cover_photo) }}"
                             alt="Foto de capa"
                             class="w-full aspect-square object-cover rounded-xl border border-gray-200">
                        <button wire:click="removeCoverPhoto"
                                wire:confirm="Remover foto de capa?"
                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-white shadow flex items-center justify-center hover:bg-red-50 transition">
                            <i data-lucide="x" class="w-3 h-3 text-red-500"></i>
                        </button>
                    @else
                        <div class="w-full aspect-square rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 flex flex-col items-center justify-center gap-1">
                            <i data-lucide="image" class="w-8 h-8 text-gray-300"></i>
                            <span class="text-[11px] text-gray-400">Sem capa</span>
                        </div>
                    @endif
                </div>
                <label class="block">
                    <span class="sr-only">Escolher foto de capa</span>
                    <input wire:model="cover_photo_upload" type="file" accept="image/*"
                           class="block w-full text-[11px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                </label>
                @error('cover_photo_upload') <p class="text-[11px] text-red-500">{{ $message }}</p> @enderror
                <div wire:loading wire:target="cover_photo_upload" class="text-[11px] text-gray-400">Carregando...</div>
            </div>

        </div>
        <p class="text-[11px] text-gray-400">As fotos são salvas automaticamente ao clicar em "Salvar alterações".</p>
    </div>

    {{-- Identificação --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="user" class="w-4 h-4" style="color: var(--color-primary);"></i>
            Identificação
        </h3>

        <div class="grid grid-cols-1 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">Nome de exibição *</label>
                <input wire:model="display_name" type="text" maxlength="80"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('display_name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-600">Cargo / Título</label>
                    <input wire:model="title" type="text" maxlength="80"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    @error('title')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-600">Empresa</label>
                    <input wire:model="company" type="text" maxlength="80"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    @error('company')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">Sobre (bio)</label>
                <textarea wire:model="bio" rows="3" maxlength="500"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 resize-none"
                          placeholder="Apresente-se brevemente..."></textarea>
                @error('bio')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Contato --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="phone" class="w-4 h-4" style="color: var(--color-primary);"></i>
            Contato
        </h3>

        <div class="grid grid-cols-1 gap-3">
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">Telefone / WhatsApp</label>
                <input wire:model="contact_phone" type="text" maxlength="20"
                       placeholder="(69) 99999-9999"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                @error('contact_phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">E-mail de contato</label>
                <input wire:model="contact_email" type="email" maxlength="255"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                @error('contact_email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">Website</label>
                <input wire:model="website" type="url" maxlength="255"
                       placeholder="https://seusite.com.br"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                @error('website')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600 flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5" style="color: var(--color-primary);"></i>
                    Endereço / Localização
                </label>
                <input wire:model="address" type="text" maxlength="255"
                       placeholder="Ex: Av. Lauro Sodré, 1234, Porto Velho, RO"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                @if ($card->address)
                <a href="https://maps.google.com/?q={{ urlencode($card->address) }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:underline mt-0.5">
                    <i data-lucide="external-link" class="w-3 h-3"></i>
                    Ver no Google Maps
                </a>
                @else
                <p class="text-[11px] text-gray-400 mt-0.5">
                    Um botão de mapa será exibido automaticamente no seu cartão.
                </p>
                @endif
                @error('address')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">Chave PIX</label>
                <input wire:model="pix_key" type="text" maxlength="100"
                       placeholder="CPF, CNPJ, e-mail, telefone ou chave aleatória"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                @error('pix_key')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Cores de marca (Pro) --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="palette" class="w-4 h-4" style="color: var(--color-primary);"></i>
            Cores de marca
            @if (!$isPro)
                <span class="ml-auto text-xs font-medium px-2 py-0.5 rounded-full text-white" style="background-color: var(--color-highlight);">Pro</span>
            @endif
        </h3>

        @if ($isPro)
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-xs font-medium text-gray-600">Cor primária (header)</label>
                    <div class="flex items-center gap-2">
                        <input wire:model.live="brand_color_primary" type="color"
                               class="w-10 h-10 rounded-lg cursor-pointer border border-gray-300 p-0.5"
                               value="{{ $brand_color_primary }}">
                        <input wire:model.live="brand_color_primary" type="text" maxlength="7"
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:border-blue-500">
                    </div>
                    @error('brand_color_primary')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-medium text-gray-600">Cor dos botões</label>
                    <div class="flex items-center gap-2">
                        <input wire:model.live="brand_color_button" type="color"
                               class="w-10 h-10 rounded-lg cursor-pointer border border-gray-300 p-0.5"
                               value="{{ $brand_color_button }}">
                        <input wire:model.live="brand_color_button" type="text" maxlength="7"
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:border-blue-500">
                    </div>
                    @error('brand_color_button')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        @else
            <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <i data-lucide="lock" class="w-5 h-5 text-amber-600 shrink-0"></i>
                <div>
                    <p class="text-sm font-medium text-amber-800">Recurso Pro</p>
                    <p class="text-xs text-amber-700 mt-0.5">Personalize as cores do seu cartão no plano Pro.</p>
                </div>
                <a href="{{ route('dashboard.plan') }}"
                   class="ml-auto shrink-0 text-xs font-medium px-3 py-1.5 rounded-lg text-white transition hover:opacity-90"
                   style="background-color: var(--color-highlight);">
                    Upgrade
                </a>
            </div>
        @endif
    </div>

    {{-- Salvar --}}
    <div class="pt-2">
        <button wire:click="save"
                wire:loading.attr="disabled"
                class="w-full text-white text-sm font-medium rounded-xl py-3 transition hover:opacity-90 disabled:opacity-60 flex items-center justify-center gap-2"
                style="background-color: var(--color-primary);">
            <span wire:loading.remove>Salvar alterações</span>
            <span wire:loading class="flex items-center gap-2">
                <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                Salvando...
            </span>
        </button>
    </div>

</div>
