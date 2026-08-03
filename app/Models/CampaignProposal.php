<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignProposal extends Model
{
    protected $fillable = [
        'card_id', 'category_id', 'title', 'description',
        'image', 'video_url', 'pdf_path', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CampaignProposalCategory::class, 'category_id');
    }
}
