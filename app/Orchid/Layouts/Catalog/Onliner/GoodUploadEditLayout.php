<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog\Onliner;

use App\Models\Catalog\Onliner\OnCatalogImage;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class GoodUploadEditLayout extends Rows
{
    public function fields(): array
    {
        /** @var OnCatalogImage $image */
        $image = $this->query->get('image');
        if (!$image) {
            $out[] = Upload::make('images')->groups('photo')->maxFiles(20)->path('/goods');
        } else {
            $out[] = Input::make('image.alt')->value($image?->getAlt())->title('alt')->type('text');
        }

        return $out;
    }
}
