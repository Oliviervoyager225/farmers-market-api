<?php

declare(strict_types=1);

namespace App\Domain\Debt\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Debt\Services\DebtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DebtController extends Controller
{
    public function __construct(
        private readonly DebtService $debtService,
    ) {}

    public function byFarmer(Request $request, int $farmer): JsonResponse
    {
        return response()->json([
            'data' => $this->debtService->summaryByFarmer($farmer),
        ]);
    }

    public function show(int $debt): JsonResponse
    {
        return response()->json([
            'data' => $this->debtService->findOrFail($debt),
        ]);
    }
}
