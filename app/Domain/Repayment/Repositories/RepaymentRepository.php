<?php

declare(strict_types=1);

namespace App\Domain\Repayment\Repositories;

use App\Models\Repayment;
use Illuminate\Pagination\LengthAwarePaginator;

final class RepaymentRepository implements RepaymentRepositoryInterface
{
    public function paginate(int $perPage = 15, ?int $farmerId = null): LengthAwarePaginator
    {
        return Repayment::query()
            ->with(['farmer', 'operator', 'repaymentDebts.debt'])
            ->when($farmerId, fn ($q) => $q->where('farmer_id', $farmerId))
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Repayment
    {
        return Repayment::query()
            ->with(['farmer', 'operator', 'debts'])
            ->find($id);
    }

    public function create(array $data): Repayment
    {
        return Repayment::query()->create($data);
    }
}
