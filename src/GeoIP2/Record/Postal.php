<?php

namespace GeoIP2\Record;

class Postal extends AbstractRecord
{
  protected $validAttributes = Array('code', 'confidence');
}
