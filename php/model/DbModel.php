<?php

// TODO
// require_once '../core/Game.php';

// Active Record-stle ORM.
// Subclasses may only define instance variables that will be persisted to the
// database.
abstract class DbModel
{
  abstract protected function tableName();

  // Override if the table uses a different column for its primary key.
  protected function primaryKey()
  {
    return 'id';
  }

  // TODO is DbQuery vulnerable to sql injection?
  static public function create($instance)
  {
    $props = get_object_vars($instance);
    $propKeys = implode(', ', array_keys($props));
    $sql = <<<SQL
      INSERT INTO {$instance->tableName()} ($propKeys)
      VALUES ({$instance->sqlFormattedValues($props)})
    SQL;
    // print $sql . "\n";
    Game::get()->DbQuery($sql);
  }

  // If the properties specified in $fieldMap exists in the subclass, set the
  // subclass property to $fieldMap[$prop]. Immediately commits to the database.
  public function update($fieldMap)
  {
    // Check primary key not set
    if (isset($fieldMap[$this->primaryKey()])) {
      throw new Exception("Cannot update "  . get_class($this) . " {$this->primaryKey()} to {$fieldMap[$prop]}: {$this->primaryKey()} is the primary key");
    }

    // Check only fields that exist are set
    $props = get_object_vars($this);

    foreach ($fieldMap as $field => $unused_value) {
      if (!isset($props[$field])) {
        throw new Exception("Cannot update "  . get_class($this) . " {$this->primaryKey()} to {$fieldMap[$prop]}: {$field} is not a valid property");
      }
    }

    // Finally update object
    foreach ($fieldMap as $prop => $value) {
      $this->$prop = $value;
    }

    // Commit to database
    $this->commit($fieldMap);
  }

  // Persist specified object properties to the database.
  //
  // This function only works if called from paxpamirseconded.game.php, where
  // self::DbQuery can be accessed.
  public function commit($fieldMap)
  {
    $primaryKeyName = $this->primaryKey();
    $primaryKeyValue = $this->$primaryKeyName;

    $sql = <<<SQL
      UPDATE {$this->tableName()}
      SET {$this->sqlFormattedKeyEqualValue($fieldMap)}
      WHERE {$primaryKeyName} = {$primaryKeyValue}
    SQL;
    // print $sql;
    Game::get()->DbQuery($sql);
  }

  // Returns comma-separated string of values.
  private function sqlFormattedValues($fieldMap)
  {
    $arr = [];
    foreach ($fieldMap as $_prop => $value) {
      // Wraps string in single quotes
      $formattedValue = is_string($value) ? "'{$value}'" : "$value";
      $arr[] = $formattedValue;
    }
    return implode(', ', $arr);
  }

  // Returns a comma-separated string of key = value.
  private function sqlFormattedKeyEqualValue($fieldMap)
  {
    $arr = [];
    foreach ($fieldMap as $prop => $value) {
      $formattedValue = is_string($value) ? "'{$value}'" : "$value";
      $arr[] = "{$prop} = {$formattedValue}";
    }
    return implode(', ', $arr);
  }

  protected function toJson()
  {
    return json_encode(get_object_vars($this));
  }

  public function __toString()
  {
    return $this->toJson();
  }
}
