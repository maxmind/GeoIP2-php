<?php

declare(strict_types=1);

namespace GeoIp2\Model;

use GeoIp2\Util;

/**
 * This class provides the GeoIP2 Connection-Type model.
 *
 * @property-read string|null $connectionType The connection type may take the
 *     following values: "Dialup", "Cable/DSL", "Corporate", "Cellular", and
 *     "Satellite". Additional values may be added in the future.
 * @property-read string $ipAddress The IP address that the data in the model is
 *     for.
 * @property-read string $network The network in CIDR notation associated with
 *      the record. In particular, this is the largest network where all of the
 *      fields besides $ipAddress have the same value.
 */
class ConnectionType implements \JsonSerializable
{
    public readonly ?string $connectionType;
    public readonly string $ipAddress;
    public readonly string $network;

    /**
     * @ignore
     */
    public function __construct(array $raw)
    {
        $this->connectionType = $raw['connection_type'] ?? null;
        $ipAddress = $raw['ip_address'];
        $this->ipAddress = $ipAddress;
        $this->network = Util::cidr($ipAddress, $raw['prefix_len']);
    }

    public function jsonSerialize(): ?array
    {
        return [
            'connection_type' => $this->connectionType,
            'ip_address' => $this->ipAddress,
            'network' => $this->network,
        ];
    }
}
