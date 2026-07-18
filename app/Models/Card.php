<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = [
        'user_id', 'slug', 'is_active',
        'display_name', 'title', 'company', 'bio',
        'profile_photo', 'cover_photo', 'logo',
        'brand_color_primary', 'brand_color_button',
        'show_watermark',
        'contact_email', 'contact_phone', 'address', 'website', 'pix_key',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'show_watermark' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
