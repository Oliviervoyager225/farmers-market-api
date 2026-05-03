<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id'   => $this->product_id,
            'product_name' => $this->whenLoaded('product', fn () => $this->product->name, ''),
            'unit_price'   => $this->unit_price_fcfa,
            'quantity'     => $this->quantity,
            'subtotal'     => $this->subtotal_fcfa,
        ];
    }
}
