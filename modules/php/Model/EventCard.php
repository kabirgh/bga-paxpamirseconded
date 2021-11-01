<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\Card;

class EventCard extends Card
{
  protected $id;
  protected $type;
  protected $purchase;
  protected $discard;

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
    $this->purchase = $params['purchase'];
    $this->discard = $params['discard'];
  }
}
