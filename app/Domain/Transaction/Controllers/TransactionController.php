<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Transaction\DTOs\CreateTransactionDTO;
use App\Domain\Transaction\Requests\CreateTransactionRequest;
use App\Domain\Transaction\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage   = (int) $request->query('per_page', 15);
        $farmerId  = $request->query('farmer_id') ? (int) $request->query('farmer_id') : null;
        $user      = $request->user();
        $operatorId = in_array($user->role, ['admin', 'supervisor']) ? null : $user->id;

        return response()->json(
            $this->transactionService->paginate($perPage, $operatorId, $farmerId),
        );
    }

    public function show(int $transaction): JsonResponse
    {
        return response()->json([
            'data' => $this->transactionService->findOrFail($transaction),
        ]);
    }

    public function store(CreateTransactionRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['operator_id' => $request->user()->id]);

        $transaction = $this->transactionService->create(CreateTransactionDTO::fromArray($data));

        return response()->json([
            'message' => 'Transaction enregistrée avec succès.',
            'data'    => $transaction,
        ], 201);
    }
}
