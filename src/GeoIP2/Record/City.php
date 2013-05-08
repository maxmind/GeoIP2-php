<?php

namespace GeoIP2\Record;

class City extends AbstractPlaceRecord
{
  protected $validAttributes = Array('confidence', 'geonameId', 'names');
}