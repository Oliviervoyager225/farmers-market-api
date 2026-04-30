<?php

declare(strict_types=1);

namespace App\Domain\Transaction\DTOs;

use App\Domain\Transaction\Enums\PaymentMethod;

final readonly class CreateTransactionDTO
{
    /**
     * @param TransactionItemDTO[] $items
     */
    public function __construct(
        public int           $farmerId,
        public int           $operatorId,
        public PaymentMethod $paymentMethod,
        public array         $items,
        public ?float        $interestRate = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            farmerId:      (int) $data['farmer_id'],
            operatorId:    (int) $data['operator_id'],
            paymentMethod: PaymentMethod::from($data['payment_method']),
            items:         array_map(
                fn (array $item) => TransactionItemDTO::fromArray($item),
                $data['items'],
            ),
            interestRate:  isset($data['interest_rate']) ? (float) $data['interest_rate'] : null,
        );
    }
}
