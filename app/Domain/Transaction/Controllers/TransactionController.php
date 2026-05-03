<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Domain\Transaction\DTOs\CreateTransactionDTO;
use App\Domain\Transaction\Requests\CreateTransactionRequest;
use App\Domain\Transaction\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage  = (int) $request->query('per_page', 15);
        $farmerId = $request->query('farmer_id') ? (int) $request->query('farmer_id') : null;

        return TransactionResource::collection(
            $this->transactionService->paginate($perPage, null, $farmerId),
        );
    }

    public function show(int $transaction): JsonResponse
    {
        return response()->json([
            'data' => new TransactionResource($this->transactionService->findOrFail($transaction)),
        ]);
    }

    public function store(CreateTransactionRequest $request): JsonResponse
    {
        $data        = array_merge($request->validated(), ['operator_id' => $request->user()->id]);
        $transaction = $this->transactionService->create(CreateTransactionDTO::fromArray($data));

        return response()->json([
            'message' => 'Transaction enregistrée avec succès.',
            'data'    => new TransactionResource($transaction),
        ], 201);
    }
}
