<?php

namespace PAX\Manager;

use PAX\Core\Game;
use PAX\Model\Player;

// Class with only static methods. Manages players
class PlayerManager
{
  public static function setupNewGame($playerMap, $options)
  {
    // Create players
    $gameinfos = Game::get()->getGameinfos();
    $colors = $gameinfos['player_colors'];

    $players = [];

    foreach ($playerMap as $player_id => $playerInfo) {
      $color = array_shift($colors);

      $players[] = Player::create([
        'player_id' => $player_id,
        'player_name' => $playerInfo['player_name'],
        'player_color' => $color,
        'player_canal' => $playerInfo['player_canal'],
        'player_avatar' => $playerInfo['player_avatar'],
        'player_score' => 0,
        'court_cards' => [],
        'event_cards' => [],
        'cylinders' => 10,
        'rupees' => 4,
        'faction' => null,
        'loyalty' => 0,
      ]);
    }

    Game::get()->reattributeColorsBasedOnPreferences($playerMap, $gameinfos['player_colors']);
    Game::get()->reloadPlayersBasicInfos();
  }

  public static function getUiData()
  {
    $players = Player::queryAll();
    $data = [];
    foreach ($players as $player) {
      $data[strval($player->id())] = $player->toArray();
    }
    return $data;
  }
}
