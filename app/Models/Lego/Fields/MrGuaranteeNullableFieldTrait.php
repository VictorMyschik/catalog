<?php

namespace App\Models\Lego\Fields;

trait MrGuaranteeNullableFieldTrait
{
  public function getGuarantee(): ?int
  {
    return $this->Guarantee;
  }

  public function setGuarantee(?int $value): void
  {
    $this->Guarantee = $value;
  }
}