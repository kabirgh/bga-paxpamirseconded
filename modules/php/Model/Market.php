<?php

declare(strict_types=1);

namespace PAX\Model;

use Exception;
use PAX\Database\Utils;
use PAX\Model\DbModel;
use PAX\Manager\CardManager;

class Market extends DbModel
{
  protected $id,
    $cards, // list of card ids
    $coins; // list of coin amounts
  /**
   * Arrangement by index
   * 0  1  2  3  4  5
   * 6  7  8  9  10 11
   */

  private $cardObjects;

  public static function setupNewGame()
  {
    $cards = CardManager::getNextCards(12);

    Market::create([
      'id' => 1,
      'cards' => array_map(function ($card) {
        return $card->id();
      }, $cards),
      'coins' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
    ]);
  }

  // Unlike other DbModels, entry point to Market is in setupNewGame
  protected static function create($params)
  {
    $instance = new self($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->id = $params['id'];
    $this->cards = $params['cards'];
    $this->coins = $params['coins'];

    $this->afterUpdate($params);
  }

  protected function afterUpdate($updatedProps)
  {
    if (isset($updatedProps['cards'])) {
      // Get Card objects from ids
      $this->cardObjects = array_map(
        function ($cardId) {
          return $cardId !== null ? Card::queryById($cardId) : null;
        },
        // $this->cards was modified in `update`
        $this->cards
      );
    }
  }

  protected static function tableName()
  {
    return 'market';
  }

  protected static function primaryKey()
  {
    return 'id';
  }

  // Singleton object
  public static function get()
  {
    $result = self::query()
      ->select(['id', 'cards', 'coins']) // Would be nice to use * instead
      ->where('id', 1)
      ->get(true);
    return new self($result);
  }

  // TODO validate somewhere that player is not purchasing a card they put coins on
  // Remove card from location, add coins to cards to the left
  public function purchaseCard($i)
  {
    for ($col = $this->col($i); $col >= 0; $col--) {
      if ($this->isCardAtPosition($i)) {
        $indexForCoin = $i;
      } else if ($this->isCardAtPosition($this->indexOnOtherRow($i))) {
        $indexForCoin = $this->indexOnOtherRow($i);
      } else {
        throw new Exception("There are no cards on both rows at column " . $col);
      }

      $this->addCoinsToPosition(1, $indexForCoin);
    }

    $cardId = $this->cards[$i];
    $this->setCardAtPosition(null, $i);
    return $cardId;
  }

  /**
   * Update market after a player's turn ends.
   * 1. Discard event cards in leftmost column
   * 2. Move cards to left, filling in gaps
   * 3. Draw new cards to fill spaces on the right
   */
  public function endTurn()
  {
    $newCards = $this->cards; // php arrays are copy-by-value

    // Discard event cards in leftmost column
    $discardedCardIds = [];
    foreach ([0, 6] as $col) {
      $cardObj = $this->cardObjects[$col];

      if ($cardObj !== null && $cardObj->type() === 'event') {
        $discardedCardIds[] = $cardObj->id();
        $newCards[$col] = null;
      }
    }

    // Move cards to left
    $row0 = array_filter(
      array_slice($newCards, 0, 6),
      function ($cardId) {
        return $cardId !== null;
      }
    );

    $row1 = array_filter(
      array_slice($newCards, 6, 6),
      function ($cardId) {
        return $cardId !== null;
      }
    );

    $shiftedCards = array_merge(array_pad($row0, 6, null), array_pad($row1, 6, null));

    // Draw new cards
    for ($i = 0; $i < 12; $i++) {
      if ($shiftedCards[$i] === null) {
        $shiftedCards[$i] = CardManager::getNextCard()->id();
      }
    }

    // Return event cards that were discarded
    return $discardedCardIds;
  }

  // Serialize market into an array for display
  public function getUiData()
  {
    return [
      'cards' => $this->cards,
      'coins' => $this->coins,
    ];
  }

  private function isCardAtPosition($i)
  {
    return $this->cards[$i] !== 0;
  }

  private function row($i)
  {
    return $i < 6 ? 0 : 1;
  }

  private function col($i)
  {
    return $i % 6;
  }

  private function indexOnOtherRow($i)
  {
    return $i < 6 ? $i + 6 : $i - 6;
  }

  private function setCardAtPosition($cardId, $i)
  {
    $cards = $this->cards; // php assigns arrays by copy
    $cards[$i] = $cardId;
    $this->update(['cards' => $cards]);
  }

  private function addCoinsToPosition($amount, $i)
  {
    $coins = $this->coins; // php assigns arrays by copy
    $coins[$i] += $amount;
    $this->update(['coins' => $coins]);
  }
}
