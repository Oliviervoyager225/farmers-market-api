<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\DTOs\ProductFilterDTO;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function filter(ProductFilterDTO $filterDTO): LengthAwarePaginator
    {
        return $this->productRepository->filter($filterDTO);
    }

    public function findOrFail(int $id): Product
    {
        $product = $this->productRepository->findById($id);

        abort_if($product === null, 404, 'Product not found.');

        return $product;
    }

    public function getByVendor(int $vendorId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->findByVendor($vendorId, $perPage);
    }

    public function create(ProductDTO $dto): Product
    {
        return $this->productRepository->create($dto);
    }

    public function update(int $id, ProductDTO $dto): Product
    {
        $product = $this->findOrFail($id);

        return $this->productRepository->update($product, $dto);
    }

    public function delete(int $id): void
    {
        $product = $this->findOrFail($id);

        $this->productRepository->delete($product);
    }

    /**
     * Check stock availability before ordering.
     */
    public function checkStock(int $productId, int $quantity): Product
    {
        $product = $this->findOrFail($productId);

        abort_if(
            $product->stock < $quantity,
            422,
            "Insufficient stock. Available: {$product->stock}, Requested: {$quantity}.",
        );

        abort_if(
            ! $product->is_available,
            422,
            'This product is currently unavailable.',
        );

        return $product;
    }

    public function decrementStock(Product $product, int $quantity): void
    {
        $this->productRepository->decrementStock($product, $quantity);
    }
}
