<?php

declare(strict_types=1);

namespace App\Domain\Repayment\Repositories;

use App\Models\Repayment;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepaymentRepositoryInterface
{
    public function paginate(int $perPage = 15, ?int $farmerId = null): LengthAwarePaginator;

    public function findById(int $id): ?Repayment;

    public function create(array $data): Repayment;
}
