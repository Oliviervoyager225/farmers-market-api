<?php

declare(strict_types=1);

namespace App\Domain\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Auth\DTOs\RegisterDTO;
use App\Domain\Auth\Requests\StoreUserRequest;
use App\Domain\Auth\Requests\UpdateUserRequest;
use App\Domain\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        
        return response()->json(
            $this->authService->paginateUsers($perPage)
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'data' => $this->authService->findUserOrFail($id),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->authService->createUser(
            RegisterDTO::fromArray($request->validated())
        );

        return response()->json([
            'message' => 'User created successfully.',
            'data'    => $user,
        ], 201);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->authService->updateUser($id, $request->validated());

        return response()->json([
            'message' => 'User updated successfully.',
            'data'    => $user,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->authService->deleteUser($id);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
