<?php

declare(strict_types=1);

namespace App\Domain\Category\Services;

use App\Domain\Category\DTOs\CategoryDTO;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class CategoryService
{
    private const CACHE_TTL = 3600;
    private const CACHE_KEY = 'categories.all';

    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function getAll(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn () => $this->categoryRepository->all());
    }

    public function findOrFail(int $id): Category
    {
        $category = $this->categoryRepository->findById($id);

        abort_if($category === null, 404, 'Category not found.');

        return $category;
    }

    public function create(CategoryDTO $dto): Category
    {
        $category = $this->categoryRepository->create($dto);

        Cache::forget(self::CACHE_KEY);

        return $category;
    }

    public function update(int $id, CategoryDTO $dto): Category
    {
        $category = $this->findOrFail($id);

        $updated = $this->categoryRepository->update($category, $dto);

        Cache::forget(self::CACHE_KEY);

        return $updated;
    }

    public function delete(int $id): void
    {
        $category = $this->findOrFail($id);

        $this->categoryRepository->delete($category);

        Cache::forget(self::CACHE_KEY);
    }
}
