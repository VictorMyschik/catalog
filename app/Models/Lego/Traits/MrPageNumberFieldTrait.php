<?php

namespace App\Models\Lego\Traits;

/**
 * Описание полей и некоторой простой логики для обработки файлов excel
 * Применяется в MrSpecificationFile, MrStorkAutoload
 *
 * Trait MrSpecificationSettingsFieldTrait
 * @package App\Models\Lego
 */
trait MrPageNumberFieldTrait
{
  /**
   * Номер листа Excel-файла
   *
   * @return int
   */
  public function getPageNumber(): int
  {
    return $this->getJsonField('PageNumber') ?: 0;
  }

  public function setPageNumber(int $value): void
  {
    $this->setJsonField('PageNumber', $value);
  }

}