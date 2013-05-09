<?php

namespace GeoIP2\Record;

class Subdivision extends AbstractPlaceRecord
{
    protected $validAttributes = array(
        'confidence',
        'geonameId',
        'isoCode',
        'names'
    );
}
