<?php

declare(strict_types=1);

namespace App\Domain\Farmer\Repositories;

use App\Domain\Farmer\DTOs\FarmerDTO;
use App\Models\Farmer;
use Illuminate\Pagination\LengthAwarePaginator;

final class FarmerRepository implements FarmerRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return Farmer::query()
            ->with('operator')
            ->when($search, function ($query, $search) {
                $query->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('identifier', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Farmer
    {
        return Farmer::query()->with(['operator', 'debts'])->find($id);
    }

    public function findByIdentifier(string $identifier): ?Farmer
    {
        return Farmer::query()->where('identifier', $identifier)->first();
    }

    public function create(FarmerDTO $dto): Farmer
    {
        return Farmer::query()->create([
            'identifier'       => $dto->identifier,
            'firstname'        => $dto->firstname,
            'lastname'         => $dto->lastname,
            'phone'            => $dto->phone,
            'credit_limit_fcfa' => $dto->creditLimitFcfa,
            'operator_id'      => $dto->operatorId,
        ]);
    }

    public function update(Farmer $farmer, FarmerDTO $dto): Farmer
    {
        $farmer->update([
            'firstname'        => $dto->firstname,
            'lastname'         => $dto->lastname,
            'phone'            => $dto->phone,
            'credit_limit_fcfa' => $dto->creditLimitFcfa,
        ]);

        return $farmer->fresh(['operator', 'debts']);
    }

    public function delete(Farmer $farmer): void
    {
        $farmer->delete();
    }
}
