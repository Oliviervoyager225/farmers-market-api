<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subtotal = $this->total_fcfa - ($this->interest_amount_fcfa ?? 0);

        return [
            'id'              => $this->id,
            'farmer_id'       => $this->farmer_id,
            'farmer_name'     => $this->whenLoaded('farmer', fn () => $this->farmer->full_name, ''),
            'operator_id'     => $this->operator_id,
            'operator_name'   => $this->whenLoaded('operator', fn () => $this->operator->name ?? '', ''),
            'operator_role'   => $this->whenLoaded('operator', fn () => $this->operator->role ?? '', ''),
            'items'           => TransactionItemResource::collection($this->whenLoaded('items')),
            'subtotal'        => round($subtotal, 2),
            'interest_rate'   => $this->interest_rate ?? 0,
            'interest_amount' => $this->interest_amount_fcfa ?? 0,
            'total'           => $this->total_fcfa,
            'payment_type'    => $this->payment_method,
            'created_at'      => $this->created_at?->toISOString(),
        ];
    }
}
