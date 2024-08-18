<?php

namespace App\Models\Lego\Traits;

use App\Models\Good\MrGood;

trait MrGoodFieldTrait
{
  public function getGood(): MrGood
  {
    return MrGood::loadByOrDie($this->GoodID);
  }

  public function setGoodID(int $value): void
  {
    $this->GoodID = $value;
  }
}