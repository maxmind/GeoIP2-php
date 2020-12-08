<?php

declare(strict_types=1);

if (!$loader = @include __DIR__ . '/../vendor/autoload.php') {
    exit('Project dependencies missing');
}

$loader->add('GeoIp2\Test', __DIR__);
