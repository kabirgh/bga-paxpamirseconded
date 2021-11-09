<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;
use PAX\Database\Utils;

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
  protected $court_cards; // list of card ids
  protected $event_cards;
  protected $cylinders;
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
    $this->player_score = intval($params['player_score']);
    // Do not re-encode if already a valid json string
    $this->court_cards = Utils::jsonEncode($params['court_cards']);
    $this->event_cards = Utils::jsonEncode($params['event_cards']);
    $this->cylinders = intval($params['cylinders']);
    $this->rupees = intval($params['rupees']);
    $this->faction = $params['faction'];
    $this->loyalty = intval($params['loyalty']);
  }

  protected static function tableName()
  {
    return 'player';
  }

  protected static function primaryKey()
  {
    return 'player_id';
  }

  public function toArray()
  {
    // Transform to make frontend happy
    return [
      'id' => $this->player_id,
      'color' => $this->player_color,
      'score' => $this->player_score,
      'court_cards' => json_decode($this->court_cards),
      'event_cards' => json_decode($this->event_cards),
      'cylinders' => $this->cylinders,
      'rupees' => $this->rupees,
      'faction' => $this->faction,
      'loyalty' => $this->loyalty,
    ];
  }

  /**
   * @return Player[]
   */
  public static function queryAll()
  {
    return self::query()
      ->get()
      ->map(function ($row) {
        return new self($row);
      })
      ->toArray();
  }
}
