<?php

namespace App\Models\Lego\Fields;

use App\Models\Marketplace\MrMarketplace;

trait MrMarketplaceFieldTrait
{
  public function getMarketplace(): MrMarketplace
  {
    return MrMarketplace::loadByOrDie($this->MarketplaceID);
  }

  public function setMarketplaceID(int $value): void
  {
    $this->MarketplaceID = $value;
  }
}