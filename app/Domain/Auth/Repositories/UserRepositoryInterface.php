<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Models\User;
use App\Domain\Auth\DTOs\RegisterDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function create(RegisterDTO $dto): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): void;
}
