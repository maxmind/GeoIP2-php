<?php

namespace GeoIP2\Record;

class Continent extends AbstractPlaceRecord
{
    protected $validAttributes = array(
        'continentCode',
        'geonameId',
        'names'
    );
}
