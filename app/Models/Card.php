<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Card extends Model
{
    protected $fillable = [
        'user_id', 'slug', 'is_active',
        'display_name', 'title', 'company', 'bio',
        'profile_photo', 'cover_photo', 'logo',
        'brand_color_primary', 'brand_color_button',
        'show_watermark',
        'contact_email', 'contact_phone', 'contact_landline', 'address', 'website', 'pix_key',
        'template',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'show_watermark' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(CardLink::class)->orderBy('order');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CardPhoto::class)->orderBy('order');
    }

    public function views(): HasMany
    {
        return $this->hasMany(CardView::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ContactMessage::class)->orderByDesc('created_at');
    }

    public function services(): HasMany
    {
        return $this->hasMany(CardService::class)->orderBy('sort_order');
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(CardSchedule::class);
    }

    public function getPrimaryColorAttribute(): string
    {
        $user = $this->relationLoaded('user') ? $this->user : $this->user()->first();
        if ($user && ($user->isPro() || $user->isOnTrial()) && $this->brand_color_primary) {
            return $this->brand_color_primary;
        }
        return '#003049';
    }

    public function getButtonColorAttribute(): string
    {
        $user = $this->relationLoaded('user') ? $this->user : $this->user()->first();
        if ($user && ($user->isPro() || $user->isOnTrial()) && $this->brand_color_button) {
            return $this->brand_color_button;
        }
        return '#D62828';
    }
}
