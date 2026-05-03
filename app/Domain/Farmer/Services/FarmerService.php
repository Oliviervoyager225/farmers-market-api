<?php

declare(strict_types=1);

namespace App\Domain\Farmer\Services;

use App\Domain\Farmer\DTOs\FarmerDTO;
use App\Domain\Farmer\Repositories\FarmerRepositoryInterface;
use App\Models\Farmer;
use Illuminate\Pagination\LengthAwarePaginator;

final class FarmerService
{
    public function __construct(
        private readonly FarmerRepositoryInterface $farmerRepository,
    ) {}

    public function paginate(int $perPage = 15, ?string $search = null, ?int $operatorId = null): LengthAwarePaginator
    {
        return $this->farmerRepository->paginate($perPage, $search, $operatorId);
    }

    public function findOrFail(int $id): Farmer
    {
        $farmer = $this->farmerRepository->findById($id);

        abort_if($farmer === null, 404, 'Agriculteur introuvable.');

        return $farmer;
    }

    public function create(FarmerDTO $dto): Farmer
    {
        $existing = $this->farmerRepository->findByIdentifier($dto->identifier);

        abort_if($existing !== null, 409, 'Un agriculteur avec ce numéro de carte existe déjà.');

        return $this->farmerRepository->create($dto);
    }

    public function update(int $id, FarmerDTO $dto): Farmer
    {
        $farmer = $this->findOrFail($id);

        return $this->farmerRepository->update($farmer, $dto);
    }

    public function delete(int $id): void
    {
        $farmer = $this->findOrFail($id);

        $this->farmerRepository->delete($farmer);
    }
}
