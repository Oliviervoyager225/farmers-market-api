<?php

declare(strict_types=1);

namespace App\Domain\Product\Repositories;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\DTOs\ProductFilterDTO;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function filter(ProductFilterDTO $filterDTO): LengthAwarePaginator;

    public function findById(int $id): ?Product;

    public function create(ProductDTO $dto): Product;

    public function update(Product $product, ProductDTO $dto): Product;

    public function delete(Product $product): void;
}
