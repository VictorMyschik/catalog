<?php

declare(strict_types=1);

namespace App\Services\Catalog;

use App\Models\Catalog\CatalogType;
use App\Services\HTTPClientService\HTTPClient;
use App\Services\ImageUploader\ImageUploadService;
use Symfony\Component\DomCrawler\Crawler;

final class ImportOnlinerService
{
    public function __construct(
        private HTTPClient         $client,
        private CatalogService     $onlinerService,
        private ImageUploadService $imageService
    ) {}

    public function import(string $stringId, CatalogType $type, string $url, bool $isLoadImages): int
    {
        $cleanData = $this->client->doGet($url);

        $parsedData = $this->parseAttributes((string)$cleanData);
        $parsedData['string_id'] = $stringId;

        return $this->createGoodWithAllInfo($type, $parsedData, $url, (string)$cleanData, $isLoadImages);
    }

    private function createGoodWithAllInfo(CatalogType $type, array $parsedData, string $url, string $cleanData, bool $isLoadImages = true): int
    {
        $goodId = $this->onlinerService->saveGood(0, [
            'type_id'   => $type->id(),
            'name'      => $parsedData['good_name'],
            'string_id' => $parsedData['string_id'],
            'link'      => $url,
        ]);

        $attributes = $this->createCatalogAttributes($parsedData['attributes'], $type);

        $this->insertGoodAttributes($goodId, $attributes);

        $this->updateGoodWithManufacturer($goodId, $parsedData['string_id']);

        if ($isLoadImages) {
            $this->importOnlinerImagesCatalog($goodId, $cleanData);
        }

        return $goodId;
    }

    public function importOnlinerImagesCatalog(int $goodId, string $htmlData): void
    {
        $imageNames = $this->parseImgUrls($htmlData);

        foreach ($imageNames as $imageUrl) {
            $this->imageService->uploadImage($goodId, $imageUrl);
        }
    }

    private function parseImgUrls(string $data): array
    {
        $crawler = new Crawler($data);

        $tmp_link = $crawler->filter('div > .product-gallery__thumb')->each(function ($link, $i) {
            return $link->attr('data-original');
        });

        $links = array();

        foreach ($tmp_link as $key => $item) {
            if (str_contains((string)$item, 'https://imgproxy.onliner.by') !== false) {
                $links[] = $item;
            }
        }

        return $links;
    }

    private function updateGoodWithManufacturer(int $goodId, string $stringId): void
    {
        $jsonData = $this->client->doGet("https://catalog.onliner.by/sdapi/catalog.api/products/{$stringId}");
        $data = json_decode((string)$jsonData, true);

        $dataForUpdate['int_id'] = $data['id'] ?? null;

        if ($manufacturerJson = $data['manufacturer'] ?? null) {
            $manufacturer = $this->onlinerService->getManufacturerOrCreateNew([
                'name'    => $manufacturerJson['name'],
                'address' => $manufacturerJson['legal_address']
            ]);

            $dataForUpdate['manufacturer_id'] = $manufacturer->id();
        }

        $dataForUpdate['name'] = $data['name'] ?? null;

        if ($data['name_prefix'] ?? null) {
            $dataForUpdate['prefix'] = $data['name_prefix'];
        }

        $dataForUpdate['is_certification'] = $data['certification_required'] ?? false;
        $dataForUpdate['parent_good_id'] = $data['parent_key'] ?? null;
        $dataForUpdate['description'] = $data['description'] ?? null;
        $dataForUpdate['sl'] = $jsonData;

        $this->onlinerService->saveGood($goodId, $dataForUpdate);
    }

    private function insertGoodAttributes(int $goodId, array $attributes = array()): void
    {
        $newGoodAttributes = array();

        foreach ($attributes as $attribute) {
            $newGoodAttributes[] = array(
                'good_id'            => $goodId,
                'bool_value'         => is_null($attribute['bool']) ? null : (bool)$attribute['bool'],
                'attribute_value_id' => $attribute['attr_val'],
            );
        }

        if (count($newGoodAttributes)) {
            $this->onlinerService->createGoodAttributes($newGoodAttributes);
        }
    }

    private function createCatalogAttributes(array $data, CatalogType $type): array
    {
        $out = [];

        foreach ($data as $group_name => $sub_group) {
            $sortOrder = 1000;
            $group = $this->onlinerService->getGroupAttributeOrCreateNew($type->id(), $group_name, $sortOrder);

            foreach ($sub_group as $title => $value) {
                $catalogAttribute = $this->onlinerService->getCatalogAttributeOrCreateNew($group, $title);

                $this->onlinerService->getCatalogAttributeValueOrCreateNew($catalogAttribute, null);
                $catalogAttributeValue = $this->onlinerService->getCatalogAttributeValueOrCreateNew($catalogAttribute, $value['text_value'] ?: null);

                $out[] = [
                    'bool'     => $value['bool'],
                    'attr_val' => $catalogAttributeValue->id(),
                ];
            }
        }

        return $out;
    }

    private function parseAttributes($data): array
    {
        $crawler = new Crawler($data);
        $data = $crawler->filter('table')->filter('tr')->each(function ($tr) {
            return $tr->filter('td')->each(function ($td, $i) {
                if ($i === 0) { // наименование атрибута
                    $tmp = trim($td->text($td->html(), false));
                    $tmp = explode("\n", $tmp)[0] ?? null;

                    $out = $tmp;
                } else {// Значение атрибута
                    $class = null;

                    if (!empty($td->filter('span'))) {
                        if (!empty($td->filter('span')->extract(['class']))) {
                            $class = $td->filter('span')->extract(['class'])[0] ?? null;
                        }
                    }

                    $out = array(
                        'i'     => $class,
                        'value' => $td->filter('.value__text')->text($td->text(), false));
                }

                return $out;
            });
        });

        $attrFormatted = array();
        $title = '';
        // Удаление лишних разделов
        foreach ($data as $tmp_key => $tmp_item) {
            if ($tmp_item[0] === 'Описание') {
                unset($data[$tmp_key]);
            }
        }

        foreach ($data as $item) {
            if (count($item) === 1) {
                $title = $item[0];
                $attrFormatted[$item[0]] = array();
            } else {
                $tmpValue = null;
                $boolValue = null;
                if ($item[1]['i'] === 'i-x') {
                    $boolValue = false;
                } elseif ($item[1]['i'] === 'i-tip') {
                    $boolValue = true;
                }

                $tmpValue = $item[1]['value'] ?? null;

                $attrFormatted[$title][$item[0]] = array(
                    'bool'       => $boolValue,
                    'text_value' => strlen(trim($tmpValue)) ? trim($tmpValue) : null,
                );
            }
        }

        $out['good_name'] = $crawler->filter('.catalog-masthead__title')->text();
        $out['attributes'] = $attrFormatted;

        return $out;
    }

    private array $urls = [];

    public function parseUrlList(string $url, ?int $max = 0): array
    {
        if ($max && count($this->urls) > $max) {
            return $this->urls;
        }

        $json = json_decode((string)$this->client->doGet($url), true);

        if (isset($json['products']) && count($json['products'])) {
            foreach ($json['products'] as $product) {
                $this->urls[] = $product['html_url'];
            }

            $pageNumber = explode('=', $url);
            $newPageNumber = $pageNumber[0] . '=' . (++$pageNumber[1]);

            $this->parseUrlList($newPageNumber);
        }

        return $this->urls;
    }
}