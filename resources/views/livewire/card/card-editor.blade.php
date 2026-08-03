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
        <div x-data="{ len: {{ mb_strlen($slug) }} }">
            <div class="flex items-center justify-between">
                <label class="text-xs font-medium text-gray-600">Link do perfil</label>
                <span class="text-[11px] tabular-nums" :class="len > 50 ? 'text-red-500' : 'text-gray-400'" x-text="len + '/50'"></span>
            </div>
            <div class="flex items-center mt-1 rounded-lg border border-gray-300 bg-white overflow-hidden">
                <span class="px-3 py-2 text-xs text-gray-400 bg-gray-50 border-r border-gray-300 shrink-0 whitespace-nowrap">/u/</span>
                <input wire:model="slug" @input="len = $event.target.value.length" type="text" maxlength="50"
                       class="flex-1 px-3 py-2 text-sm focus:outline-none bg-white"
                       placeholder="seu-nome">
                <a href="{{ route('card.show', $card->slug) }}" target="_blank"
                   class="px-2 py-2 text-gray-400 hover:text-gray-600 shrink-0">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-400 mt-1">Apenas letras minúsculas, números e hífens (sem espaços ou acentos). Mín. 3, máx. 50 caracteres.</p>
            <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                <i data-lucide="alert-triangle" class="w-3 h-3 shrink-0"></i>
                Alterar o link invalida QR Codes e links já compartilhados.
            </p>
        </div>
    </div>

    {{-- Template do cartão --}}
    <div class="space-y-3">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="layout" class="w-4 h-4" style="color: var(--color-primary);"></i>
            Estilo do cartão
        </h3>
        <div class="grid grid-cols-2 gap-3">

            {{-- Default --}}
            <button type="button" wire:click="$set('template', 'default')"
                    class="relative rounded-xl border-2 overflow-hidden text-left transition-all
                           {{ $template === 'default' ? 'border-[#003049]' : 'border-gray-200 hover:border-gray-300' }}">
                <div class="bg-white h-20 flex flex-col gap-1 p-2.5">
                    <div class="w-8 h-8 rounded-full bg-gray-200"></div>
                    <div class="h-2 w-16 rounded bg-gray-200"></div>
                    <div class="h-1.5 w-10 rounded bg-gray-100"></div>
                    <div class="h-5 w-full rounded-lg mt-auto" style="background:#003049;opacity:.15;"></div>
                </div>
                <div class="px-3 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-700">Clássico</p>
                        <p class="text-[10px] text-gray-400">Fundo branco</p>
                    </div>
                    @if ($template === 'default')
                    <i data-lucide="check-circle" class="w-4 h-4" style="color:#003049;"></i>
                    @endif
                </div>
            </button>

            {{-- Dark --}}
            <button type="button" wire:click="$set('template', 'dark')"
                    class="relative rounded-xl border-2 overflow-hidden text-left transition-all
                           {{ $template === 'dark' ? 'border-[#C9A96E]' : 'border-gray-200 hover:border-gray-300' }}">
                <div class="h-20 flex flex-col gap-1 p-2.5" style="background:#0B0B0D;">
                    <div class="w-8 h-8 rounded-full" style="background:#1e1e24;border:1.5px solid rgba(201,169,110,.3);"></div>
                    <div class="h-2 w-16 rounded" style="background:#2a2a32;"></div>
                    <div class="h-1.5 w-10 rounded" style="background:#1e1e24;"></div>
                    <div class="h-5 w-full rounded-lg mt-auto" style="background:linear-gradient(90deg,#9A6E2E,#C9A96E);"></div>
                </div>
                <div class="px-3 py-2 border-t flex items-center justify-between" style="background:#141416;border-color:#1e1e24;">
                    <div>
                        <p class="text-xs font-semibold" style="color:#C9A96E;">Premium</p>
                        <p class="text-[10px]" style="color:#4a4a55;">Fundo escuro</p>
                    </div>
                    @if ($template === 'dark')
                    <i data-lucide="check-circle" class="w-4 h-4" style="color:#C9A96E;"></i>
                    @endif
                </div>
            </button>

            {{-- Campanha (Pro) --}}
            @if ($isPro)
                <button type="button" wire:click="$set('template', 'campaign-hero')"
                        class="relative rounded-xl border-2 overflow-hidden text-left transition-all
                               {{ $template === 'campaign-hero' ? 'border-[#D62828]' : 'border-gray-200 hover:border-gray-300' }}">
                    <div class="bg-white h-20 flex flex-col gap-1 p-2.5">
                        <div class="w-8 h-8 rounded-full bg-gray-200 mx-auto"></div>
                        <div class="h-2 w-16 rounded bg-gray-200 mx-auto"></div>
                        <div class="h-5 w-full rounded-lg mt-auto" style="background:#D62828;opacity:.15;"></div>
                    </div>
                    <div class="px-3 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Campanha — Hero</p>
                            <p class="text-[10px] text-gray-400">Eleições / chapas</p>
                        </div>
                        @if ($template === 'campaign-hero')
                        <i data-lucide="check-circle" class="w-4 h-4" style="color:#D62828;"></i>
                        @endif
                    </div>
                </button>

                <button type="button" wire:click="$set('template', 'campaign-institucional')"
                        class="relative rounded-xl border-2 overflow-hidden text-left transition-all
                               {{ $template === 'campaign-institucional' ? 'border-[#003049]' : 'border-gray-200 hover:border-gray-300' }}">
                    <div class="bg-white h-20 flex flex-col gap-1 p-2.5">
                        <div class="w-8 h-8 rounded-full bg-gray-200 mx-auto"></div>
                        <div class="h-2 w-16 rounded bg-gray-200 mx-auto"></div>
                        <div class="h-5 w-full rounded-lg mt-auto" style="background:#003049;opacity:.15;"></div>
                    </div>
                    <div class="px-3 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Campanha — Institucional</p>
                            <p class="text-[10px] text-gray-400">Sindicatos / associações</p>
                        </div>
                        @if ($template === 'campaign-institucional')
                        <i data-lucide="check-circle" class="w-4 h-4" style="color:#003049;"></i>
                        @endif
                    </div>
                </button>
            @endif
        </div>

        @if ($isPro)
            <div class="flex flex-wrap gap-2 pt-1">
                @foreach ([
                    'campaign-retrato' => 'Retrato',
                    'campaign-banner' => 'Banner',
                    'campaign-minimalista' => 'Minimalista',
                    'campaign-chapa' => 'Chapa',
                    'campaign-moderno' => 'Moderno',
                ] as $key => $label)
                    <button type="button" wire:click="$set('template', '{{ $key }}')"
                            class="px-3 py-1.5 rounded-full text-xs font-medium border transition-all
                                   {{ $template === $key ? 'border-[#D62828] text-[#D62828] bg-red-50' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                        Campanha — {{ $label }}
                    </button>
                @endforeach
            </div>
        @endif

        <p class="text-xs text-gray-400">O estilo é aplicado ao seu cartão público imediatamente.</p>
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
                <p class="text-xs font-medium text-gray-600">Foto de perfil <span class="text-gray-400 font-normal">(quadrado, recortado do topo)</span></p>
                <div class="relative">
                    @if ($card->profile_photo)
                        <img src="{{ Storage::url($card->profile_photo) }}"
                             alt="Foto de perfil"
                             class="w-full aspect-square object-cover object-top rounded-xl border border-gray-200">
                        <button wire:click="removeProfilePhoto"
                                wire:confirm="Remover foto de perfil?"
                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-red-500 shadow-md flex items-center justify-center hover:bg-red-600 transition z-10 text-white"
                                title="Remover foto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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
                    <input wire:model="profile_photo_upload" type="file" accept="image/png,image/jpeg,image/webp,image/gif,image/bmp"
                           class="block w-full text-[11px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                </label>
                <p class="text-[11px] text-gray-400">Formatos aceitos: JPG, PNG, WEBP, GIF ou BMP. Tamanho máximo: 2 MB.</p>
                @error('profile_photo_upload') <p class="text-[11px] text-red-500">{{ $message }}</p> @enderror
                <div wire:loading wire:target="profile_photo_upload" class="text-[11px] text-gray-400">Processando...</div>
            </div>

            {{-- Capa --}}
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-600">Foto de capa <span class="text-gray-400 font-normal">(panorâmica 3:1, recortada do centro)</span></p>
                <div class="relative">
                    @if ($card->cover_photo)
                        <img src="{{ Storage::url($card->cover_photo) }}"
                             alt="Foto de capa"
                             style="aspect-ratio:3/1"
                             class="w-full object-cover object-center rounded-xl border border-gray-200">
                        <button wire:click="removeCoverPhoto"
                                wire:confirm="Remover foto de capa?"
                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-red-500 shadow-md flex items-center justify-center hover:bg-red-600 transition z-10 text-white"
                                title="Remover capa">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    @else
                        <div style="aspect-ratio:3/1" class="w-full rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 flex flex-col items-center justify-center gap-1">
                            <i data-lucide="image" class="w-8 h-8 text-gray-300"></i>
                            <span class="text-[11px] text-gray-400">Sem capa</span>
                        </div>
                    @endif
                </div>
                <label class="block">
                    <span class="sr-only">Escolher foto de capa</span>
                    <input wire:model="cover_photo_upload" type="file" accept="image/png,image/jpeg,image/webp,image/gif,image/bmp"
                           class="block w-full text-[11px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                </label>
                <p class="text-[11px] text-gray-400">Formatos aceitos: JPG, PNG, WEBP, GIF ou BMP. Tamanho máximo: 4 MB.</p>
                @error('cover_photo_upload') <p class="text-[11px] text-red-500">{{ $message }}</p> @enderror
                <div wire:loading wire:target="cover_photo_upload" class="text-[11px] text-gray-400">Processando...</div>
            </div>

        </div>
        <p class="text-[11px] text-gray-400 flex items-center gap-1">
            <i data-lucide="info" class="w-3 h-3 shrink-0"></i>
            As fotos são salvas automaticamente ao clicar em "Salvar alterações". Fotos maiores são redimensionadas automaticamente.
        </p>
    </div>

    {{-- Identificação --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="user" class="w-4 h-4" style="color: var(--color-primary);"></i>
            Identificação
        </h3>

        <div class="grid grid-cols-1 gap-4">
            <div class="space-y-1" x-data="{ len: {{ mb_strlen($display_name) }} }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600">Nome de exibição *</label>
                    <span class="text-[11px] tabular-nums" :class="len > 70 ? 'text-amber-600' : 'text-gray-400'" x-text="len + '/80'"></span>
                </div>
                <input wire:model="display_name" @input="len = $event.target.value.length" type="text" maxlength="80"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('display_name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1" x-data="{ len: {{ mb_strlen($title) }} }">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-600">Cargo / Título</label>
                        <span class="text-[11px] tabular-nums" :class="len > 70 ? 'text-amber-600' : 'text-gray-400'" x-text="len + '/80'"></span>
                    </div>
                    <input wire:model="title" @input="len = $event.target.value.length" type="text" maxlength="80"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    @error('title')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1" x-data="{ len: {{ mb_strlen($company) }} }">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-600">Empresa</label>
                        <span class="text-[11px] tabular-nums" :class="len > 70 ? 'text-amber-600' : 'text-gray-400'" x-text="len + '/80'"></span>
                    </div>
                    <input wire:model="company" @input="len = $event.target.value.length" type="text" maxlength="80"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    @error('company')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-1" x-data="{ len: {{ mb_strlen($bio) }}, expanded: false }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600">Sobre (bio)</label>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] tabular-nums" :class="len > 450 ? 'text-amber-600' : 'text-gray-400'" x-text="len + '/500'"></span>
                        <button type="button" @click="expanded = !expanded"
                                class="text-gray-400 hover:text-gray-600" :title="expanded ? 'Diminuir campo' : 'Aumentar campo'">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5" x-show="!expanded"></i>
                            <i data-lucide="chevron-up" class="w-3.5 h-3.5" x-show="expanded" x-cloak></i>
                        </button>
                    </div>
                </div>
                <textarea wire:model="bio" @input="len = $event.target.value.length" maxlength="500"
                          :rows="expanded ? 10 : 3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 resize-y transition-all"
                          placeholder="Apresente-se brevemente..."></textarea>
                <p class="text-[11px] text-gray-400">Use a seta para expandir o campo, ou arraste o canto inferior direito para redimensionar.</p>
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
            <div class="space-y-1" x-data="{ len: {{ mb_strlen($contact_phone) }} }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600">Celular / WhatsApp</label>
                    <span class="text-[11px] tabular-nums text-gray-400" x-text="len + '/20'"></span>
                </div>
                <input wire:model="contact_phone" @input="len = $event.target.value.length" type="text" maxlength="20"
                       placeholder="(69) 99999-9999"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                <p class="text-[11px] text-gray-400">Inclua o DDD. Este número também é usado como WhatsApp no cartão de contato (.vcf).</p>
                @error('contact_phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1" x-data="{ len: {{ mb_strlen($contact_landline) }} }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600">Telefone fixo</label>
                    <span class="text-[11px] tabular-nums text-gray-400" x-text="len + '/20'"></span>
                </div>
                <input wire:model="contact_landline" @input="len = $event.target.value.length" type="text" maxlength="20"
                       placeholder="(69) 3222-0000"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                @error('contact_landline')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1" x-data="{ len: {{ mb_strlen($contact_email) }} }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600">E-mail de contato</label>
                    <span class="text-[11px] tabular-nums" :class="len > 240 ? 'text-amber-600' : 'text-gray-400'" x-text="len + '/255'"></span>
                </div>
                <input wire:model="contact_email" @input="len = $event.target.value.length" type="email" maxlength="255"
                       placeholder="voce@email.com"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                @error('contact_email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1" x-data="{ len: {{ mb_strlen($website) }} }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600">Website</label>
                    <span class="text-[11px] tabular-nums" :class="len > 240 ? 'text-amber-600' : 'text-gray-400'" x-text="len + '/255'"></span>
                </div>
                <input wire:model="website" @input="len = $event.target.value.length" type="url" maxlength="255"
                       placeholder="https://seusite.com.br"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                <p class="text-[11px] text-gray-400">Inclua "https://" no início do endereço.</p>
                @error('website')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1" x-data="{ len: {{ mb_strlen($address) }} }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5" style="color: var(--color-primary);"></i>
                        Endereço / Localização
                    </label>
                    <span class="text-[11px] tabular-nums" :class="len > 240 ? 'text-amber-600' : 'text-gray-400'" x-text="len + '/255'"></span>
                </div>
                <input wire:model="address" @input="len = $event.target.value.length" type="text" maxlength="255"
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
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1" x-data="{ len: {{ mb_strlen($city) }} }">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-600">Cidade</label>
                        <span class="text-[11px] tabular-nums text-gray-400" x-text="len + '/100'"></span>
                    </div>
                    <input wire:model="city" @input="len = $event.target.value.length" type="text" maxlength="100"
                           placeholder="Porto Velho"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    @error('city')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1" x-data="{ len: {{ mb_strlen($state) }} }">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-600">Estado (UF)</label>
                        <span class="text-[11px] tabular-nums text-gray-400" x-text="len + '/2'"></span>
                    </div>
                    <input wire:model="state" @input="len = $event.target.value.length" type="text" maxlength="2"
                           placeholder="RO"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:border-blue-500">
                    @error('state')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1" x-data="{ len: {{ mb_strlen($zip_code) }} }">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-600">CEP</label>
                        <span class="text-[11px] tabular-nums text-gray-400" x-text="len + '/9'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input wire:model="zip_code" @input="len = $event.target.value.length" wire:keydown.enter.prevent="buscarCep" type="text" maxlength="9"
                               placeholder="76800-000"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                        <button type="button" wire:click="buscarCep" wire:loading.attr="disabled" wire:target="buscarCep"
                                class="shrink-0 flex items-center gap-1 text-xs font-medium px-3 py-2 rounded-lg border border-gray-300 text-gray-600 transition hover:bg-gray-50 disabled:opacity-60">
                            <span wire:loading.remove wire:target="buscarCep">Buscar</span>
                            <span wire:loading wire:target="buscarCep">...</span>
                        </button>
                    </div>
                    @if ($cepErro)
                        <p class="text-xs text-amber-600">{{ $cepErro }}</p>
                    @endif
                    <p class="text-[11px] text-gray-400">Formato: 00000-000. Preenche cidade e estado automaticamente.</p>
                    @error('zip_code')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1" x-data="{ len: {{ mb_strlen($country) }} }">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-600">País</label>
                        <span class="text-[11px] tabular-nums text-gray-400" x-text="len + '/100'"></span>
                    </div>
                    <input wire:model="country" @input="len = $event.target.value.length" type="text" maxlength="100"
                           placeholder="Brasil"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    @error('country')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="space-y-1" x-data="{ len: {{ mb_strlen($pix_key) }} }">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-gray-600">Chave PIX</label>
                    <span class="text-[11px] tabular-nums text-gray-400" x-text="len + '/100'"></span>
                </div>
                <input wire:model="pix_key" @input="len = $event.target.value.length" type="text" maxlength="100"
                       placeholder="CPF, CNPJ, e-mail, telefone ou chave aleatória"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                <p class="text-[11px] text-gray-400">Aceita CPF, CNPJ, e-mail, telefone ou chave aleatória (UUID).</p>
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
                    <p class="text-[11px] text-gray-400">Formato hexadecimal, ex: #003049.</p>
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
                    <p class="text-[11px] text-gray-400">Formato hexadecimal, ex: #D62828.</p>
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
