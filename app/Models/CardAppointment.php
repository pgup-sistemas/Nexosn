<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardAppointment extends Model
{
    // status: pending | confirmed | refused | cancelled
    protected $fillable = [
        'card_schedule_id', 'visitor_name', 'visitor_email', 'visitor_phone',
        'appointment_date', 'appointment_time', 'status', 'token', 'notes', 'token_expires_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'token_expires_at' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(CardSchedule::class, 'card_schedule_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isTokenValid(): bool
    {
        return $this->token_expires_at !== null && $this->token_expires_at->isFuture();
    }
}
