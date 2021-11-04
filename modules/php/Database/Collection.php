<?php

namespace PAX\Database;

class Collection extends \ArrayObject
{
  public function getIds()
  {
    return array_keys($this->getArrayCopy());
  }

  public function empty()
  {
    return empty($this->getArrayCopy());
  }

  public function first()
  {
    $arr = $this->toArray();
    return isset($arr[0]) ? $arr[0] : null;
  }

  public function last()
  {
    $arr = $this->toArray();
    return empty($arr) ? null : $arr[count($arr) - 1];
  }

  public function toArray()
  {
    return array_values($this->getArrayCopy());
  }

  public function toAssoc()
  {
    return $this->getArrayCopy();
  }

  public function map($func)
  {
    return new Collection(array_map($func, $this->toAssoc()));
  }

  public function merge($arr)
  {
    return new Collection(array_merge($this->toAssoc(), $arr->toAssoc()));
  }

  public function reduce($func, $init)
  {
    return array_reduce($this->toArray(), $func, $init);
  }

  public function filter($func)
  {
    return new Collection(array_filter($this->toAssoc(), $func));
  }

  public function ui()
  {
    return $this->map(function ($elem) {
      return $elem->jsonSerialize();
    })->toArray();
  }
}
