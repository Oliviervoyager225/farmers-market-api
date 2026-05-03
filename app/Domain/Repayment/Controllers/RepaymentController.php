<?php

declare(strict_types=1);

namespace App\Domain\Repayment\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RepaymentResource;
use App\Domain\Repayment\DTOs\CreateRepaymentDTO;
use App\Domain\Repayment\Requests\CreateRepaymentRequest;
use App\Domain\Repayment\Services\RepaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class RepaymentController extends Controller
{
    public function __construct(
        private readonly RepaymentService $repaymentService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage  = (int) $request->query('per_page', 15);
        $farmerId = $request->query('farmer_id') ? (int) $request->query('farmer_id') : null;

        return RepaymentResource::collection(
            $this->repaymentService->paginate($perPage, $farmerId),
        );
    }

    public function byFarmer(Request $request, int $farmer): AnonymousResourceCollection
    {
        $perPage = (int) $request->query('per_page', 15);

        return RepaymentResource::collection(
            $this->repaymentService->paginateByFarmer($farmer, $perPage),
        );
    }

    public function show(int $repayment): JsonResponse
    {
        return response()->json([
            'data' => new RepaymentResource($this->repaymentService->findOrFail($repayment)),
        ]);
    }

    public function store(CreateRepaymentRequest $request): JsonResponse
    {
        $data      = array_merge($request->validated(), ['operator_id' => $request->user()->id]);
        $repayment = $this->repaymentService->create(CreateRepaymentDTO::fromArray($data));

        return response()->json([
            'message' => 'Remboursement enregistré et appliqué aux dettes.',
            'data'    => new RepaymentResource($repayment),
        ], 201);
    }
}
