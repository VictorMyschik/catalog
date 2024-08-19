<?php

declare(strict_types=1);

namespace App\Services\Onliner;

use App\Models\CatalogType;
use Illuminate\Support\Facades\DB;

final readonly class ImportOnlinerService
{
    public function __construct() {}

    public function import(string $stringId, CatalogType $type, string $url, bool $isLoadImages): void
    {
        // Получение данных по ссылке
        $cleanData = APIServiceBase::loadUrlData($url, true);

        // Парсинг сырых данных, поиск атрибутов
        $parsedData = self::parseAttributes($cleanData);
        $parsedData['string_id'] = $stringId;

        // Создание товарной позиции
        $good = self::createGoodWithAllInfo($type, $parsedData, $url, $cleanData, $isLoadImages);

        // MrGoodVendorCodeJob::dispatch($parsedData['string_id'], $good->id());
    }

    /**
     * Parsing loaded XML file
     */
    public static function parse(string $str): void
    {
        $xml = simplexml_load_string($str);

        $category = (string)$xml->category->attributes()->name;

        foreach ($xml->category as $categoryXml) {
            if (empty($categoryXml->vendor))
                continue;

            $data = [];
            foreach ($categoryXml->vendor as $item) {
                $vendor = (string)$item->attributes()->name;

                foreach ($item->model as $value) {
                    foreach ($value->attributes() as $key => $val) {
                        if ($key === 'id')
                            $id = (string)$val;
                        elseif ($key === 'name')
                            $name = (string)$val;
                    }

                    $data[] = [
                        'OnlinerId' => $id,
                        'Category'  => $category,
                        'Vendor'    => $vendor,
                        'Name'      => $name
                    ];
                }
            }

            DB::table('books')->upsert($data, ['OnlinerId'], ['Category', 'Vendor', 'Name']);
        }
    }
}