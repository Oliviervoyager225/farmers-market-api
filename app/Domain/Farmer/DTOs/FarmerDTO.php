<?php

declare(strict_types=1);

namespace App\Domain\Farmer\DTOs;

final readonly class FarmerDTO
{
    public function __construct(
        public string  $identifier,
        public string  $firstname,
        public string  $lastname,
        public int     $operatorId,
        public ?string $phone = null,
        public float   $creditLimitFcfa = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            identifier:      $data['identifier'],
            firstname:       $data['firstname'],
            lastname:        $data['lastname'],
            operatorId:      (int) $data['operator_id'],
            phone:           $data['phone'] ?? null,
            creditLimitFcfa: (float) ($data['credit_limit_fcfa'] ?? 0),
        );
    }
}
