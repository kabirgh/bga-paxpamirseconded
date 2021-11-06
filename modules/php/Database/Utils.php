<?php

declare(strict_types=1);

namespace PAX\Database;

class Utils
{
  public static function maybeJsonEncode($value)
  {
    return is_array($value) ? json_encode($value) : $value;
  }

  public static function maybeJsonDecode($value)
  {
    if (!is_string($value)) {
      return false;
    }

    $decoded = json_decode($value);
    return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
  }

  public static function maybeJsonEncodeMap($map)
  {
    $encoded = [];
    foreach ($map as $key => $value) {
      $encoded[$key] = Utils::maybeJsonEncode($value);
    }
    return $encoded;
  }

  public static function maybeJsonDecodeMap($map)
  {
    $decoded = [];
    foreach ($map as $key => $value) {
      $decoded[$key] = Utils::maybeJsonDecode($value);
    }
    return $decoded;
  }
}
