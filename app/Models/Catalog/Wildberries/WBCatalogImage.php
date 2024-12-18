<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\ORM\ORM;
use Illuminate\Support\Facades\Storage;

class WBCatalogImage extends ORM
{
    protected $table = 'wb_catalog_images';

    public function getPath(): string
    {
        return $this->path;
    }
    public function getFullPath(): string
    {
        return storage_path($this->path);
    }

    public function getUrl(): string
    {
        return Storage::url($this->getPath());
    }
}
