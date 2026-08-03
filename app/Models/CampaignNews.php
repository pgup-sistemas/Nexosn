<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignNews extends Model
{
    protected $table = 'campaign_news';

    protected $fillable = [
        'card_id', 'title', 'body', 'cover_image', 'published_at', 'is_active', 'order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
