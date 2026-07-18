<?php

namespace App\Jobs;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendContactMessage implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly ContactMessage $message) {}

    public function handle(): void
    {
        $card  = $this->message->card()->with('user')->first();
        $owner = $card->user;

        Mail::to($owner->email)->send(new ContactMessageMail($this->message, $card));
    }
}
