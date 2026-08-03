<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignTeamMember extends Model
{
    protected $fillable = ['card_id', 'name', 'role', 'photo', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
