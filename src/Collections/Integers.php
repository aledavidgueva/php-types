<?php declare(strict_types=1);

namespace Types\Collections;

class Integers extends Base
{
  protected array $itemsDataTypes = [
    'integer',
  ];

  /**
   * Return items
   *
   * @return int[]
   */
  public function getItems(): array
  {
    return $this->items;
  }
}
