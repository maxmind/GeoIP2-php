<?php

namespace GeoIP2\Record;

class Continent extends AbstractPlaceRecord
{
  protected $validAttributes = Array('continentCode',
                                     'geonameId',
                                     'names');
}