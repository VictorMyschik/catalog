<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Catalog\Onliner\ImportOnlinerService;
use App\Services\Catalog\Onliner\OnlinerCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class CatalogImportController extends Controller
{
    public function __construct(
        private readonly ImportOnlinerService $importOnlinerService,
        private readonly OnlinerCatalogService $service
    ) {}

    public function importLink(Request $request): RedirectResponse
    {
        abort('manual disable');
        $input = Validator::make($request->all(), [
            'url'            => 'required|max:255',
            'type_id'        => 'required|integer|exists:catalog_groups,id',
            'is_load_images' => 'nullable|boolean',
            'complex'        => 'nullable|boolean',
        ])->validate();

        $catalogGroup = $this->service->getCatalogGroupById((int)$input['type_id']);

        // Макс товаров
        $max = $input['max'] ?? 0;

        $urls = array();

        // Раздел
        if ($input['complex']) {
            ini_set('max_execution_time', 500000000000);
            $catalogUrl = $input['url'];

            // Преобразование простой ссылки из браузера в ссылку для скачки раздела
            $tmpArr = explode('/', $catalogUrl);
            $article = array_pop($tmpArr);
            $templateUrl = "https://catalog.onliner.by/sdapi/catalog.api/search/{$article}?page=1";

            $urls = $this->importOnlinerService->parseUrlList($templateUrl, $max);
        } else { // Один товар
            $urls[] = $input['url']; // ссылка
        }

        $isLoadImages = (bool)$input['is_load_images'] ?? false;
        $i = 1;

        foreach ($urls as $url) {
            if ($max && $i > $max) {
                break;
            }

            $arrTmp = explode('/', $url);
            $stringId = array_pop($arrTmp);

            if (!$stringId || $this->service->isGoodExist($stringId)) {
                continue;
            }

            $this->importOnlinerService->import($stringId, $catalogGroup, $url, $isLoadImages);

            $i++;
        }

        return back();
    }
}
