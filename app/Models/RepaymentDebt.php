<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepaymentDebt extends Model
{
    public $timestamps = false;

    protected $table = 'repayment_debt';

    protected $fillable = [
        'repayment_id',
        'debt_id',
        'amount_applied_fcfa',
    ];

    protected $casts = [
        'amount_applied_fcfa' => 'float',
    ];

    public function repayment(): BelongsTo
    {
        return $this->belongsTo(Repayment::class);
    }

    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }
}
