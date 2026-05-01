<?php

declare(strict_types=1);

namespace App\Domain\Setting\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

interface SettingRepositoryInterface
{
    public function all(): Collection;

    public function findByKey(string $key): ?Setting;

    public function set(string $key, string $value): Setting;
}
