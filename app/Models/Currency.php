<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Lego\Traits\DescriptionNullableFieldTrait;
use App\Models\Lego\Traits\NameFieldTrait;
use App\Models\ORM\ORM;
use Carbon\Carbon;

class Currency extends ORM
{
    use NameFieldTrait;
    use DescriptionNullableFieldTrait;

    protected $table = 'currencies';
    protected $casts = [
        'id'          => 'int',
        'code'        => 'string',
        'text_code'   => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'date_from'   => 'datetime',
        'date_to'     => 'datetime',
        'name'        => 'string',
        'rounding'    => 'int',
        'description' => 'string',
    ];

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTextCode(): string
    {
        return $this->text_code;
    }

    public function getDateFrom(): ?Carbon
    {
        return $this->date_from;
    }

    public function getDateTo(): ?Carbon
    {
        return $this->date_to;
    }
}