<?php

declare(strict_types=1);

namespace PAX\Model;

use Exception;
use PAX\Database\QueryBuilder;
use PAX\Database\Utils;

// Active Record-style ORM.
// Subclasses may only define instance variables that will be persisted to the
// database. Automatically serializes arrays into json when writing, and vice
// versa when reading.
abstract class DbModel
{
  abstract protected static function tableName();
  abstract protected static function primaryKey();

  protected static function create($instance)
  {
    // Automatically serialize arrays into json
    self::query()->insert(Utils::maybeJsonEncodeMap(get_object_vars($instance)));
  }

  protected static function query()
  {
    // static:: refers to subclass if it exists, self:: refers to this class
    return new QueryBuilder(
      static::tableName(),
      static::primaryKey(),
      false,
      function ($row) {
        return Utils::maybeJsonDecodeMap($row);
      },
      function ($map) {
        return Utils::maybeJsonEncodeMap($map);
      }
    );
  }

  // Getter
  public function getProp($prop)
  {
    return get_object_vars($this)[$prop];
  }

  public function id()
  {
    $primaryKeyName = static::primaryKey();
    return $this->$primaryKeyName;
  }

  // If the properties specified in $fieldMap exists in the subclass, set the
  // subclass property to $fieldMap[$prop]. Immediately commits to the database.
  public function update($fieldMap)
  {
    $primaryKeyName = $this::primaryKey();
    // Check primary key not set
    if (isset($fieldMap[$primaryKeyName])) {
      throw new Exception("Cannot update "  . get_class($this) . " {$primaryKeyName} to {$fieldMap[$primaryKeyName]}: {$primaryKeyName} is the primary key");
    }

    // Check only fields that exist are set
    $props = get_object_vars($this);

    foreach ($fieldMap as $field => $unused_value) {
      if (!isset($props[$field])) {
        throw new Exception("Cannot update "  . get_class($this) . " {$primaryKeyName} to {$fieldMap[$field]}: {$field} is not a valid property");
      }
    }

    // Finally update object
    foreach ($fieldMap as $prop => $value) {
      $this->$prop = $value;
    }

    // Commit to database
    self::query()->update($fieldMap, $props[$primaryKeyName]);
  }
}
