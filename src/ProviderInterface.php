<?php

declare(strict_types=1);

namespace GeoIp2;

use MaxMind\Db\Reader\InvalidDatabaseException;

interface ProviderInterface
{
    /**
     * @param string $ipAddress an IPv4 or IPv6 address to lookup
     *
     * @throws Exception\GeoIp2Exception if the address is not found or a web service error occurs
     * @throws InvalidDatabaseException  if the database is invalid
     * @throws \BadMethodCallException   if the database does not support the lookup or is closed
     * @throws \InvalidArgumentException if the address is invalid or unsupported by the database
     * @throws \RuntimeException         if the database decoder or HTTP client cannot run
     *
     * @return Model\Country a Country model for the requested IP address
     */
    public function country(string $ipAddress): Model\Country;

    /**
     * @param string $ipAddress an IPv4 or IPv6 address to lookup
     *
     * @throws Exception\GeoIp2Exception if the address is not found or a web service error occurs
     * @throws InvalidDatabaseException  if the database is invalid
     * @throws \BadMethodCallException   if the database does not support the lookup or is closed
     * @throws \InvalidArgumentException if the address is invalid or unsupported by the database
     * @throws \RuntimeException         if the database decoder or HTTP client cannot run
     *
     * @return Model\City a City model for the requested IP address
     */
    public function city(string $ipAddress): Model\City;
}
