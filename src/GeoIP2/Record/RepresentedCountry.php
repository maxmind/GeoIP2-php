<?php

namespace GeoIP2\Record;

class RepresentedCountry extends Country
{
  protected $validAttributes = Array('confidence',
                                   'geonameId',
                                   'isoCode',
                                   'names',
                                   'type');
}