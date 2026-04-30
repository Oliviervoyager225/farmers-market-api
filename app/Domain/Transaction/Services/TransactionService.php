<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Domain\Transaction\DTOs\CreateTransactionDTO;
use App\Domain\Transaction\Enums\PaymentMethod;
use App\Domain\Transaction\Repositories\TransactionRepositoryInterface;
use App\Models\Debt;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
    ) {}

    public function paginate(int $perPage = 15, ?int $operatorId = null, ?int $farmerId = null): LengthAwarePaginator
    {
        return $this->transactionRepository->paginate($perPage, $operatorId, $farmerId);
    }

    public function findOrFail(int $id): Transaction
    {
        $transaction = $this->transactionRepository->findById($id);

        abort_if($transaction === null, 404, 'Transaction introuvable.');

        return $transaction;
    }

    public function create(CreateTransactionDTO $dto): Transaction
    {
        return DB::transaction(function () use ($dto) {
            $subtotal = array_sum(array_map(fn ($item) => $item->subtotal(), $dto->items));

            $interestAmount = null;
            $total          = $subtotal;

            if ($dto->paymentMethod === PaymentMethod::Credit) {
                abort_if($dto->interestRate === null, 422, 'Le taux d\'intérêt est requis pour un paiement à crédit.');

                $interestAmount = round($subtotal * ($dto->interestRate / 100), 2);
                $total          = round($subtotal + $interestAmount, 2);

                // Vérification de la limite de crédit
                $farmer = \App\Models\Farmer::find($dto->farmerId);
                $openDebtsSum = \App\Models\Debt::where('farmer_id', $dto->farmerId)
                    ->whereIn('status', ['open', 'partial'])
                    ->sum('remaining_amount_fcfa');

                if (($openDebtsSum + $total) > $farmer->credit_limit_fcfa) {
                    abort(422, "Le montant dépasse la limite de crédit de l'agriculteur. Limite: {$farmer->credit_limit_fcfa} FCFA, Encours: {$openDebtsSum} FCFA, Nouveau crédit: {$total} FCFA.");
                }
            }

            $transaction = $this->transactionRepository->create([
                'farmer_id'            => $dto->farmerId,
                'operator_id'          => $dto->operatorId,
                'total_fcfa'           => $total,
                'payment_method'       => $dto->paymentMethod->value,
                'interest_rate'        => $dto->interestRate,
                'interest_amount_fcfa' => $interestAmount,
                'status'               => $dto->paymentMethod === PaymentMethod::Cash ? 'paid' : 'pending',
            ]);

            foreach ($dto->items as $item) {
                TransactionItem::query()->create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item->productId,
                    'quantity'       => $item->quantity,
                    'unit_price_fcfa' => $item->unitPriceFcfa,
                    'subtotal_fcfa'  => $item->subtotal(),
                ]);
            }

            if ($dto->paymentMethod === PaymentMethod::Credit) {
                Debt::query()->create([
                    'transaction_id'        => $transaction->id,
                    'farmer_id'             => $dto->farmerId,
                    'original_amount_fcfa'  => $total,
                    'remaining_amount_fcfa' => $total,
                    'status'                => 'open',
                ]);
            }

            return $transaction->load(['farmer', 'operator', 'items.product', 'debt']);
        });
    }
}
