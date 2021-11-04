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
        'player_score' => 1,
        'rupees' => 4,
        'faction' => null,
        'loyalty' => 0,
      ]);
    }

    Game::get()->reattributeColorsBasedOnPreferences($playerMap, $gameinfos['player_colors']);
    Game::get()->reloadPlayersBasicInfos();
  }
}
