<?php

declare(strict_types=1);

namespace App\Domain\Debt\Services;

use App\Domain\Debt\Repositories\DebtRepositoryInterface;
use App\Models\Debt;
use Illuminate\Pagination\LengthAwarePaginator;

final class DebtService
{
    public function __construct(
        private readonly DebtRepositoryInterface $debtRepository,
    ) {}

    public function paginateByFarmer(int $farmerId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->debtRepository->paginateByFarmer($farmerId, $perPage);
    }

    public function findOrFail(int $id): Debt
    {
        $debt = $this->debtRepository->findById($id);

        abort_if($debt === null, 404, 'Dette introuvable.');

        return $debt;
    }
}
