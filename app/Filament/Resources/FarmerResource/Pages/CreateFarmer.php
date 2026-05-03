<?php

declare(strict_types=1);

namespace App\Filament\Resources\FarmerResource\Pages;

use App\Filament\Resources\FarmerResource;
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $specialties = [];
        if (isset($data['specialties_veg']) && is_array($data['specialties_veg'])) {
            $specialties = array_merge($specialties, $data['specialties_veg']);
        }
        if (isset($data['specialties_ani']) && is_array($data['specialties_ani'])) {
            $specialties = array_merge($specialties, $data['specialties_ani']);
        }
        $data['specialties'] = $specialties;

        unset($data['specialties_veg']);
        unset($data['specialties_ani']);

        return $data;
    }
}
