<?php

namespace GeoIP2\Record;

class RepresentedCountry extends Country
{
  protected $validAttributes = Array('confidence',
                                   'geoname_id',
                                   'iso_code',
                                   'names',
                                   'type');
}