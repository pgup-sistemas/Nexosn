<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'efi_subscription_id',
        'efi_charge_id',
        'plan_type',
        'amount',
        'status',
        'description',
        'paid_at',
        'due_at',
        'metadata',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'paid_at'  => 'datetime',
        'due_at'   => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'R$ ' . number_format((float) $this->amount, 2, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid'     => 'Pago',
            'pending'  => 'Pendente',
            'failed'   => 'Falhou',
            'refunded' => 'Estornado',
            default    => $this->status,
        };
    }
}
