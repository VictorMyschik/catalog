<?php

namespace App\Models\Lego\Traits;

use App\Models\Good\MrGood;

trait MrGoodNullableFieldTrait
{
  public function getGood(): ?MrGood
  {
    return MrGood::find($this->GoodID);
  }

  public function setGoodID(?int $value): void
  {
    $this->GoodID = $value;
  }
}