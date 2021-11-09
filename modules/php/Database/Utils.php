<?php

declare(strict_types=1);

namespace PAX\Database;

class Utils
{
  public static function jsonEncode($value)
  {
    return self::isJson($value) ? $value : json_encode($value);
  }

  public static function isJson($value)
  {
    if (!is_string($value)) {
      return false;
    }

    json_decode($value);
    return json_last_error() === JSON_ERROR_NONE;
  }
}
