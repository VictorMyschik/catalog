<?php

namespace App\Models\Lego\Fields;

use App\Models\Reference\MrCurrency;

trait MrCurrencyNullableFieldsTrait
{
  public function getCurrencyCash(): ?MrCurrency
  {
    return MrCurrency::loadBy($this->CurrencyCashID);
  }

  public function setCurrencyCashID(?int $value): void
  {
    $this->CurrencyCashID = $value;
  }

  /**
   * Валюта для РРЦ
   */
  public function getRecCurrencyCash(): ?MrCurrency
  {
    return MrCurrency::loadBy($this->RecCurrencyCashID);
  }

  public function setRecCurrencyCashID(?int $value): void
  {
    $this->RecCurrencyCashID = $value;
  }
  
  /**
   * Валюта для РРЦ
   */
  public function getRecCurrency(): ?MrCurrency
  {
    return MrCurrency::loadBy($this->RecCurrencyID);
  }

  public function setRecCurrencyID(?int $value): void
  {
    $this->RecCurrencyID = $value;
  }

  /// БезНал
  public function getCurrency(): ?MrCurrency
  {
    return MrCurrency::loadBy($this->CurrencyID);
  }

  public function setCurrencyID(?int $value): void
  {
    $this->CurrencyID = $value;
  }
}