<?php

namespace GeoIP2\Record;

class City extends AbstractPlaceRecord
{
  protected $validAttribute = Array('confidence', 'geoname_id', 'names');
}