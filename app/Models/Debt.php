<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
    protected $fillable = [
        'transaction_id',
        'farmer_id',
        'original_amount_fcfa',
        'remaining_amount_fcfa',
        'status',
    ];

    protected $casts = [
        'original_amount_fcfa'  => 'float',
        'remaining_amount_fcfa' => 'float',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function repaymentDebts(): HasMany
    {
        return $this->hasMany(RepaymentDebt::class);
    }
}
