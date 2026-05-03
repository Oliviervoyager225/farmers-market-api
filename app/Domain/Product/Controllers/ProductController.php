<?php

declare(strict_types=1);

namespace App\Domain\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\DTOs\ProductFilterDTO;
use App\Domain\Product\Requests\FilterProductRequest;
use App\Domain\Product\Requests\StoreProductRequest;
use App\Domain\Product\Requests\UpdateProductRequest;
use App\Domain\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {}

    public function index(FilterProductRequest $request): JsonResponse
    {
        $products = $this->productService->filter(
            ProductFilterDTO::fromArray($request->validated()),
        );

        return response()->json($products);
    }

    public function show(int $product): JsonResponse
    {
        return response()->json([
            'data' => $this->productService->findOrFail($product),
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(ProductDTO::fromArray($request->validated()));

        return response()->json([
            'message' => 'Product created successfully.',
            'data'    => $product,
        ], 201);
    }

    public function update(UpdateProductRequest $request, int $product): JsonResponse
    {
        $updated = $this->productService->update($product, ProductDTO::fromArray($request->validated()));

        return response()->json([
            'message' => 'Product updated successfully.',
            'data'    => $updated,
        ]);
    }

    public function destroy(int $product): JsonResponse
    {
        $this->productService->delete($product);

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }

}
