<?php

declare(strict_types=1);

namespace App\Domain\Category\Repositories;

use App\Models\Category;
use App\Domain\Category\DTOs\CategoryDTO;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Category;

    public function create(CategoryDTO $dto): Category;

    public function update(Category $category, CategoryDTO $dto): Category;

    public function delete(Category $category): void;
}
