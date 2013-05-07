<?php

namespace GeoIP2\Model;

class Country
{
  private $continent;
  private $country;
  private $registeredCountry;
  private $representedCountry;
  private $traits;
  private $raw;

  public function __construct($raw, $languages) {
    $this->raw = $raw;

    $this->continent = new \GeoIP2\Record\Continent($this->get('continent'),
                                                    $languages);
    $this->country = new \GeoIP2\Record\Country($this->get('country'),
                                                $languages);
    $this->registeredCountry =
      new \GeoIP2\Record\Country($this->get('registered_country'), $languages);
    $this->representedCountry =
      new \GeoIP2\Record\RepresentedCountry($this->get('represented_country'),
                                            $languages);
    $this->traits = new \GeoIP2\Record\Traits($this->get('traits'));
  }

  protected function get($field) {
    return isset($this->raw[$field]) ? $this->raw[$field] : Array();
  }

  public function __get ($var)
  {
	if ($var != "instance" && isset($this->$var)) return $this->$var;

    throw new RuntimeException("Unknown attribute: $attr");
  }
}
