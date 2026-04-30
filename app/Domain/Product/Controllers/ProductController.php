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
use App\Domain\Vendor\Repositories\VendorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly VendorRepositoryInterface $vendorRepository,
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
        $vendor = $this->vendorRepository->findByUserId($request->user()->id);

        abort_if($vendor === null, 403, 'You must have a vendor profile to create products.');

        $data = array_merge($request->validated(), ['vendor_id' => $vendor->id]);

        $product = $this->productService->create(ProductDTO::fromArray($data));

        return response()->json([
            'message' => 'Product created successfully.',
            'data'    => $product,
        ], 201);
    }

    public function update(UpdateProductRequest $request, int $product): JsonResponse
    {
        $vendor = $this->vendorRepository->findByUserId($request->user()->id);

        abort_if($vendor === null, 403, 'You must have a vendor profile to update products.');

        $data = array_merge($request->validated(), ['vendor_id' => $vendor->id]);

        $updated = $this->productService->update($product, ProductDTO::fromArray($data));

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

    public function byVendor(Request $request, int $vendor): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);

        return response()->json(
            $this->productService->getByVendor($vendor, $perPage),
        );
    }
}
