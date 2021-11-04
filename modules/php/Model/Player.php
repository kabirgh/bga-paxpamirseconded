<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;

class Player extends DbModel
{
  // The BGA framework pre-defined columns in the table for these fields. See
  // https://en.doc.boardgamearena.com/Main_game_logic:_yourgamename.game.php#Accessing_player_information
  // for more details.
  protected $player_id;
  protected $player_name;
  protected $player_color;
  protected $player_canal;
  protected $player_avatar;
  protected $player_score;
  // New fields
  protected $rupees;
  protected $faction;
  protected $loyalty;

  static public function create($params)
  {
    $instance = new self($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->player_id = $params['player_id'];
    $this->player_name = $params['player_name'];
    $this->player_color = $params['player_color'];
    $this->player_canal = $params['player_canal'];
    $this->player_avatar = $params['player_avatar'];
    $this->player_score = $params['player_score'];
    $this->rupees = $params['rupees'];
    $this->faction = $params['faction'];
    $this->loyalty = $params['loyalty'];
  }

  protected static function tableName()
  {
    return 'player';
  }

  protected static function primaryKey()
  {
    return 'player_id';
  }
}
