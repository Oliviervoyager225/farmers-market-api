<?php

declare(strict_types=1);

namespace App\Domain\Category\Services;

use App\Domain\Category\DTOs\CategoryDTO;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->categoryRepository->all();
    }

    public function findOrFail(int $id): Category
    {
        $category = $this->categoryRepository->findById($id);

        abort_if($category === null, 404, 'Category not found.');

        return $category;
    }

    public function create(CategoryDTO $dto): Category
    {
        return $this->categoryRepository->create($dto);
    }

    public function update(int $id, CategoryDTO $dto): Category
    {
        $category = $this->findOrFail($id);

        return $this->categoryRepository->update($category, $dto);
    }

    public function delete(int $id): void
    {
        $category = $this->findOrFail($id);

        $this->categoryRepository->delete($category);
    }
}
