<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Onliner\ImportOnlinerService;
use App\Services\Onliner\OnlinerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class CatalogController extends Controller
{
    public function __construct(
        private readonly ImportOnlinerService $importOnlinerService,
        private readonly OnlinerService       $service
    ) {}

    public function importLink(Request $request): RedirectResponse
    {
        $input = Validator::make($request->all(), [
            'url'            => 'required|max:255',
            'type_id'        => 'required|integer|exists:catalog_types,id',
            'is_load_images' => 'nullable|boolean',
            'complex'        => 'nullable|boolean',
        ])->validate();

        $catalogType = $this->service->getCatalogTypeById((int)$input['type_id']);

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

            $this->importOnlinerService->import($stringId, $catalogType, $url, $isLoadImages);

            $i++;
        }

        return back();
    }
}