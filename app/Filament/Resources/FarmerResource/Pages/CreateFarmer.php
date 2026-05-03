<?php

declare(strict_types=1);

namespace App\Filament\Resources\FarmerResource\Pages;

use App\Filament\Resources\FarmerResource;
use App\Models\Farmer;
use Filament\Resources\Pages\CreateRecord;

class CreateFarmer extends CreateRecord
{
    protected static string $resource = FarmerResource::class;

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Add Farmer')
            ->color('success')
            ->icon('heroicon-m-check');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction(),
            $this->getCreateFormAction(),
        ];
    }

    public function getFormActionsAlignment(): string|\Filament\Support\Enums\Alignment
    {
        return \Filament\Support\Enums\Alignment::Right;
    }

    /**
     * Pré-remplir l'identifiant auto-généré et l'opérateur avant l'affichage du form.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['identifier'] = Farmer::generateNextIdentifier();
        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Fusionner specialties_veg + specialties_ani → specialties
        $specialties = [];
        if (isset($data['specialties_veg']) && is_array($data['specialties_veg'])) {
            $specialties = array_merge($specialties, $data['specialties_veg']);
        }
        if (isset($data['specialties_ani']) && is_array($data['specialties_ani'])) {
            $specialties = array_merge($specialties, $data['specialties_ani']);
        }
        $data['specialties'] = $specialties;

        unset($data['specialties_veg'], $data['specialties_ani']);

        // L'identifier est disabled dans le form → il n'est pas soumis, on le regénère
        if (empty($data['identifier'])) {
            $data['identifier'] = Farmer::generateNextIdentifier();
        }

        // operator_id auto-assigné à l'utilisateur connecté
        if (empty($data['operator_id'])) {
            $data['operator_id'] = auth()->id();
        }

        return $data;
    }
}
