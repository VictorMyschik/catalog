<?php

declare(strict_types=1);

namespace App\Models\Catalog\Onliner;

use App\Models\ORM\ORM;
use Illuminate\Support\Facades\Storage;

class OnCatalogImage extends ORM
{
    protected $table = 'on_catalog_images';

    protected $fillable = [
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
        if (!empty($this->path)) {
            return Storage::url($this->path);
        }

        return null;
    }
}
