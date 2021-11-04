<?php

namespace PAX\Manager;

use Exception;
use PAX\Manager\CardManager;
use PAX\Model\Card;
use PAX\Model\Globals;

class MarketManager
{
  // Card ids and coin amounts stored in globals table

  //   col:  0    1    2    3    4    5
  // row 0: 400, 401, 402, 403, 404, 405
  // row 1: 406, 407, 408, 409, 410, 411
  const CARD_BASE = 400;

  //   col:  0    1    2    3    4    5
  // row 0: 500, 501, 502, 503, 504, 505
  // row 1: 506, 507, 508, 509, 510, 511
  const COIN_BASE = 500;

  public static function setupNewGame($playerMap, $options)
  {
    $cards = CardManager::getNextCards(12);

    for ($i = 0; $i < 12; $i++) {
      self::createCardAtPosition($cards[$i]->id(), $i);
      self::createCoinAtPosition(0, $i);
    }
  }

  // TODO validate somewhere that player is not purchasing a card they put coins on
  // Remove card from location, add coins to cards to the left
  public static function purchaseCard($row, $col)
  {
    for ($c = $col - 1; $c >= 0; $c--) {
      if (self::isCardAtPosition($row, $c)) {
        $r = $row;
      } else if (self::isCardAtPosition(self::otherRow($row), $c)) {
        $r = self::otherRow($row);
      } else {
        throw new Exception("There are no card on either row at column {$c}");
      }

      self::addCoinsToPosition(1, $r, $c);
    }

    $card = self::getCardAtPosition($row, $col);
    self::setCardAtPosition(0, $row, $col);
  }

  // TODO caller must reduce player coins even if market doesn't have enought
  // cards to take all the money
  // Add a coin to each card moving right to left
  public static function payForAction($actionRank)
  {
    $coins = $actionRank * 2;

    for ($col = 5; $col >= 0; $col--) {
      for ($row = 0; $row < 2; $row++) {
        if (self::isCardAtPosition($row, $col)) {
          self::addCoinsToPosition(1, $row, $col);
          $coins -= 1;
        }
      }
    }
  }

  public static function endTurn()
  {
    // Slide cards to the left
    // Discard event cards or dominance checks if they are on the leftmost row
    // Replenish cards
  }

  // Serialize market into an array for display
  public static function getUiData()
  {
    $market = [];

    for ($i = 0; $i < 12; $i++) {
      $x = Globals::queryById(self::CARD_BASE + $i)->getValue();

      $market[] = [
        'cardId' => Globals::queryById(self::CARD_BASE + $i)->getValue(),
        'coin' => Globals::queryById(self::COIN_BASE + $i)->getValue(),
      ];
    }

    return $market;
  }

  private static function otherRow($row)
  {
    return $row === 0 ? 1 : 0;
  }

  private static function isCardAtPosition($row, $col)
  {
    return self::positionToGlobal('card', $row, $col)->getValue() !== 0;
  }

  // type is 'card' or 'coin'
  private static function positionToGlobal($type, $row, $col)
  {
    if ($type == 'card') {
      $id = self::CARD_BASE + ($row * 6) + $col;
    } else if ($type == 'coin') {
      $id = self::COIN_BASE + ($row * 6) + $col;
    } else {
      throw new Exception("Unexpected type {$type}");
    }

    return Globals::queryById($id);
  }

  private static function getCardAtPosition($row, $col)
  {
    $gCard = self::positionToGlobal('card', $row, $col);
    return Card::queryById($gCard->getValue());
  }

  private static function setCardAtPosition($cardId, $row, $col)
  {
    $gCard = self::positionToGlobal('card', $row, $col);
    $gCard->setValue($cardId);
  }

  private static function addCoinsToPosition($amount, $row, $col)
  {
    $gCoin = self::positionToGlobal('coin', $row, $col);
    $gCoin->addAmount($amount);
  }

  // Only used during setupNewGame
  private static function createCardAtPosition($cardId, $index)
  {
    Globals::create(['global_id' => self::CARD_BASE + $index, 'global_value' => $cardId]);
  }

  // Only used during setupNewGame
  private static function createCoinAtPosition($amount, $index)
  {
    Globals::create(['global_id' => self::COIN_BASE + $index, 'global_value' => $amount]);
  }
}
