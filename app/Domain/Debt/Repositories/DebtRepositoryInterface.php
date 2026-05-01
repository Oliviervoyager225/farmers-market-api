<?php

declare(strict_types=1);

namespace App\Domain\Debt\Repositories;

use App\Models\Debt;
use Illuminate\Pagination\LengthAwarePaginator;

interface DebtRepositoryInterface
{
    public function paginateByFarmer(int $farmerId, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Debt;

    public function openDebtsForFarmer(int $farmerId): \Illuminate\Database\Eloquent\Collection;
}
