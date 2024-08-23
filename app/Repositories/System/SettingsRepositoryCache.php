<?php

declare(strict_types=1);

namespace App\Repositories\System;

use App\Models\System\Settings;
use Psr\SimpleCache\CacheInterface;

readonly class SettingsRepositoryCache implements SettingsRepositoryInterface
{
    private const string API_SETTINGS_LIST_CACHE_KEY = 'settings_list';

    public function __construct(private SettingsRepositoryInterface $repository, private CacheInterface $cache) {}

    public function getSettingsList(): array
    {
        $this->clearCache();
        return $this->cache->rememberForever(self::API_SETTINGS_LIST_CACHE_KEY, function () {
            return $this->repository->getSettingsList();
        });
    }

    public function getByKey(string $key): ?Settings
    {
        return $this->getSettingsList()[$key] ?? $this->repository->getByKey($key);
    }

    public function saveSetting(int $id, array $data): int
    {
        $id = $this->repository->saveSetting($id, $data);

        $this->clearCache();

        return $id;
    }

    private function clearCache(): void
    {
        $this->cache->delete(self::API_SETTINGS_LIST_CACHE_KEY);
    }
}
