<?php

declare(strict_types=1);

namespace App\Domain\Debt\Repositories;

use App\Models\Debt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class DebtRepository implements DebtRepositoryInterface
{
    public function paginateByFarmer(int $farmerId, int $perPage = 15): LengthAwarePaginator
    {
        return Debt::query()
            ->with(['transaction', 'farmer'])
            ->where('farmer_id', $farmerId)
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Debt
    {
        return Debt::query()
            ->with(['transaction.items.product', 'farmer', 'repaymentDebts.repayment'])
            ->find($id);
    }

    public function openDebtsForFarmer(int $farmerId): Collection
    {
        return Debt::query()
            ->where('farmer_id', $farmerId)
            ->whereIn('status', ['open', 'partial'])
            ->orderBy('created_at')
            ->get();
    }
}
