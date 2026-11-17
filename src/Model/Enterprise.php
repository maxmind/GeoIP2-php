<?php

declare(strict_types=1);

namespace GeoIp2\Model;

/**
 * Model class for the data returned by GeoIP Enterprise database lookups.
 *
 * See https://dev.maxmind.com/geoip/docs/web-services/?lang=en for more
 * details.
 *
 * @final
 */
// phpcs:disable
class Enterprise extends City {}
