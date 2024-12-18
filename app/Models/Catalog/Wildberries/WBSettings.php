<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Enums\Shop\ShopSettingsTypeEnum;
use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class WBSettings extends ORM
{
    use AsSource;
    use Filterable;

    protected $table = 'wb_settings';

    public function getType(): ShopSettingsTypeEnum
    {
        return ShopSettingsTypeEnum::from($this->type);
    }

    protected $fillable = [
        'value',
    ];

    public $casts = [
        'id'         => 'integer',
        'type'       => 'integer',
        'value'      => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function geDisplayValue(): mixed
    {
        return match ($this->getType()) {
            ShopSettingsTypeEnum::ORDER => $this->value === '1' ? 'Вкл' : 'Выкл',
        };
    }

    public function setValue(?string $val): void
    {
        $this->value = match ($this->getType()) {
            ShopSettingsTypeEnum::ORDER => (bool)$val,
        };
    }
}
