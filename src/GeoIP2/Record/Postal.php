<?php

namespace GeoIP2\Record;

class Postal extends AbstractRecord
{
    protected $validAttributes = array('code', 'confidence');
}
