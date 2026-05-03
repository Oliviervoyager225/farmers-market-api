<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repayment extends Model
{
    protected $fillable = [
        'farmer_id',
        'operator_id',
        'commodity',
        'kg_received',
        'commodity_rate_fcfa',
        'total_fcfa_credited',
    ];

    protected $casts = [
        'kg_received'         => 'float',
        'commodity_rate_fcfa' => 'float',
        'total_fcfa_credited' => 'float',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function repaymentDebts(): HasMany
    {
        return $this->hasMany(RepaymentDebt::class);
    }

    public function debts(): BelongsToMany
    {
        return $this->belongsToMany(Debt::class, 'repayment_debt')
            ->withPivot('amount_applied_fcfa');
    }
}
