<?php

namespace GeoIP2\Record;

abstract class AbstractRecord
{
  private $record;

  public function __construct($record) {
    $this->record = $record;
  }

  public function __get($attr) {
    $valid = in_array($attr, $this->validAttributes);
    if ($valid && isset($this->record[$attr])){
      return $this->record[$attr];
    } elseif ($valid) {
      return null;
    } else {
      throw new \RuntimeException("Unknown attribute: $attr");
    }
  }
}