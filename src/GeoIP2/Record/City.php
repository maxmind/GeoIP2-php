<?php

namespace GeoIP2\Record;

class City extends AbstractPlaceRecord
{
    protected $validAttributes = array('confidence', 'geonameId', 'names');
}
