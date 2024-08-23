<?php

declare(strict_types=1);

namespace App\Repositories\System;

use App\Models\System\Settings;
use App\Repositories\RepositoryBase;

final readonly class SettingsRepository extends RepositoryBase implements SettingsRepositoryInterface
{
    public function getSettingsList(): array
    {
        return Settings::get()->keyBy('code_key')->all();
    }

    public function saveSetting(int $id, array $data): int
    {
        $model = Settings::loadBy($id) ?? new Settings();

        $model->setActive((bool)$data['active']);
        $model->setCategory($data['category']);
        $model->setName($data['name']);
        $model->setValue($data['value']);
        $model->setCodeKey($data['code_key']);
        $model->setDescription($data['description']);

        $model->saveOrFail();

        return $model->id();
    }

    public function getByKey(string $key): ?Settings
    {
        return Settings::where('code_key', $key)->first();
    }
}
