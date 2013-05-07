<?php

namespace GeoIP2\Record;

class Country extends AbstractPlaceRecord
{

  private $languages;

  public function __construct($record, $languages){
    $this->languages = $languages;
    parent::__construct($record);
  }

  public function name() {
    foreach($this->languages as $language) {
      if (isset($this->names[$language])) return $this->names[$language];
    }
  }

}