#!/usr/bin/env php

<?php

require_once '../vendor/autoload.php';

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

$reader = new Reader('GeoLite2-City.mmdb');
$count = 40000;
$startTime = microtime(true);
for ($i = 0; $i < $count; $i++) {
    $ip = long2ip(rand(0, pow(2, 32) -1));
    try {
        $t = $reader->city($ip);
    } catch (AddressNotFoundException $e) {
    }
    if ($i % 1000 == 0) {
        print($i . ' ' . $ip . "\n");
    }
}
$endTime = microtime(true);

$duration = $endTime - $startTime;
print('Requests per second: ' . $count / $duration . "\n");
