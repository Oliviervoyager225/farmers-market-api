<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Models\User;
use App\Domain\Auth\DTOs\RegisterDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

final class UserRepository implements UserRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::query()->latest()->paginate($perPage);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function create(RegisterDTO $dto): User
    {
        return User::query()->create([
            'name'          => $dto->name,
            'email'         => $dto->email,
            'password'      => Hash::make($dto->password),
            'role'          => $dto->role->value,
            'supervisor_id' => $dto->supervisorId,
        ]);
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
