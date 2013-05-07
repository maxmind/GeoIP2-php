<?php

namespace GeoIP2\Model;

class Country
{
  // XXX - use __get__
  public $continent;
  public $country;
  public $registered_country;
  public $represented_country;
  public $traits;
  public $raw;

  public function __construct($raw, $language) {
    $this->country = new \GeoIP2\Record\Country($raw['country']);

    $this->raw = $raw;
  }
}
