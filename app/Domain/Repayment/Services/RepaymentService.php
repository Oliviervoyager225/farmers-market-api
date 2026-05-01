<?php

declare(strict_types=1);

namespace App\Domain\Repayment\Services;

use App\Domain\Debt\Repositories\DebtRepositoryInterface;
use App\Domain\Repayment\DTOs\CreateRepaymentDTO;
use App\Domain\Repayment\Repositories\RepaymentRepositoryInterface;
use App\Models\Repayment;
use App\Models\RepaymentDebt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class RepaymentService
{
    public function __construct(
        private readonly RepaymentRepositoryInterface $repaymentRepository,
        private readonly DebtRepositoryInterface      $debtRepository,
    ) {}

    public function paginate(int $perPage = 15, ?int $farmerId = null): LengthAwarePaginator
    {
        return $this->repaymentRepository->paginate($perPage, $farmerId);
    }

    public function findOrFail(int $id): Repayment
    {
        $repayment = $this->repaymentRepository->findById($id);

        abort_if($repayment === null, 404, 'Remboursement introuvable.');

        return $repayment;
    }

    public function create(CreateRepaymentDTO $dto): Repayment
    {
        return DB::transaction(function () use ($dto) {
            $totalCredited = $dto->totalCredited();

            $repayment = $this->repaymentRepository->create([
                'farmer_id'           => $dto->farmerId,
                'operator_id'         => $dto->operatorId,
                'kg_received'         => $dto->kgReceived,
                'commodity_rate_fcfa' => $dto->commodityRateFcfa,
                'total_fcfa_credited' => $totalCredited,
            ]);

            $this->applyToDebts($repayment, $dto->farmerId, $totalCredited);

            return $repayment->load(['farmer', 'operator', 'debts']);
        });
    }

    private function applyToDebts(Repayment $repayment, int $farmerId, float $available): void
    {
        $debts = $this->debtRepository->openDebtsForFarmer($farmerId);

        foreach ($debts as $debt) {
            if ($available <= 0) {
                break;
            }

            $applied   = min($available, $debt->remaining_amount_fcfa);
            $remaining = round($debt->remaining_amount_fcfa - $applied, 2);

            RepaymentDebt::query()->create([
                'repayment_id'       => $repayment->id,
                'debt_id'            => $debt->id,
                'amount_applied_fcfa' => $applied,
            ]);

            $debt->update([
                'remaining_amount_fcfa' => $remaining,
                'status'                => $remaining <= 0 ? 'closed' : 'partial',
            ]);

            $available = round($available - $applied, 2);
        }
    }
}
