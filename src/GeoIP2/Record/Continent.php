<?php

namespace GeoIP2\Record;

class Continent extends AbstractPlaceRecord
{
  protected $validAttributes = Array('continent_code',
                                     'geoname_id',
                                     'names');
}