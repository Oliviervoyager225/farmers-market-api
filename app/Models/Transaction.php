<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'farmer_id',
        'operator_id',
        'total_fcfa',
        'payment_method',
        'interest_rate',
        'interest_amount_fcfa',
        'status',
    ];

    protected $casts = [
        'total_fcfa'           => 'float',
        'interest_rate'        => 'float',
        'interest_amount_fcfa' => 'float',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function debt(): HasOne
    {
        return $this->hasOne(Debt::class);
    }
}
