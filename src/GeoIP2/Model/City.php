<?php

namespace GeoIP2\Model;

class City extends Country
{
  protected $city;
  protected $location;
  protected $postal;
  protected $subdivisions = Array();

  public function __construct($raw, $languages)
  {
    parent::__construct($raw, $languages);

    $this->city = new \GeoIP2\Record\City($this->get('city'), $languages);
    $this->location = new \GeoIP2\Record\Location($this->get('location'));
    $this->postal = new \GeoIP2\Record\Postal($this->get('postal'));

    $this->createSubdivisions($raw, $languages);
  }

  private function createSubdivisions($raw, $languages) {
    if(!isset($raw['subdivisions'])) return;

    foreach ($raw['subdivisions'] as $sub) {
      array_push($this->subdivisions,
                 new \GeoIP2\Record\Subdivision($sub, $languages));
    }
  }
}