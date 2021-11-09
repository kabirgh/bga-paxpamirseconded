<?php

declare(strict_types=1);

namespace PAX\Model;

use Exception;
use PAX\Core\Game;
use PAX\Model\DbModel;

class Map extends DbModel
{
  protected $id,
    /**
     * [
     *  'Kabul' => [
     *    'playerId1' => 3,
     *    'playerId2' => 0,
     *    ...
     *  ],
     *  'Persia' => ...
     * ]
     */
    $tribes,
    /**
     * [
     *  'Kabul' => [
     *    'Russian' => 0,
     *    'British' => 3,
     *    'Afghan' => 0,
     *  ],
     *  'Persia' => ...
     * ]
     */
    $armies,
    /**
     * [
     *  'Herat-Kandahar' => [
     *    'Russian' => 2,
     *    'British' => 1,
     *    'Afghan' => 0,
     *   ],
     *  'Herat-Persia' => ...
     * ]
     *
     * $from-$to is alphabetized so only one ordering is valid.
     */
    $roads;

  public static function setupNewGame($playerMap, $options)
  {
    $tribes =  ['Herat' => [], 'Kabul' => [], 'Kandahar' => [], 'Persia' => [], 'Punjab' => [], 'Transcaspia' => []];
    foreach (array_keys($tribes) as $region) {
      foreach (array_keys($playerMap) as $playerId) {
        $tribes[$region][$playerId] = 0;
      }
    }

    $armies =  ['Herat' => [], 'Kabul' => [], 'Kandahar' => [], 'Persia' => [], 'Punjab' => [], 'Transcaspia' => []];
    foreach (array_keys($armies) as $region) {
      foreach (['Afghan', 'British', 'Russian'] as $faction) {
        $armies[$region][$faction] = 0;
      }
    }

    $roads = [
      'Herat-Kabul' => [],
      'Herat-Kandahar' => [],
      'Herat-Persia' => [],
      // 'Herat-Punjab' => [], -- not connected
      'Herat-Transcaspia' => [],
      'Kabul-Kandahar' => [],
      // 'Kabul-Persia' => [],
      'Kabul-Punjab' => [],
      'Kabul-Transcaspia' => [],
      // 'Kandahar-Persia' => [],
      'Kandahar-Punjab' => [],
      // 'Kandahar-Transcaspia' => [],
      // 'Persia-Punjab' => [],
      'Persia-Transcaspia' => [],
      // 'Punjab-Transcaspia' => [],
    ];
    foreach (array_keys($roads) as $link) {
      foreach (['Afghan', 'British', 'Russian'] as $faction) {
        $roads[$link][$faction] = 0;
      }
    }

    Map::create([
      'id' => 1,
      'tribes' => $tribes,
      'armies' => $armies,
      'roads' => $roads,
    ]);
  }

  // Singleton object
  public static function get()
  {
    $result = self::query()
      ->select(['id', 'tribes', 'armies', 'roads']) // Would be nice to use * instead
      ->where('id', 1)
      ->get(true);
    return new self($result);
  }

  public function addArmies($region, $faction, $numArmies)
  {
    $newArmies = $this->armies;
    $newArmies[$region][$faction] += $numArmies;
    $this->update(['armies' => $newArmies]);
  }

  public function addTribes($region, $playerId, $numTribes)
  {
    $newTribes = $this->tribes;
    $newTribes[$region][$playerId] += $numTribes;
    $this->update(['tribes' => $newTribes]);
  }

  public function addRoads($region1, $region2, $faction, $numRoads)
  {
    $link = $region1 < $region2 ? "{$region1}-{$region2}" : "{$region2}-{$region1}";

    $newRoads = $this->roads;
    $newRoads[$link][$faction] += $numRoads;
    $this->update(['roads' => $newRoads]);
  }

  // Boilerplate

  protected static function create($params)
  {
    $instance = new self($params);
    parent::create($instance);
    return $instance;
  }

  private function __construct($params)
  {
    $this->id = $params['id'];
    $this->tribes = $params['tribes'];
    $this->roads = $params['roads'];
    $this->armies = $params['armies'];
  }

  protected static function tableName()
  {
    return 'map';
  }

  protected static function primaryKey()
  {
    return 'id';
  }
}
