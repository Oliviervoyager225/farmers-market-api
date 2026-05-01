<?php

declare(strict_types=1);

namespace App\Domain\Setting\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

final class SettingRepository implements SettingRepositoryInterface
{
    public function all(): Collection
    {
        return Setting::query()->orderBy('key')->get();
    }

    public function findByKey(string $key): ?Setting
    {
        return Setting::query()->where('key', $key)->first();
    }

    public function set(string $key, string $value): Setting
    {
        return Setting::set($key, $value);
    }
}
