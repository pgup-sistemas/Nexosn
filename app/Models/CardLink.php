<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardLink extends Model
{
    protected $fillable = ['card_id', 'label', 'url', 'icon', 'type', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function getLucideIconAttribute(): string
    {
        if ($this->icon) return $this->icon;

        $url = strtolower($this->url);
        return match (true) {
            str_contains($url, 'instagram.com')  => 'camera',
            str_contains($url, 'wa.me') || str_contains($url, 'whatsapp') => 'message-circle',
            str_contains($url, 'linkedin.com')   => 'briefcase',
            str_contains($url, 'tiktok.com')     => 'music',
            str_contains($url, 'youtube.com')    => 'play-circle',
            str_contains($url, 'twitter.com') || str_contains($url, 'x.com') => 'at-sign',
            str_contains($url, 'facebook.com')   => 'users',
            str_contains($url, 't.me') || str_contains($url, 'telegram') => 'send',
            str_contains($url, 'pinterest.com')  => 'pin',
            str_contains($url, 'spotify.com')    => 'music-2',
            str_contains($url, 'github.com')     => 'code-2',
            str_contains($url, 'mailto:')        => 'mail',
            str_contains($url, 'tel:')           => 'phone',
            default                              => 'link',
        };
    }
}
