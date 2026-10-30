<?php

namespace GeoIp2\Database;

interface ReaderInterface
{
    /**
     * This method returns a GeoIP2 City model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\City
     */
    public function city($ipAddress);

    /**
     * This method returns a GeoIP2 Country model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\Country
     */
    public function country($ipAddress);

    /**
     * This method returns a GeoIP2 Anonymous IP model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\AnonymousIp
     */
    public function anonymousIp($ipAddress);

    /**
     * This method returns a GeoLite2 ASN model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\Asn
     */
    public function asn($ipAddress);

    /**
     * This method returns a GeoIP2 Connection Type model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\ConnectionType
     */
    public function connectionType($ipAddress);

    /**
     * This method returns a GeoIP2 Domain model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\Domain
     */
    public function domain($ipAddress);

    /**
     * This method returns a GeoIP2 Enterprise model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\Enterprise
     */
    public function enterprise($ipAddress);

    /**
     * This method returns a GeoIP2 ISP model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @return \GeoIp2\Model\Isp
     */
    public function isp($ipAddress);

    /**
     * @return \MaxMind\Db\Reader\Metadata object for the database
     */
    public function metadata();

    /**
     * Closes the GeoIP2 database and returns the resources to the system.
     */
    public function close();
}
