<?php

declare(strict_types=1);

namespace PAX\Manager;

use PAX\Core\Game;
use PAX\Model\Card;

class CardManager
{
  private const DECK_CURSOR_ID = '127';

  public static function setupNewGame($players, $options)
  {
    $game = Game::get();
    $deckJson = self::buildDeck(
      $game->courtCardData,
      $game->eventCardData,
      $game->dominanceCardData,
      count($players)
    );

    for ($i = 0; $i < count($deckJson); $i++) {
      $card = $deckJson[$i];
      Card::create([
        'id' => $card['id'],
        'type' => $card['type'],
        'deck_pos' => ($i + 1),
      ]);
    }

    // Initialize deck cursor (next card position)
    // TODO move global insert and read into another class
    $game->DbQuery("INSERT INTO global (global_id, global_value) VALUES (" . self::DECK_CURSOR_ID . ", 1)");
  }

  public static function getNextCard()
  {
    return self::getNextCards(1)[0];
  }

  public static function getNextCards($n)
  {
    // $deckPos = Globals::get(self::DECK_CURSOR_ID);
    // Card::queryBy
    // Globals::set(self::DECK_CURSOR_ID, $deckPos + $n);
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
}
