<?php

namespace App\Models\Lego\Traits;

trait NameNullableFieldTrait
{
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $value): void
    {
        $this->name = $value;
    }
}