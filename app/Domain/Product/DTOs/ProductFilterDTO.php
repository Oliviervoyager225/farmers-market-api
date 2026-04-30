<?php

declare(strict_types=1);

namespace App\Domain\Product\DTOs;

final readonly class ProductFilterDTO
{
    public function __construct(
        public ?int    $categoryId = null,
        public ?float  $minPrice = null,
        public ?float  $maxPrice = null,
        public ?string $search = null,
        public int     $perPage = 15,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            categoryId: isset($data['category_id']) ? (int) $data['category_id'] : null,
            minPrice:   isset($data['min_price']) ? (float) $data['min_price'] : null,
            maxPrice:   isset($data['max_price']) ? (float) $data['max_price'] : null,
            search:     $data['search'] ?? null,
            perPage:    (int) ($data['per_page'] ?? 15),
        );
    }
}
