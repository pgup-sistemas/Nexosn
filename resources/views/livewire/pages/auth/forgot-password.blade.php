<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Informe seu e-mail.',
            'email.email'    => 'Informe um e-mail válido.',
        ]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
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
        <p class="text-sm text-gray-500 mt-1">Recuperar senha</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <p class="text-sm text-gray-500 mb-4 text-center">
        Informe seu e-mail e enviaremos um link para redefinir sua senha.
    </p>

    <form wire:submit="sendPasswordResetLink" class="space-y-4">
        <div>
            <label for="email" class="block text-xs font-medium text-gray-600 mb-1">E-mail</label>
            <input wire:model="email"
                   id="email" name="email" type="email"
                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-[#003049] focus:ring-1 focus:ring-[#003049] transition"
                   placeholder="voce@email.com" required autofocus>
            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 rounded-lg text-sm font-semibold text-white transition hover:opacity-90 flex items-center justify-center gap-2"
                style="background-color: #003049;">
            <svg data-lucide="mail" class="w-4 h-4"></svg>
            Enviar link de recuperação
        </button>

        <p class="text-center text-xs text-gray-500">
            <a href="{{ route('login') }}" wire:navigate class="font-medium hover:underline" style="color: #003049;">
                Voltar ao login
            </a>
        </p>
    </form>
</div>
