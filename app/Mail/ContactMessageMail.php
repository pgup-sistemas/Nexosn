<?php

namespace App\Mail;

use App\Models\Card;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly ContactMessage $msg,
        public readonly Card $card
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Card] Nova mensagem de {$this->msg->sender_name}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-message');
    }
}
