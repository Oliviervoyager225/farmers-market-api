<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Repositories;

use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function paginate(int $perPage = 15, ?int $operatorId = null, ?int $farmerId = null): LengthAwarePaginator;

    public function findById(int $id): ?Transaction;

    public function create(array $data): Transaction;
}
