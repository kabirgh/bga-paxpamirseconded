<?php

declare(strict_types=1);

namespace PAX\Manager;

use Exception;
use PAX\Core\Game;
use PAX\Model\CourtCard;
use PAX\Model\EventCard;

class CardManager
{
  private static $deck;

  public static function setupNewGame($players, $options)
  {
    $courtCards = self::getCourtCardsJson();
    $eventCards = self::getEventCardsJson();
    $dominanceCards = self::getDominanceCardsJson();

    // TODO verify deck builder
    $deckJson = self::buildDeck($courtCards, $eventCards, $dominanceCards, count($players));

    for ($i = 0; $i < count($deckJson); $i++) {
      $card = $deckJson[$i];

      // Messy, messy
      if ($card['type'] === 'court') {
        CourtCard::create(array_merge($card, ['deck_pos' => ($i + 1)]));
      } else if ($card['type'] === 'event') {
        EventCard::create(array_merge($card, ['deck_pos' => ($i + 1)]));
      } else {
        throw new Exception("Could not handle card of type `{$card['type']}`");
      }
    }
  }

  private static function debugPrintCardsJson($cards)
  {
    print json_encode(array_map(function ($c) {
      return ['id' => $c['id'], 'type' => $c['type']];
    }, $cards), JSON_PRETTY_PRINT);
    print "\nSize: " . count($cards) . "\n";
  }

  public static function getNextCard()
  {
    return self::getNextCards(1)[0];
  }

  public static function getNextCards($n)
  {
    $cards = array_slice(self::$deck, 0, $n);
    self::$deck = array_slice(self::$deck, $n);

    return $cards;
  }

  private static function buildDeck($courtCards, $eventCards, $dominanceCards, $numPlayers)
  {
    $n = 5 + $numPlayers;

    // n court cards
    $deck = array_slice($courtCards, 0, $n);
    shuffle($deck);

    // n court cards, 2 event cards
    $pile2 = array_merge(
      array_slice($courtCards, $n, $n),
      array_slice($eventCards, 0, 2)
    );
    shuffle($pile2);

    $deck = array_merge($deck, $pile2);

    for ($i = 0; $i < 4; $i++) {
      // n court cards, 1 event card, 1 dominance check
      $pile = array_merge(
        array_slice($courtCards, ($i + 2) * $n, $n),
        array_slice($eventCards, ($i + 2), 1),
        array_slice($dominanceCards, $i, 1)
      );

      shuffle($pile);
      $deck = array_merge($deck, $pile);
    }

    return $deck;
  }

  private static function getCourtCardsJson()
  {
    $jsonStr = file_get_contents(__DIR__ . '/court_card_info.json');
    $cardArr = json_decode($jsonStr, true);

    $cards = [];
    foreach ($cardArr as $map) {
      // TODO set ids
      if (isset($map['id']) and $map['id'] !== 999) {
        // TODO order impact array
        // TODO order card actions right to left for easier html element positioning
        // TODO special
        $cards[] = array_merge($map, ['type' => 'court']);
      }
    }

    shuffle($cards);
    return $cards;
  }

  private static function getEventCardsJson()
  {
    $jsonStr = file_get_contents(__DIR__ . '/event_card_info.json');
    $cardArr = json_decode($jsonStr, true);

    // Excluding dominance checks
    $cards = [];
    foreach ($cardArr as $map) {
      $cards[] = array_merge($map, ['type' => 'event']);
    }

    shuffle($cards);
    return $cards;
  }

  private static function getDominanceCardsJson()
  {
    $cards = [];
    for ($id = 101; $id < 105; $id++) {
      $cards[] = [
        'id' => $id,
        'type' => 'event',
        'purchase' => 'Dominance Check',
        'discard' => 'Dominance Check',
      ];
    }

    return $cards;
  }
}
