<?php

require_once 'phar://geoip2.phar/vendor/autoload.php';

// The following was taken from Guzzle (MIT license)

// Copy the cacert.pem file from the phar if it is not in the temp folder.
$from = 'phar://geoip2.phar/vendor/guzzle/guzzle/src/Guzzle/Http/Resources/cacert.pem';
$certFile = sys_get_temp_dir() . '/guzzle-cacert.pem';

// Only copy when the file size is different
if (!file_exists($certFile) || filesize($certFile) != filesize($from)) {
    if (!copy($from, $certFile)) {
        throw new RuntimeException("Could not copy {$from} to {$certFile}: "
            . var_export(error_get_last(), true));
    }
}
