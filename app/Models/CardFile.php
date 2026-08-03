<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardFile extends Model
{
    protected $fillable = [
        'card_id', 'category', 'label', 'file_path', 'file_type', 'order',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
