<?php

namespace GeoIP2\Record;

class Location extends AbstractRecord
{
  protected $validAttributes = Array('accuracyRadius',
                                     'latitude',
                                     'longitude',
                                     'metroCode',
                                     'postalCode',
                                     'postalConfidence',
                                     'timeZone');
}