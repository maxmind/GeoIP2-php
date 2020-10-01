<?php

declare(strict_types=1);

if (!$loader = @include __DIR__ . '/../vendor/autoload.php') {
    die('Project dependencies missing');
}

$loader->add('GeoIp2\Test', __DIR__);
