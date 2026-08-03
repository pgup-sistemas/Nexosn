<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignProfile extends Model
{
    protected $fillable = [
        'card_id',
        'campaign_name', 'role_title', 'ballot_number', 'organization_name',
        'affiliation', 'slogan', 'portrait_photo',
        'countdown_target_at',
        'hq_address', 'hq_lat', 'hq_lng',
        'legal_responsible_name', 'legal_responsible_document',
    ];

    protected $casts = [
        'countdown_target_at' => 'datetime',
        'hq_lat' => 'decimal:7',
        'hq_lng' => 'decimal:7',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
