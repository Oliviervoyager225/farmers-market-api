<?php

declare(strict_types=1);

namespace App\Domain\Category\DTOs;

final readonly class CategoryDTO
{
    public function __construct(
        public string $name,
        public ?int   $parentId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name:     $data['name'],
            parentId: isset($data['parent_id']) ? (int) $data['parent_id'] : null,
        );
    }
}
