<?php

namespace GeoIP2\Model;

class City extends Country
{
  //XXX use properties
  public $city;
  public $location;
  public $postal;
  public $subdivisions;

  public function __construct($raw, $language)
  {

    parent::__construct($raw, $language);
  }

}