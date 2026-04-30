<?php

declare(strict_types=1);

namespace App\Domain\Transaction\DTOs;

final readonly class TransactionItemDTO
{
    public function __construct(
        public int   $productId,
        public int   $quantity,
        public float $unitPriceFcfa,
    ) {
    }

    public function subtotal(): float
    {
        return $this->quantity * $this->unitPriceFcfa;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            productId:    (int) $data['product_id'],
            quantity:     (int) $data['quantity'],
            unitPriceFcfa: (float) $data['unit_price_fcfa'],
        );
    }
}
