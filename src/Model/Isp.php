<?php

declare(strict_types=1);

namespace GeoIp2\Model;

use GeoIp2\Util;

/**
 * This class provides the GeoIP2 ISP model.
 *
 * @property-read int|null $autonomousSystemNumber The autonomous system number
 *     associated with the IP address.
 * @property-read string|null $autonomousSystemOrganization The organization
 *     associated with the registered autonomous system number for the IP
 *     address.
 * @property-read string|null $isp The name of the ISP associated with the IP
 *     address.
 * @property-read string|null $mobileCountryCode The [mobile country code
 *     (MCC)](https://en.wikipedia.org/wiki/Mobile_country_code) associated with
 *     the IP address and ISP.
 * @property-read string|null $mobileNetworkCode The [mobile network code
 *     (MNC)](https://en.wikipedia.org/wiki/Mobile_country_code) associated with
 *     the IP address and ISP.
 * @property-read string|null $organization The name of the organization associated
 *     with the IP address.
 * @property-read string $ipAddress The IP address that the data in the model is
 *     for.
 * @property-read string $network The network in CIDR notation associated with
 *      the record. In particular, this is the largest network where all of the
 *      fields besides $ipAddress have the same value.
 */
class Isp implements \JsonSerializable
{
    public readonly ?int $autonomousSystemNumber;
    public readonly ?string $autonomousSystemOrganization;
    public readonly ?string $isp;
    public readonly ?string $mobileCountryCode;
    public readonly ?string $mobileNetworkCode;
    public readonly ?string $organization;
    public readonly string $ipAddress;
    public readonly string $network;

    /**
     * @ignore
     */
    public function __construct(array $raw)
    {
        $this->autonomousSystemNumber = $raw['autonomous_system_number'] ?? null;
        $this->autonomousSystemOrganization =
            $raw['autonomous_system_organization'] ?? null;
        $this->isp = $raw['isp'] ?? null;
        $this->mobileCountryCode = $raw['mobile_country_code'] ?? null;
        $this->mobileNetworkCode = $raw['mobile_network_code'] ?? null;
        $this->organization = $raw['organization'] ?? null;

        $ipAddress = $raw['ip_address'];
        $this->ipAddress = $ipAddress;
        $this->network = Util::cidr($ipAddress, $raw['prefix_len']);
    }

    public function jsonSerialize(): ?array
    {
        $js = [];
        if ($this->autonomousSystemNumber !== null) {
            $js['autonomous_system_number'] = $this->autonomousSystemNumber;
        }
        if ($this->autonomousSystemOrganization !== null) {
            $js['autonomous_system_organization'] = $this->autonomousSystemOrganization;
        }
        if ($this->isp !== null) {
            $js['isp'] = $this->isp;
        }
        if ($this->mobileCountryCode !== null) {
            $js['mobile_country_code'] = $this->mobileCountryCode;
        }
        if ($this->mobileNetworkCode !== null) {
            $js['mobile_network_code'] = $this->mobileNetworkCode;
        }
        if ($this->organization !== null) {
            $js['organization'] = $this->organization;
        }
        $js['ip_address'] = $this->ipAddress;
        $js['network'] = $this->network;

        return $js;
    }
}
