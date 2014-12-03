#!/usr/bin/env php
<?php
require_once 'geoip2.phar';
use GeoIp2\Database\Reader;

$reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');

$record = $reader->city('81.2.69.160');

if ( $record->country->isoCode === 'GB' ) {
    exit(0);
}

print('Problem with Phar!');
exit(1);
