<?php

namespace GeoIP2\Model;

class Country
{
  // XXX - use __get__
  public $continent;
  public $country;
  public $registeredCountry;
  public $representedCountry;
  public $traits;
  public $raw;

  public function __construct($raw, $languages) {
    $this->raw = $raw;

    $this->continent = new \GeoIP2\Record\Continent($this->get('continent'), $languages);
    $this->country = new \GeoIP2\Record\Country($this->get('country'), $languages);
    $this->registeredCountry = new \GeoIP2\Record\Country($this->get('registered_country'), $languages);
    $this->representedCountry = new \GeoIP2\Record\RepresentedCountry($this->get('represented_country'), $languages);
    $this->traits = new \GeoIP2\Record\Traits($this->get('traits'));
  }

  private function get($field) {
    return isset($this->raw[$field]) ? $this->raw[$field] : Array();
  }
}
