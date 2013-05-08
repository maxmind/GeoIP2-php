<?php

namespace GeoIP2\Record;

class Country extends AbstractPlaceRecord
{

  protected $validAttributes = Array('confidence',
                                   'geoname_id',
                                   'iso_code',
                                   'names');
}