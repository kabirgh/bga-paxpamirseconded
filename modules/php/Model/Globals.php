<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;
use PAX\Core\Game;
use PAX\Database\Result;

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

  // Getters
  public function getValue()
  {
    return $this->global_value;
  }

  // Static queries
  public static function queryById($id)
  {
    $result = self::query()
      ->select(['global_id', 'global_value']) // Would be nice to use * instead
      ->where('global_id', $id)
      ->get(true);
    return new self($result);

    // $tableName = self::tableName();
    // // TODO refactor
    // $mysqli = Game::get()->DbQuery("SELECT * from {$tableName} WHERE global_id = {$id}");
    // $result = (new Result($mysqli, 'getById'))->load();
    // return new self($result);
  }
}
