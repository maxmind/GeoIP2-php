<?php

declare(strict_types=1);

namespace GeoIp2\Model;

use GeoIp2\Util;

/**
 * This class provides the GeoLite2 ASN model.
 *
 * @property-read int|null $autonomousSystemNumber The autonomous system number
 *     associated with the IP address.
 * @property-read string|null $autonomousSystemOrganization The organization
 *     associated with the registered autonomous system number for the IP
 *     address.
 * @property-read string $ipAddress The IP address that the data in the model is
 *     for.
 * @property-read string $network The network in CIDR notation associated with
 *      the record. In particular, this is the largest network where all of the
 *      fields besides $ipAddress have the same value.
 */
class Asn implements \JsonSerializable
{
    public readonly ?int $autonomousSystemNumber;
    public readonly ?string $autonomousSystemOrganization;
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
        $ipAddress = $raw['ip_address'];
        $this->ipAddress = $ipAddress;
        $this->network = Util::cidr($ipAddress, $raw['prefix_len']);
    }

    public function jsonSerialize(): ?array
    {
        return [
            'autonomous_system_number' => $this->autonomousSystemNumber,
            'autonomous_system_organization' => $this->autonomousSystemOrganization,
            'ip_address' => $this->ipAddress,
            'network' => $this->network,
        ];
    }
}
