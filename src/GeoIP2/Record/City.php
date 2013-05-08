<?php

namespace GeoIP2\Record;

class City extends AbstractPlaceRecord
{
  protected $validAttribute = Array('confidence', 'geonameId', 'names');
}