<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'farmer_id'     => $this->farmer_id,
            'farmer_name'   => $this->whenLoaded('farmer', fn () => $this->farmer->full_name ?? '', ''),
            'commodity'     => $this->commodity,
            'kg_received'   => $this->kg_received,
            'rate_per_kg'   => $this->commodity_rate_fcfa,
            'fcfa_value'    => $this->total_fcfa_credited,
            'operator_id'   => $this->operator_id,
            'operator_name' => $this->whenLoaded('operator', fn () => $this->operator->name ?? '', ''),
            'operator_role' => $this->whenLoaded('operator', fn () => $this->operator->role ?? '', ''),
            'debt_ids'      => $this->whenLoaded('debts', fn () => $this->debts->pluck('id')->all(), []),
            'created_at'    => $this->created_at?->toISOString(),
        ];
    }
}
