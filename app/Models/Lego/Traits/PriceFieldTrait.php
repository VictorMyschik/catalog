<?php

namespace App\Models\Lego\Traits;

trait PriceFieldTrait
{
  public function getPrice(): float
  {
    return $this->price;
  }

  public function setPrice(float $value): void
  {
    $this->price = $value;
  }
}