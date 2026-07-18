<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardView extends Model
{
    protected $fillable = ['card_id', 'ip', 'user_agent', 'referer'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
