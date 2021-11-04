<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;

class Card extends DbModel
{
  // More card data stored in material.inc.php
  protected $id;
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

  public static function queryById($id)
  {
    $result = self::query()
      ->select(['id', 'deck_pos']) // Would be nice to use * instead
      ->where('id', $id)
      ->get(true);
    return new self($result);
  }

  public static function queryByDeckPosAndLimit($deckPos, $limit)
  {
    return self::query()
      ->select(['id', 'deck_pos'])
      ->where('deck_pos', '>=', $deckPos)
      ->orderBy('deck_pos', 'ASC')
      ->limit($limit)
      ->get()
      ->map(function ($row) {
        return new self($row);
      })
      ->toArray();
  }
}
