<?php

declare(strict_types=1);

namespace App\Domain\Farmer\Repositories;

use App\Domain\Farmer\DTOs\FarmerDTO;
use App\Models\Farmer;
use Illuminate\Pagination\LengthAwarePaginator;

interface FarmerRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?int $operatorId = null): LengthAwarePaginator;

    public function findById(int $id): ?Farmer;

    public function findByIdentifier(string $identifier): ?Farmer;

    public function create(FarmerDTO $dto): Farmer;

    public function update(Farmer $farmer, FarmerDTO $dto): Farmer;

    public function delete(Farmer $farmer): void;
}
