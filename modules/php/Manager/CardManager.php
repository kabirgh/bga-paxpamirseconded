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
        $map['prize'] = self::abbreviationToFaction($map['prize']);
        $map['patriot'] = self::abbreviationToFaction($map['patriot']);
        // TODO impact should be array, not json
        // TODO card actions right to left for easier html element positioning

        $cards[] = Card::create($map);
      }
    }
  }

  private static function abbreviationToFaction($abbr)
  {
    switch ($abbr) {
      case 'B':
        return 'British';
      case 'R':
        return 'Russian';
      case 'A':
        return 'Afghan';
      case null:
        return null;
      default:
        throw new Exception("Could not parse faction abbreviation '{$abbr}'");
    }
  }
}
