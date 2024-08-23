<?php

declare(strict_types=1);

namespace App\Repositories\System;

use App\Models\System\Settings;

interface SettingsRepositoryInterface
{
    public function getSettingsList(): array;

    public function saveSetting(int $id, array $data): int;

    public function getByKey(string $key): ?Settings;
}
