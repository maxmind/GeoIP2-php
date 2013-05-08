<?php

if (!$loader = @include __DIR__.'/../vendor/autoload.php') {
    die('Project dependencies missing');
}

$loader->add('GeoIP2\Test', __DIR__);

