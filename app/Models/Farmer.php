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
        'email',
        'state',
        'city',
        'address',
        'bio',
        'farm_size',
        'experience',
        'categories',
        'specialties',
        'certification',
        'primary_market',
        'credit_limit_fcfa',
        'operator_id',
    ];

    protected $casts = [
        'credit_limit_fcfa' => 'float',
        'farm_size' => 'float',
        'experience' => 'integer',
        'categories' => 'array',
        'specialties' => 'array',
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

    /**
     * Génère le prochain identifiant automatique au format AGR-CI-001.
     */
    public static function generateNextIdentifier(): string
    {
        $identifiers = static::where('identifier', 'like', 'AGR-CI-%')->pluck('identifier');

        $max = 0;
        foreach ($identifiers as $id) {
            if (preg_match('/AGR-CI-(\d+)$/', $id, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return 'AGR-CI-' . str_pad($max + 1, 3, '0', STR_PAD_LEFT);
    }
}
