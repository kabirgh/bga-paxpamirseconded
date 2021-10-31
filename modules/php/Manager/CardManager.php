<?php

declare(strict_types=1);

namespace PAX\Manager;

use Exception;
use PAX\Core\Game;
use PAX\Model\Card;

abstract class CardManager
{
  private static $deck;

  public static function setupNewGame($players, $options)
  {
    $jsonStr = file_get_contents(__DIR__ . '/court_card_info.json');
    $cardArr = json_decode($jsonStr, true);

    $cards = [];
    foreach ($cardArr as $map) {
      if (isset($map['id'])) {
        $map['type'] = 'court';
        $map['event_behavior'] = null;
        // TODO order impact array
        // TODO order card actions right to left for easier html element positioning
        // TODO special

        $cards[] = Card::create($map);
      }
    }
  }
}
