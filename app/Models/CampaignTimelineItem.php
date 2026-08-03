<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignTimelineItem extends Model
{
    protected $fillable = [
        'card_id', 'occurred_on', 'title', 'description', 'icon', 'image', 'order',
    ];

    protected $casts = [
        'occurred_on' => 'date',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
