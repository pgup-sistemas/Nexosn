@component('mail::message')
# Nova mensagem recebida no seu cartão

**De:** {{ $msg->sender_name }} ({{ $msg->sender_email }})
@if ($msg->sender_phone)
**Telefone:** {{ $msg->sender_phone }}
@endif

---

**Mensagem:**

{{ $msg->message }}

---

@component('mail::button', ['url' => route('dashboard.messages')])
Ver no painel
@endcomponent

*Enviado via Card · /u/{{ $card->slug }}*
@endcomponent
