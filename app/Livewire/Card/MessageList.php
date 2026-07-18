<?php

namespace App\Livewire\Card;

use Livewire\Component;
use Livewire\WithPagination;

class MessageList extends Component
{
    use WithPagination;

    public function markRead(int $id): void
    {
        auth()->user()->card->messages()->findOrFail($id)->update(['read_at' => now()]);
    }

    public function render()
    {
        $messages = auth()->user()->card?->messages()->paginate(20) ?? collect();
        return view('livewire.card.message-list', compact('messages'));
    }
}
