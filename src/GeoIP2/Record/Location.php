<?php

namespace GeoIP2\Record;

class Location extends AbstractRecord
{
  protected $validAttributes = Array('accuracy_radius',
                                     'latitude',
                                     'longitude',
                                     'metro_code',
                                     'postal_code',
                                     'postal_confidence',
                                     'time_zone');
}