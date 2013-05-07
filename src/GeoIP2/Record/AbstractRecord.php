<?php

namespace GeoIP2\Record;

abstract class AbstractRecord
{
  private $record;

  public function __construct($record) {
    $this->record = $record;
  }

  public function __get($attr) {
    if (isset($this->record[$attr])) return $this->record[$attr];

    throw new RuntimeException("Unknown attribute: $attr");
  }
}