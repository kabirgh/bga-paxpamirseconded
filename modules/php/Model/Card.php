<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;
use PAX\Core\Game;

class Card extends DbModel
{
  // More card data stored in material.inc.php
  protected $id;
  protected $type;
  protected $deck_pos;

  static public function create($params)
  {
    $instance = new self($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->id = $params['id'];
    $this->type = $params['type'];
    $this->deck_pos = $params['deck_pos'];
  }

  protected static function tableName()
  {
    return 'card';
  }

  protected static function primaryKey()
  {
    return 'id';
  }

  // Static queries
  public static function queryByDeckPosAndLimit($deckPos, $limit)
  {
    $tableName = self::tableName();
    $result = Game::get()->DbQuery("SELECT id, type, deck_pos from {$tableName} WHERE deck_pos >= {$deckPos} ORDER BY deck_pos DESC LIMIT {$limit}");
  }
}
