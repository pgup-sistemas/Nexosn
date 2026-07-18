<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CardPhoto extends Model
{
    protected $fillable = ['card_id', 'path', 'thumbnail_path', 'caption', 'order'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return Storage::url($this->thumbnail_path ?? $this->path);
    }
}
