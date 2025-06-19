<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\ORM\ORM;
use Illuminate\Support\Facades\Storage;

class WBCatalogImage extends ORM
{
    protected $table = 'wb_catalog_images';

    protected $fillable = [
        'file_name',
        'good_id',
        'original_url',
        'path',
        'hash',
        'type',
        'media_type',
    ];

    public const null UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getOriginalUrl(): ?string
    {
        return $this->original_url;
    }

    public function getUrlExt(): string
    {
        return $this->getLocalFileUrl() ?: $this->getOriginalUrl();
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getLocalFileUrl(): ?string
    {
        if (!empty($this->path) && Storage::exists($this->path)) {
            return Storage::url($this->path);
        }

        return null;
    }
}
