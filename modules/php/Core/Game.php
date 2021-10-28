<?php

namespace PAX\Core;

use PaxPamirSecondEd; // defined in paxpamirseconded.game.php

/*
 * Game: a wrapper over table object to allow more generic modules
 */

class Game
{
  public static function get()
  {
    return PaxPamirSecondEd::get();
  }
}
