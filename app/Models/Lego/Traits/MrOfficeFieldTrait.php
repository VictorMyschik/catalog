<?php

namespace App\Models\Lego\Traits;

use App\Exceptions\Handler;
use App\Models\Office\MrOffice;
use Symfony\Component\HttpFoundation\Response;

trait MrOfficeFieldTrait
{
  public function getOffice(): MrOffice
  {
    return MrOffice::loadByOrDie($this->OfficeID);
  }

  public function setOfficeID(int $value): void
  {
    abort_if(is_null(MrOffice::loadBy($value)), Response::HTTP_NOT_ACCEPTABLE, Handler::CODE_3001);

    $this->OfficeID = $value;
  }
}