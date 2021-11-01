<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\Card;

class CourtCard extends Card
{
  protected $id;
  protected $name;
  protected $type;
  protected $region;
  // protected $suit;
  protected $rank;
  protected $patriot;
  protected $prize;
  protected $impact;
  protected $card_actions;
  protected $special;

  static public function create($params)
  {
    $instance = new self($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->id = $params['id'];
    $this->name = $params['name'];
    $this->type = $params['type'];
    $this->region =  $params['region'];
    // TODO suit causes insert error
    // $this->suit = $params['suit'];
    $this->rank = $params['rank'];
    $this->patriot = $params['patriot'];
    $this->prize = $params['prize'];
    $this->impact = $params['impact'];
    $this->card_actions = $params['card_actions'];
    $this->special = $params['special'];
  }
}
