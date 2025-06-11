<?php

declare(strict_types=1);

namespace App\Models\Catalog\Onliner;

use App\Models\ORM\ORM;
use Illuminate\Support\Facades\Storage;

class OnCatalogImage extends ORM
{
    protected $table = 'on_catalog_images';

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

    public function getOriginalUrlExt(): string
    {
        if (!$this->getOriginalUrl()) {
            return $this->getUrl();
        }

        return $this->getOriginalUrl();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getUrl(): string
    {
        return Storage::url($this->getPath());
    }

    public function getFilePathWithName(): string
    {
        return $this->getPath();
    }
}
