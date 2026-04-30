<?php

declare(strict_types=1);

namespace App\Domain\Product\Repositories;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\DTOs\ProductFilterDTO;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductRepository implements ProductRepositoryInterface
{
    public function filter(ProductFilterDTO $filterDTO): LengthAwarePaginator
    {
        return Product::query()
            ->with('category')
            ->when($filterDTO->categoryId, fn ($q) => $q->where('category_id', $filterDTO->categoryId))
            ->when($filterDTO->minPrice, fn ($q) => $q->where('price_fcfa', '>=', $filterDTO->minPrice))
            ->when($filterDTO->maxPrice, fn ($q) => $q->where('price_fcfa', '<=', $filterDTO->maxPrice))
            ->when($filterDTO->search, fn ($q) => $q->where(function ($query) use ($filterDTO) {
                $query->where('name', 'like', "%{$filterDTO->search}%")
                      ->orWhere('description', 'like', "%{$filterDTO->search}%");
            }))
            ->latest()
            ->paginate($filterDTO->perPage);
    }

    public function findById(int $id): ?Product
    {
        return Product::query()->with('category')->find($id);
    }

    public function create(ProductDTO $dto): Product
    {
        return Product::query()->create([
            'category_id' => $dto->categoryId,
            'name'        => $dto->name,
            'description' => $dto->description,
            'price_fcfa'  => $dto->priceFcfa,
        ]);
    }

    public function update(Product $product, ProductDTO $dto): Product
    {
        $product->update([
            'category_id' => $dto->categoryId,
            'name'        => $dto->name,
            'description' => $dto->description,
            'price_fcfa'  => $dto->priceFcfa,
        ]);

        return $product->fresh('category');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
