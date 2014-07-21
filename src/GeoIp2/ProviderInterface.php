<?php

namespace GeoIp2;

interface ProviderInterface
{
    /**
     * @param ipAddress
     *            IPv4 or IPv6 address to lookup.
     * @return \GeoIp2\Model\Country A Country model for the requested IP address.
     */
    public function country($ipAddress);

    /**
     * @param ipAddress
     *            IPv4 or IPv6 address to lookup.
     * @return \GeoIp2\Model\City A City model for the requested IP address.
     */
    public function city($ipAddress);

    /**
     * @param ipAddress
     *            IPv4 or IPv6 address to lookup.
     * @return \GeoIp2\Model\City A City model for the requested IP address.
     *
     * @deprecated deprecated since version 0.7.0
     */
    public function cityIspOrg($ipAddress);

    /**
     * @param ipAddress
     *            IPv4 or IPv6 address to lookup.
     * @return \GeoIp2\Model\Insights An Insights model for the requested IP address.
     *
     * @deprecated deprecated since version 0.7.0
     */
    public function omni($ipAddress);
}
