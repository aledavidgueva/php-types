<?php declare(strict_types=1);

namespace Types\Collections;

use Iterator;
use Countable;
use ArrayAccess;
use JsonSerializable;
use Types\Exceptions\CollectionException;

abstract class Base implements Iterator, Countable, ArrayAccess, JsonSerializable
{
  /**
   * Items de la colección
   *
   * @var mixed[]
   */
  protected array $items = [];

  /**
   * Data type of items
   *
   * @var array<string,string>
   */
  protected array $itemsDataTypes = [];

  /**
   * Rewind list pointer
   */
  public function rewind(): void
  {
    reset($this->items);
  }

  /**
   * Get current item
   */
  public function current(): mixed
  {
    return current($this->items);
  }

  /**
   * Get key of list pointer
   */
  public function key(): mixed
  {
    return key($this->items);
  }

  /**
   * Move list pointer to next
   */
  public function next(): void
  {
    next($this->items);
  }

  /**
   * Is valid list pointer
   */
  public function valid(): bool
  {
    $key = $this->key();
    return $key !== null && $key !== false;
  }

  /**
   * Count items
   */
  public function count(): int
  {
    return count($this->items);
  }

  /**
   * Offset exists
   */
  public function offsetExists(mixed $offset): bool
  {
    return key_exists($offset, $this->items);
  }

  /**
   * Offset get
   */
  public function offsetGet(mixed $offset): mixed
  {
    return $this->items[$offset];
  }

  /**
   * Offset set
   */
  public function offsetSet(mixed $offset, mixed $value): void
  {
    $this->_checkItemDataType($value);

    if (is_null($offset)) {
      $this->items[] = $value;
    } else {
      $this->items[$offset] = $value;
    }
  }

  /**
   * Offset unset
   */
  public function offsetUnset(mixed $offset): void
  {
    unset($this->items[$offset]);
  }

  /**
   * Check item data type
   *
   * @throws CollectionException thrown if $value isn't an data type allowed
   */
  protected function _checkItemDataType(mixed $value): void
  {
    if (!$this->itemsDataTypes) {
      return;
    }

    $type = gettype($value);

    if ($type === 'object') {
      foreach ($this->itemsDataTypes as $itemDataType) {
        if ($value instanceof (new $itemDataType())) {
          return;
        }
      }
    } elseif (in_array($type, $this->itemsDataTypes)) {
      return;
    }

    $varCast = ($type !== 'object')
                ? $type
                : get_class($value);

    throw new CollectionException("Type error: Type `${varCast}` not allowed on collection `".get_called_class().'`');
  }

  /**
   * Return items for json
   */
  public function jsonSerialize(): array
  {
    return $this->items;
  }
}
