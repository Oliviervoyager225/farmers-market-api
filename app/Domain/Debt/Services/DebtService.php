<?php

declare(strict_types=1);

namespace App\Domain\Debt\Services;

use App\Domain\Debt\Repositories\DebtRepositoryInterface;
use App\Models\Debt;
use App\Models\Farmer;

final class DebtService
{
    public function __construct(
        private readonly DebtRepositoryInterface $debtRepository,
    ) {}

    public function summaryByFarmer(int $farmerId): array
    {
        $farmer = Farmer::with(['debts' => fn ($q) => $q->with('transaction')])->findOrFail($farmerId);
        $debts  = $farmer->debts;

        $totalDebt = (float) $debts->sum('original_amount_fcfa');
        $totalPaid = (float) $debts->sum(fn (Debt $d) => $d->original_amount_fcfa - $d->remaining_amount_fcfa);
        $remaining = (float) $debts->whereIn('status', ['open', 'partial'])->sum('remaining_amount_fcfa');

        $openDebts = $debts
            ->whereIn('status', ['open', 'partial'])
            ->sortBy('created_at')
            ->values()
            ->map(fn (Debt $d) => [
                'transaction_id' => $d->transaction_id,
                'amount'         => $d->original_amount_fcfa,
                'paid'           => round($d->original_amount_fcfa - $d->remaining_amount_fcfa, 2),
                'balance'        => $d->remaining_amount_fcfa,
                'date'           => $d->created_at?->toISOString(),
            ])
            ->all();

        return [
            'total_debt' => $totalDebt,
            'total_paid' => $totalPaid,
            'remaining'  => $remaining,
            'open_debts' => $openDebts,
        ];
    }

    public function findOrFail(int $id): Debt
    {
        $debt = $this->debtRepository->findById($id);

        abort_if($debt === null, 404, 'Dette introuvable.');

        return $debt;
    }
}
