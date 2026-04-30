<?php

declare(strict_types=1);

namespace App\Domain\Category\Repositories;

use App\Models\Category;
use App\Domain\Category\DTOs\CategoryDTO;
use Illuminate\Database\Eloquent\Collection;

final class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::query()
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?Category
    {
        return Category::query()->with(['parent', 'children', 'products'])->find($id);
    }

    public function create(CategoryDTO $dto): Category
    {
        return Category::query()->create([
            'name'      => $dto->name,
            'parent_id' => $dto->parentId,
        ]);
    }

    public function update(Category $category, CategoryDTO $dto): Category
    {
        $category->update([
            'name'      => $dto->name,
            'parent_id' => $dto->parentId,
        ]);

        return $category->fresh(['parent', 'children']);
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
