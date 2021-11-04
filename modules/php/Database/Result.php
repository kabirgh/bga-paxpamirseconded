<?php

declare(strict_types=1);

namespace PAX\Database;

use Exception;
use PAX\Core\Game;

// Wrapper for mysqli_result https://www.php.net/manual/en/class.mysqli-result.php
class Result
{
  private $r;
  private $query;

  public function __construct($result, $queryName)
  {
    $this->r = $result;
    $this->query = $queryName;
  }

  public function load()
  {
    if ($this->r->num_rows !== 1) {
      throw new Exception("Expected 1 row in {$this->query} result but found {$this->r->num_rows}");
    }
    return $this->r->fetch_assoc();
  }

  public function loadAll()
  {
    if ($this->r->num_rows <= 1) {
      throw new Exception("Expected multiple rows in {$this->query} result but found {$this->r->num_rows}");
    }
    return $this->r->fetch_assoc();
  }
}
