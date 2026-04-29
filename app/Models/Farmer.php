<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farmer extends Model
{
    protected $fillable = [
        'identifier',
        'firstname',
        'lastname',
        'phone',
        'credit_limit_fcfa',
        'operator_id',
    ];

    protected $casts = [
        'credit_limit_fcfa' => 'float',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getTotalRemainingDebtAttribute(): float
    {
        return (float) $this->debts()->whereIn('status', ['open', 'partial'])->sum('remaining_amount_fcfa');
    }
}
