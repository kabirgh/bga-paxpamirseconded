<?php

declare(strict_types=1);

namespace PAX\Model;

use PAX\Model\DbModel;
use PAX\Database\Utils;

class Player extends DbModel
{
  // The BGA framework pre-defined columns in the table for some fields. See
  // https://en.doc.boardgamearena.com/Main_game_logic:_yourgamename.game.php#Accessing_player_information
  protected $player_id,
    $player_name,
    $player_color,
    $player_canal,
    $player_avatar,
    $player_score;
  // New fields
  protected $court_cards, // list of card ids
    $event_cards,
    $cylinders,
    $rupees,
    $faction,
    $loyalty;

  // Hydrated view of $court_cards
  private $courtCardObjects;

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
    $this->court_cards = $params['court_cards'];
    $this->event_cards = $params['event_cards'];
    $this->cylinders = intval($params['cylinders']);
    $this->rupees = intval($params['rupees']);
    $this->faction = $params['faction'];
    $this->loyalty = intval($params['loyalty']);

    $this->afterUpdate($params);
  }

  protected static function tableName()
  {
    return 'player';
  }

  protected static function primaryKey()
  {
    return 'player_id';
  }

  protected function afterUpdate($updatedProps)
  {
    if (isset($updatedProps['court_cards'])) {
      // Get Card objects from ids
      $this->courtCardObjects = array_map(
        function ($cardId) {
          return Card::queryById($cardId);
        },
        // $this->court_cards was modified in `update`
        $this->court_cards
      );
    }
  }

  // TODO handle right placement
  public function purchaseCard($cardId, $placement)
  {
    $this->update(['court_cards' => array_merge($this->court_cards, [$cardId])]);
  }

  private function sumRankForSuit($suit)
  {
    // Total rank of $suit cards
    $sum = 0;
    foreach ($this->courtCardObjects as $card) {
      if ($card['suit'] === $suit) {
        $sum += $card->rank();
      }
    }
    return $sum;
  }

  public function courtCapacity()
  {
    return 3 + $this->sumRankForSuit('Political');
  }

  public function handCapacity()
  {
    return 2 + $this->sumRankForSuit('Intelligence');
  }

  public function taxShelter()
  {
    return $this->sumRankForSuit('Economic');
  }

  public function militaryStars()
  {
    return $this->sumRankForSuit('Military');
  }

  public function toArray()
  {
    // Transform to make frontend happy
    return [
      'id' => $this->player_id,
      'color' => $this->player_color,
      'score' => $this->player_score,
      'court_cards' => $this->court_cards,
      'event_cards' => $this->event_cards,
      'cylinders' => $this->cylinders,
      'rupees' => $this->rupees,
      'faction' => $this->faction,
      'loyalty' => $this->loyalty,
      'courtCapacity' => $this->courtCapacity(),
      'handCapacity' => $this->handCapacity(),
      'taxShelter' => $this->taxShelter(),
      'militaryStars' => $this->militaryStars(),
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
