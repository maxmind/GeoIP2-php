<?php

namespace GeoIP2\Record;

class Subdivision extends AbstractPlaceRecord
{
  protected $validAttributes = Array('confidence',
                                     'geoname_id',
                                     'iso_code',
                                     'names');
}