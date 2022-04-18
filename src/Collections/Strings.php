<?php declare(strict_types=1);

namespace Types\Collections;

class Strings extends Base
{
  protected array $itemsDataTypes = [
    'string',
  ];

  /**
   * Return items
   *
   * @return string[]
   */
  public function getItems(): array
  {
    return $this->items;
  }
}
