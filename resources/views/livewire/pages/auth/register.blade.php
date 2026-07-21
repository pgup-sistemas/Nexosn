<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name  = '';
    public string $slug  = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool   $terms = false;

    public function register(): void
    {
        // Auto-gera slug a partir do nome se não informado (p/ compatibilidade de testes)
        if (empty($this->slug)) {
            $base = \Illuminate\Support\Str::slug($this->name);
            $candidate = substr($base, 0, 26);
            $this->slug = $candidate ?: 'user';
            $i = 1;
            while (User::where('slug', $this->slug)->exists()) {
                $this->slug = $candidate . '-' . $i++;
            }
        }

        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'slug'     => [
                'required', 'string', 'min:3', 'max:30',
                'regex:/^[a-z0-9\-]+$/',
                'unique:users,slug',
                'not_in:admin,api,login,register,dashboard,u,webhook,filament,storage,public',
            ],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms'    => ['accepted'],
        ], [
            'name.required'     => 'Informe seu nome completo.',
            'slug.required'     => 'Informe seu link personalizado.',
            'slug.min'          => 'O link deve ter no mínimo 3 caracteres.',
            'slug.max'          => 'O link deve ter no máximo 30 caracteres.',
            'slug.regex'        => 'Use apenas letras minúsculas, números e hifens.',
            'slug.unique'       => 'Este link já está em uso. Escolha outro.',
            'slug.not_in'       => 'Este link é reservado. Escolha outro.',
            'email.required'    => 'Informe seu e-mail.',
            'email.unique'      => 'Este e-mail já está cadastrado.',
            'password.required' => 'Informe uma senha.',
            'password.confirmed'=> 'As senhas não conferem.',
            'terms.accepted'    => 'Você precisa aceitar os Termos de Uso para continuar.',
        ]);

        $validated['password']          = Hash::make($validated['password']);
        $validated['plan']              = 'free';
        $validated['terms_accepted_at'] = now();
        unset($validated['terms']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-6">
        <div class="flex items-center justify-center gap-2">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" style="color:#003049;flex-shrink:0;">
                <line x1="5" y1="5"  x2="5"  y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                <line x1="5" y1="5"  x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                <line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                <circle cx="5"  cy="5"  r="2.75" fill="currentColor"/>
                <circle cx="5"  cy="19" r="2.75" fill="currentColor"/>
                <circle cx="19" cy="5"  r="2.75" fill="currentColor"/>
                <circle cx="19" cy="19" r="2.75" fill="currentColor"/>
            </svg>
            <span class="font-black tracking-widest" style="color:#003049;font-size:18px;letter-spacing:.1em;">NEX<span style="opacity:.45;font-weight:700;">OSN</span></span>
        </div>
        <p class="text-sm text-gray-500 mt-1">Crie sua conta gratuitamente</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        {{-- Nome --}}
        <div>
            <label for="name" class="block text-xs font-medium text-gray-600 mb-1">Nome completo</label>
            <input wire:model.live="name"
                   id="name" name="name" type="text"
                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-[#003049] focus:ring-1 focus:ring-[#003049] transition"
                   placeholder="Seu nome" required autofocus autocomplete="name"
                   x-on:input="
                       const v = $event.target.value
                           .toLowerCase()
                           .normalize('NFD').replace(/[̀-ͯ]/g,'')
                           .replace(/[^a-z0-9\s-]/g,'')
                           .trim()
                           .replace(/\s+/g,'-');
                       $wire.slug = v;
                   ">
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Slug --}}
        <div>
            <label for="slug" class="block text-xs font-medium text-gray-600 mb-1">
                Seu link personalizado
            </label>
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-50 focus-within:border-[#003049] focus-within:ring-1 focus-within:ring-[#003049] transition">
                <span class="px-3 py-2.5 text-xs text-gray-400 bg-gray-100 border-r border-gray-200 select-none whitespace-nowrap">
                    card.app/u/
                </span>
                <input wire:model="slug"
                       id="slug" name="slug" type="text"
                       class="flex-1 px-3 py-2.5 text-sm bg-gray-50 focus:outline-none"
                       placeholder="seu-nome" required
                       x-on:input="$event.target.value = $event.target.value.toLowerCase().replace(/[^a-z0-9-]/g,'')">
            </div>
            <p class="text-xs text-gray-400 mt-1">Apenas letras, números e hifens. Imutável após 30 dias.</p>
            @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- E-mail --}}
        <div>
            <label for="email" class="block text-xs font-medium text-gray-600 mb-1">E-mail</label>
            <input wire:model="email"
                   id="email" name="email" type="email"
                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-[#003049] focus:ring-1 focus:ring-[#003049] transition"
                   placeholder="voce@email.com" required autocomplete="username">
            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Senha --}}
        <div>
            <label for="password" class="block text-xs font-medium text-gray-600 mb-1">Senha</label>
            <input wire:model="password"
                   id="password" name="password" type="password"
                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-[#003049] focus:ring-1 focus:ring-[#003049] transition"
                   placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Confirmar senha --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-gray-600 mb-1">Confirmar senha</label>
            <input wire:model="password_confirmation"
                   id="password_confirmation" name="password_confirmation" type="password"
                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-[#003049] focus:ring-1 focus:ring-[#003049] transition"
                   placeholder="Repita a senha" required autocomplete="new-password">
            @error('password_confirmation')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Aceite dos Termos --}}
        <div>
            <label class="flex items-start gap-2.5 cursor-pointer">
                <input wire:model="terms" type="checkbox" id="terms"
                       class="mt-0.5 rounded border-gray-300 text-[#003049] focus:ring-[#003049] flex-shrink-0">
                <span class="text-xs text-gray-600 leading-relaxed">
                    Li e aceito os
                    <a href="{{ route('legal.termos') }}" target="_blank" class="font-medium underline" style="color:#003049;">Termos de Uso</a>
                    e a
                    <a href="{{ route('legal.privacidade') }}" target="_blank" class="font-medium underline" style="color:#003049;">Política de Privacidade</a>
                    da NEXOSN.
                </span>
            </label>
            @error('terms')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-2.5 rounded-lg text-sm font-semibold text-white transition hover:opacity-90 active:scale-[.98] flex items-center justify-center gap-2"
                style="background-color: #003049;">
            <svg data-lucide="user-plus" class="w-4 h-4"></svg>
            Criar conta grátis
        </button>

        <p class="text-center text-xs text-gray-500">
            Já tem conta?
            <a href="{{ route('login') }}" wire:navigate class="font-medium hover:underline" style="color: #003049;">
                Entrar
            </a>
        </p>

        <p class="text-center text-xs text-gray-400 mt-2">
            Ao criar sua conta você ganha <span class="font-semibold text-[#F77F00]">14 dias grátis do Pro</span>.
        </p>
    </form>
</div>
