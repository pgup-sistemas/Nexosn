<?php

namespace App\Livewire\Card;

use App\Jobs\SendContactMessage;
use App\Models\Card;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    public Card $card;
    public bool $sent = false;

    #[Validate('required|string|max:100')]
    public string $senderName = '';

    #[Validate('required|email|max:150')]
    public string $senderEmail = '';

    #[Validate('nullable|string|max:20')]
    public string $senderPhone = '';

    #[Validate('required|string|min:10|max:1000')]
    public string $message = '';

    // Honeypot — deve estar vazio
    public string $website = '';

    public function submit(Request $request): void
    {
        // Honeypot: bot preencheu o campo oculto
        if (!empty($this->website)) {
            $this->sent = true;
            return;
        }

        $key = 'contact-form:' . ($request->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('limit', 'Muitas tentativas. Aguarde antes de enviar novamente.');
            return;
        }
        RateLimiter::hit($key, 3600);

        $this->validate(messages: [
            'senderName.required'  => 'Informe seu nome.',
            'senderEmail.required' => 'Informe seu e-mail.',
            'senderEmail.email'    => 'E-mail inválido.',
            'message.required'     => 'Escreva sua mensagem.',
            'message.min'          => 'Mensagem muito curta (mínimo 10 caracteres).',
        ]);

        $msg = ContactMessage::create([
            'card_id'      => $this->card->id,
            'sender_name'  => $this->senderName,
            'sender_email' => $this->senderEmail,
            'sender_phone' => $this->senderPhone ?: null,
            'message'      => $this->message,
            'ip_address'   => $request->ip(),
        ]);

        SendContactMessage::dispatch($msg);

        $this->reset(['senderName', 'senderEmail', 'senderPhone', 'message']);
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.card.contact-form');
    }
}
