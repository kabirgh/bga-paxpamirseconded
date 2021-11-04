<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;

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

  protected function tableName()
  {
    return 'card';
  }

  // We map $id to player_id so the superclass can update correctly
  protected function primaryKey()
  {
    return 'id';
  }
}
