<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Repositories;

use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;

final class TransactionRepository implements TransactionRepositoryInterface
{
    public function paginate(int $perPage = 15, ?int $operatorId = null, ?int $farmerId = null): LengthAwarePaginator
    {
        return Transaction::query()
            ->with(['farmer', 'operator', 'items.product', 'debt'])
            ->when($operatorId, fn ($q) => $q->where('operator_id', $operatorId))
            ->when($farmerId, fn ($q) => $q->where('farmer_id', $farmerId))
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Transaction
    {
        return Transaction::query()
            ->with(['farmer', 'operator', 'items.product', 'debt'])
            ->find($id);
    }

    public function create(array $data): Transaction
    {
        return Transaction::query()->create($data);
    }
}
