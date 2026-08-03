<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignProposalCategory extends Model
{
    protected $fillable = ['card_id', 'name', 'order'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(CampaignProposal::class, 'category_id')->orderBy('order');
    }
}
