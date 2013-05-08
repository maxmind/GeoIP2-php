<?php

namespace GeoIP2\Record;

class Country extends AbstractPlaceRecord
{

  protected $validAttributes = Array('confidence',
                                     'geonameId',
                                     'isoCode',
                                     'names');
}