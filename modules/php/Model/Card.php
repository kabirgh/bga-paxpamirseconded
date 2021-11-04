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
  public static function queryByDeckPos($deckPos, $limit)
  {
    $rows = Game::get()->DbQuery("SELECT id, type, deck_pos from {$this->tableName()} WHERE deck_pos >= {$deckPos} ORDER BY deck_pos DESC LIMIT {$limit}");

    return array_map(function ($row) {
      return new self(['id' => $row[0], 'type' => $row[1], 'deck_pos' => $row[2]]);
    }, $rows);
  }
}
