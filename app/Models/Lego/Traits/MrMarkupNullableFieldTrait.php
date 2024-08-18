<?php

namespace App\Models\Lego\Traits;

use App\Models\Office\MrOfficeSettings;

trait MrMarkupNullableFieldTrait
{
  public function getMarkup(): ?float
  {
    return $this->Markup;
  }

  public function getMarkupExt(): float
  {
    $out = $this->getStock()->getOffice()->getOfficeSettings()['Data'][MrOfficeSettings::S_MARKUP];

    if(!is_null($this->getMarkup()))
      $out = $this->getMarkup();

    return $out;
  }

  public function setMarkup(?float $value): void
  {
    $this->Markup = $value;
  }

  public function getMarkupCash(): ?float
  {
    return $this->MarkupCash;
  }

  public function getMarkupCashExt(): float
  {
    $out = $this->getStock()->getOffice()->getOfficeSettings()[MrOfficeSettings::S_MARKUP_CASH]['Default'];

    if(!is_null($this->getMarkupCash()))
      $out = $this->getMarkupCash();

    return $out;
  }

  public function setMarkupCash(?float $value): void
  {
    $this->MarkupCash = $value;
  }
}