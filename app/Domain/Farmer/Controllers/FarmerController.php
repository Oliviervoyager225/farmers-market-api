<?php

declare(strict_types=1);

namespace App\Domain\Farmer\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmerResource;
use App\Domain\Farmer\DTOs\FarmerDTO;
use App\Domain\Farmer\Requests\StoreFarmerRequest;
use App\Domain\Farmer\Requests\UpdateFarmerRequest;
use App\Domain\Farmer\Services\FarmerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FarmerController extends Controller
{
    public function __construct(
        private readonly FarmerService $farmerService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = (int) $request->query('per_page', 15);
        $search  = $request->query('search');

        return FarmerResource::collection(
            $this->farmerService->paginate($perPage, $search, null),
        );
    }

    public function show(int $farmer): JsonResponse
    {
        return response()->json([
            'data' => new FarmerResource($this->farmerService->findOrFail($farmer)),
        ]);
    }

    public function store(StoreFarmerRequest $request): JsonResponse
    {
        $data   = array_merge($request->validated(), ['operator_id' => $request->user()->id]);
        $farmer = $this->farmerService->create(FarmerDTO::fromArray($data));

        return response()->json([
            'message' => 'Agriculteur créé avec succès.',
            'data'    => new FarmerResource($farmer),
        ], 201);
    }

    public function update(UpdateFarmerRequest $request, int $farmer): JsonResponse
    {
        $data    = array_merge(
            $request->validated(),
            ['operator_id' => $request->user()->id, 'identifier' => ''],
        );
        $updated = $this->farmerService->update($farmer, FarmerDTO::fromArray($data));

        return response()->json([
            'message' => 'Agriculteur mis à jour avec succès.',
            'data'    => new FarmerResource($updated),
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
