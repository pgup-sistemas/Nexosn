<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'plan',
        'is_admin',
        'plan_expires_at',
        'trial_ends_at',
        'terms_accepted_at',
        'efi_subscription_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'plan_expires_at'    => 'datetime',
            'trial_ends_at'      => 'datetime',
            'is_admin'           => 'boolean',
            'terms_accepted_at'  => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function card(): HasOne
    {
        return $this->hasOne(Card::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin === true;
    }

    public function isPro(): bool
    {
        return $this->plan === 'pro'
            && ($this->plan_expires_at === null || $this->plan_expires_at->isFuture());
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }
}
