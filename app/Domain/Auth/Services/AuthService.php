<?php

declare(strict_types=1);

namespace App\Domain\Auth\Services;

use App\Domain\Auth\DTOs\LoginDTO;
use App\Domain\Auth\DTOs\RegisterDTO;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Register a new user and return it with a token.
     */
    public function register(RegisterDTO $dto): array
    {
        $user = $this->userRepository->create($dto);

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Attempt login and return a token.
     *
     * @throws ValidationException
     */
    public function login(LoginDTO $dto): array
    {
        if (! Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // Revoke all previous tokens for single-session security
        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Revoke the current user's token.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function paginateUsers(int $perPage = 15)
    {
        return $this->userRepository->paginate($perPage);
    }

    public function findUserOrFail(int $id): User
    {
        $user = $this->userRepository->findById($id);
        abort_if($user === null, 404, 'User not found.');
        return $user;
    }

    public function createUser(RegisterDTO $dto): User
    {
        return $this->userRepository->create($dto);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->findUserOrFail($id);
        return $this->userRepository->update($user, $data);
    }

    public function deleteUser(int $id): void
    {
        $user = $this->findUserOrFail($id);
        $this->userRepository->delete($user);
    }
}
