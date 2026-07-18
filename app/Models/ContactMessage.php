<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = [
        'card_id', 'sender_name', 'sender_email', 'sender_phone',
        'message', 'ip_address', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }
}
