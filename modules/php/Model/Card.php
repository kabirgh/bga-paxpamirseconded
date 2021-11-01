<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;

abstract class Card extends DbModel
{
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
