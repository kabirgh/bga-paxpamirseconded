<?php

declare(strict_types=1);

namespace PAX\Manager;

use PAX\Core\Game;
use PAX\Model\CourtCard;
use PAX\Model\EventCard;

abstract class CardManager
{
  private static $deck;

  public static function setupNewGame($players, $options)
  {
    $courtCards = self::createCourtCards();
    $eventCards = self::createEventCards();
    $dominanceCards = self::createDominanceCards();

    // TODO verify deck builder
    // self::$deck = self::buildDeck($courtCards, $eventCards, $dominanceCards, count($players));
  }

  private static function buildDeck($courtCards, $eventCards, $dominanceCards, $numPlayers)
  {
    $n = 5 + $numPlayers;

    // n court cards
    $deck = array_slice($courtCards, 0, $n);

    // n court cards, 2 event cards
    $pile2 = array_merge(
      array_slice($courtCards, $n, $n),
      array_slice($eventCards, 0, 2)
    );
    shuffle($pile2);

    array_merge($deck, $pile2);

    for ($i = 2; $i < 6; $i++) {
      // n court cards, 1 event card, 1 dominance check
      $pile = array_merge(
        array_slice($courtCards, $i * $n, $n),
        array_slice($eventCards, $i, 1),
        array_slice($dominanceCards, $i, 1)
      );
      shuffle($pile);
      array_merge($deck, $pile);
    }

    return $deck;
  }

  private static function createCourtCards()
  {
    $jsonStr = file_get_contents(__DIR__ . '/court_card_info.json');
    $cardArr = json_decode($jsonStr, true);

    $cards = [];
    foreach ($cardArr as $map) {
      // TODO set ids
      if (isset($map['id']) and $map['id'] !== 999) {
        $map['type'] = 'court';
        // TODO order impact array
        // TODO order card actions right to left for easier html element positioning
        // TODO special

        $cards[] = CourtCard::create($map);
      }
    }

    shuffle($cards);
    return $cards;
  }

  private static function createEventCards()
  {
    $jsonStr = file_get_contents(__DIR__ . '/event_card_info.json');
    $cardArr = json_decode($jsonStr, true);

    // Excluding dominance checks
    $cards = [];
    foreach ($cardArr as $map) {
      $map['type'] = 'event';
      $cards[] = EventCard::create($map);
    }

    shuffle($cards);
    return $cards;
  }

  private static function createDominanceCards()
  {
    $cards = [];
    for ($id = 101; $id < 105; $id++) {
      $cards[] = EventCard::create([
        'id' => $id,
        'type' => 'event',
        'purchase' => 'Dominance Check',
        'discard' => 'Dominance Check',
      ]);
    }

    return $cards;
  }
}
