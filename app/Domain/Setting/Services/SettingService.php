<?php

declare(strict_types=1);

namespace App\Domain\Setting\Services;

use App\Domain\Setting\Repositories\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

final class SettingService
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
    ) {}

    public function all(): Collection
    {
        return $this->settingRepository->all();
    }

    public function update(string $key, string $value): Setting
    {
        return $this->settingRepository->set($key, $value);
    }
}
