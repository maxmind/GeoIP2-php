<?php

namespace GeoIP2\Record;

class Subdivision extends AbstractPlaceRecord
{
  protected $validAttributes = Array('confidence',
                                     'geonameId',
                                     'isoCode',
                                     'names');
}