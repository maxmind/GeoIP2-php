<?php

declare(strict_types=1);

namespace GeoIp2\Model;

use GeoIp2\Util;

/**
 * This class provides the GeoIP2 Anonymous IP model.
 *
 * @property-read bool $isAnonymous This is true if the IP address belongs to
 *     any sort of anonymous network.
 * @property-read bool $isAnonymousVpn This is true if the IP address is
 *     registered to an anonymous VPN provider. If a VPN provider does not
 *     register subnets under names associated with them, we will likely only
 *     flag their IP ranges using the isHostingProvider property.
 * @property-read bool $isHostingProvider This is true if the IP address belongs
 *     to a hosting or VPN provider (see description of isAnonymousVpn property).
 * @property-read bool $isPublicProxy This is true if the IP address belongs to
 *     a public proxy.
 * @property-read bool $isResidentialProxy This is true if the IP address is
 *     on a suspected anonymizing network and belongs to a residential ISP.
 * @property-read bool $isTorExitNode This is true if the IP address is a Tor
 *     exit node.
 * @property-read string $ipAddress The IP address that the data in the model is
 *     for.
 * @property-read string $network The network in CIDR notation associated with
 *      the record. In particular, this is the largest network where all of the
 *      fields besides $ipAddress have the same value.
 */
class AnonymousIp implements \JsonSerializable
{
    public readonly bool $isAnonymous;
    public readonly bool $isAnonymousVpn;
    public readonly bool $isHostingProvider;
    public readonly bool $isPublicProxy;
    public readonly bool $isResidentialProxy;
    public readonly bool $isTorExitNode;
    public readonly string $ipAddress;
    public readonly string $network;

    /**
     * @ignore
     */
    public function __construct(array $raw)
    {
        $this->isAnonymous = $raw['is_anonymous'] ?? false;
        $this->isAnonymousVpn = $raw['is_anonymous_vpn'] ?? false;
        $this->isHostingProvider = $raw['is_hosting_provider'] ?? false;
        $this->isPublicProxy = $raw['is_public_proxy'] ?? false;
        $this->isResidentialProxy = $raw['is_residential_proxy'] ?? false;
        $this->isTorExitNode = $raw['is_tor_exit_node'] ?? false;
        $ipAddress = $raw['ip_address'];
        $this->ipAddress = $ipAddress;
        $this->network = Util::cidr($ipAddress, $raw['prefix_len']);
    }

    public function jsonSerialize(): ?array
    {
        $js = [];
        if ($this->isAnonymous !== null) {
            $js['is_anonymous'] = $this->isAnonymous;
        }
        if ($this->isAnonymousVpn !== null) {
            $js['is_anonymous_vpn'] = $this->isAnonymousVpn;
        }
        if ($this->isHostingProvider !== null) {
            $js['is_hosting_provider'] = $this->isHostingProvider;
        }
        if ($this->isPublicProxy !== null) {
            $js['is_public_proxy'] = $this->isPublicProxy;
        }
        if ($this->isResidentialProxy !== null) {
            $js['is_residential_proxy'] = $this->isResidentialProxy;
        }
        if ($this->isTorExitNode !== null) {
            $js['is_tor_exit_node'] = $this->isTorExitNode;
        }
        $js['ip_address'] = $this->ipAddress;
        $js['network'] = $this->network;

        return $js;
    }
}
