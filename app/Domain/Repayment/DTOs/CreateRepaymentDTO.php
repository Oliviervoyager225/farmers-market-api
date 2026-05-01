<?php

declare(strict_types=1);

namespace App\Domain\Repayment\DTOs;

final readonly class CreateRepaymentDTO
{
    public function __construct(
        public int   $farmerId,
        public int   $operatorId,
        public float $kgReceived,
        public float $commodityRateFcfa,
    ) {}

    public function totalCredited(): float
    {
        return round($this->kgReceived * $this->commodityRateFcfa, 2);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            farmerId:          (int) $data['farmer_id'],
            operatorId:        (int) $data['operator_id'],
            kgReceived:        (float) $data['kg_received'],
            commodityRateFcfa: (float) $data['commodity_rate_fcfa'],
        );
    }
}
