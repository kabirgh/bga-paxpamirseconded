<?php

namespace PAX\Model;

use PAX\Model\DbModel;

class Card extends DbModel
{
  protected $id;
  protected $name;
  protected $type;
  protected $location;
  protected $suit;
  protected $rank;
  protected $patriot;
  protected $prize;
  protected $impact;
  protected $card_actions;
  protected $event_behavior;
  protected $special;

  static public function create($params)
  {
    $instance = new Player($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->id = $params['id'];
    $this->name = $params['name'];
    $this->type = $params['type'];
    $this->location = $params['location'];
    $this->suit = $params['suit'];
    $this->rank = $params['rank'];
    $this->patriot = $params['patriot'];
    $this->prize = $params['prize'];
    $this->impact = $params['impact'];
    $this->card_actions = $params['card_actions'];
    $this->event_behavior = $params['event_behavior'];
    $this->special = $params['special'];
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
