<?php

declare(strict_types=1);

namespace App\Domain\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Auth\DTOs\LoginDTO;
use App\Domain\Auth\DTOs\RegisterDTO;
use App\Domain\Auth\Requests\LoginRequest;
use App\Domain\Auth\Requests\RegisterRequest;
use App\Domain\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register(
            RegisterDTO::fromArray($request->validated()),
        );

        return response()->json([
            'message' => 'Registration successful.',
            'data'    => $result,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            LoginDTO::fromArray($request->validated()),
        );

        return response()->json([
            'message' => 'Login successful.',
            'data'    => $result,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}
