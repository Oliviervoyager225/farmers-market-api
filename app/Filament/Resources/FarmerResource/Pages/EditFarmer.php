<?php

declare(strict_types=1);

namespace App\Filament\Resources\FarmerResource\Pages;

use App\Filament\Resources\FarmerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFarmer extends EditRecord
{
    protected static string $resource = FarmerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $specialties = $data['specialties'] ?? [];
        if (is_string($specialties)) {
            $specialties = json_decode($specialties, true) ?? [];
        }

        $vegList = ['Agriculteur', 'Maraîcher', 'Arboriculteur', 'Riziculteur', 'Cacaoculteur', 'Caféiculteur', 'Horticulteur', 'Pépiniériste'];
        $aniList = ['Éleveur', 'Aviculteur', 'Boviniculteur', 'Porciniculteur', 'Pisciculteur', 'Apiculteur'];

        $data['specialties_veg'] = array_values(array_intersect($specialties, $vegList));
        $data['specialties_ani'] = array_values(array_intersect($specialties, $aniList));

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $specialties = [];
        if (isset($data['specialties_veg']) && is_array($data['specialties_veg'])) {
            $specialties = array_merge($specialties, $data['specialties_veg']);
        }
        if (isset($data['specialties_ani']) && is_array($data['specialties_ani'])) {
            $specialties = array_merge($specialties, $data['specialties_ani']);
        }
        $data['specialties'] = $specialties;

        unset($data['specialties_veg'], $data['specialties_ani']);

        // Conserver l'identifier existant (champ disabled, non soumis)
        if (empty($data['identifier'])) {
            $data['identifier'] = $this->record->identifier;
        }

        return $data;
    }
}
