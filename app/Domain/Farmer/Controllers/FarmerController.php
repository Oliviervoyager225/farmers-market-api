<?php

declare(strict_types=1);

namespace App\Domain\Farmer\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Farmer\DTOs\FarmerDTO;
use App\Domain\Farmer\Requests\StoreFarmerRequest;
use App\Domain\Farmer\Requests\UpdateFarmerRequest;
use App\Domain\Farmer\Services\FarmerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class FarmerController extends Controller
{
    public function __construct(
        private readonly FarmerService $farmerService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $search  = $request->query('search');

        return response()->json(
            $this->farmerService->paginate($perPage, $search),
        );
    }

    public function show(int $farmer): JsonResponse
    {
        return response()->json([
            'data' => $this->farmerService->findOrFail($farmer),
        ]);
    }

    public function store(StoreFarmerRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['operator_id' => $request->user()->id]);

        $farmer = $this->farmerService->create(FarmerDTO::fromArray($data));

        return response()->json([
            'message' => 'Agriculteur créé avec succès.',
            'data'    => $farmer,
        ], 201);
    }

    public function update(UpdateFarmerRequest $request, int $farmer): JsonResponse
    {
        $data = array_merge($request->validated(), ['operator_id' => $request->user()->id, 'identifier' => '']);

        $updated = $this->farmerService->update($farmer, FarmerDTO::fromArray($data));

        return response()->json([
            'message' => 'Agriculteur mis à jour avec succès.',
            'data'    => $updated,
        ]);
    }

    public function destroy(int $farmer): JsonResponse
    {
        $this->farmerService->delete($farmer);

        return response()->json([
            'message' => 'Agriculteur supprimé avec succès.',
        ]);
    }
}
