<?php

declare(strict_types=1);

namespace App\Domain\Product\DTOs;

final readonly class ProductDTO
{
    public function __construct(
        public int     $categoryId,
        public string  $name,
        public float   $priceFcfa,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            categoryId:  (int) $data['category_id'],
            name:        $data['name'],
            priceFcfa:   (float) $data['price_fcfa'],
            description: $data['description'] ?? null,
        );
    }
}
