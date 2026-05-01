<?php

declare(strict_types=1);

namespace App\Domain\Setting\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Setting\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SettingController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->settingService->all(),
        ]);
    }

    public function update(Request $request, string $key): JsonResponse
    {
        $request->validate([
            'value' => ['required', 'string', 'max:255'],
        ]);

        $setting = $this->settingService->update($key, $request->input('value'));

        return response()->json([
            'message' => 'Paramètre mis à jour.',
            'data'    => $setting,
        ]);
    }
}
