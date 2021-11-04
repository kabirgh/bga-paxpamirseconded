<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;

class Globals extends DbModel
{
  protected $global_id;
  protected $global_value;

  static public function create($params)
  {
    $instance = new self($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->global_id = $params['global_id'];
    $this->global_value = $params['global_value'];
  }

  protected static function tableName()
  {
    return 'global';
  }

  protected static function primaryKey()
  {
    return 'global_id';
  }

  // Utility methods

  public function getValue()
  {
    return $this->get('global_value');
  }

  public function setValue($value)
  {
    $this->update(['global_value' => $value]);
  }

  // Many operations need to read a value, change it by an amount, and set it
  // again. This method abstracts that operation.
  public function addAmount($amount)
  {
    $this->update(['global_value' => $this->get('global_value') + $amount]);
  }

  // Static queries

  // TODO move into DbModel
  public static function queryById($id)
  {
    $result = self::query()
      ->select(['global_id', 'global_value']) // Would be nice to use * instead
      ->where('global_id', $id)
      ->get(true);
    return new self($result);
  }
}
