<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch;

use App\Models\Catalog\Good;
use Illuminate\Support\Facades\Log;

final readonly class ESArticlesService
{
    private const string INDEX = 'catalog';

    public function __construct(private ESClient $client) {}

    public function addGood(Good $good): void
    {
        $body = [
            'id'           => $good->id(),
            'prefix'       => $good->getPrefix(),
            'name'         => $good->getName(),
            'short_info'   => $good->getShortInfo(),
            'description'  => $good->getDescription(),
            'manufacturer' => $good->getManufacturer()?->getName(),
        ];

        try {
            $this->client->single(self::INDEX, $body);
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при попытке добавить товар в ES: ' . $e->getMessage());
        }

        Log::info('Good ' . $good->id() . ' успешно добавлен в ES', $body);
    }

    public function getByGoodId(int $id): array
    {
        return $this->client->getById(self::INDEX, $id);
    }
}
