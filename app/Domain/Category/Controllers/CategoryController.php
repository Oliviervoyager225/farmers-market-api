<?php

declare(strict_types=1);

namespace App\Domain\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Category\DTOs\CategoryDTO;
use App\Domain\Category\Requests\StoreCategoryRequest;
use App\Domain\Category\Requests\UpdateCategoryRequest;
use App\Domain\Category\Services\CategoryService;
use Illuminate\Http\JsonResponse;

final class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->categoryService->getAll(),
        ]);
    }

    public function show(int $category): JsonResponse
    {
        return response()->json([
            'data' => $this->categoryService->findOrFail($category),
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create(
            CategoryDTO::fromArray($request->validated()),
        );

        return response()->json([
            'message' => 'Category created successfully.',
            'data'    => $category,
        ], 201);
    }

    public function update(UpdateCategoryRequest $request, int $category): JsonResponse
    {
        $updated = $this->categoryService->update(
            $category,
            CategoryDTO::fromArray($request->validated()),
        );

        return response()->json([
            'message' => 'Category updated successfully.',
            'data'    => $updated,
        ]);
    }

    public function destroy(int $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
