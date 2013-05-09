<?php

namespace GeoIP2\Record;

class RepresentedCountry extends Country
{
    protected $validAttributes = array(
        'confidence',
        'geonameId',
        'isoCode',
        'namespace',
        'type'
    );
}
