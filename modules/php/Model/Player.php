<?php

namespace PAX\Model;

class Player extends DbModel
{
  // The BGA framework pre-defined columns in the table for these fields. See
  // https://en.doc.boardgamearena.com/Main_game_logic:_yourgamename.game.php#Accessing_player_information
  // for more details.
  protected $player_id;
  protected $player_name;
  protected $player_color;
  protected $player_no; // Turn order within the game
  protected $player_score;
  // Unused
  protected $player_canal;
  protected $player_avatar;
  // New fields
  protected $rupees;
  protected $faction;
  protected $loyalty;

  static public function create($params)
  {
    $instance = new Player($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->player_id = $params['player_id'];
    $this->player_name = $params['player_name'];
    $this->player_no = $params['player_no'];
    $this->player_color = $params['player_color'];
    $this->player_score = $params['player_score'];
    $this->player_canal = $params['player_canal'];
    $this->player_avatar = $params['player_avatar'];
    $this->rupees = $params['rupees'];
    $this->faction = $params['faction'];
    $this->loyalty = $params['loyalty'];
  }

  protected function tableName()
  {
    return "player";
  }

  // We map $id to player_id so the superclass can update correctly
  protected function primaryKey()
  {
    return "player_id";
  }
}

// $p = Player::create([
//   'player_id' => 'id_1',
//   'player_name' => 'Bob',
//   'player_no' => 2,
//   'player_color' => 'red',
//   'player_score' => 0,
//   'player_canal' => 'canal',
//   'player_avatar' => 'avatar',
//   'rupees' => 4,
//   'faction' => 'Afghan',
//   'loyalty' => 2
// ]);
// $new = ["player_name" => "Alice", "player_score" => 7];
// $p->update($new) . "\n";
// print $p . "\n";
// print $p->commit($new) . "\n";
// Should fail
// $p->update(["doesnt_exist" => "qwe"]);
// $p->update(["player_id" => "id_new"]);
