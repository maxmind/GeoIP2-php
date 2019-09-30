<?php

namespace GeoIp2;

class Util
{
    /**
     * This returns the network in CIDR notation for the given IP and prefix
     * length. This is for internal use only.
     *
     * @internal
     * @ignore
     *
     * @param mixed $ipAddress
     * @param mixed $prefixLen
     */
    public static function cidr($ipAddress, $prefixLen)
    {
        $ipBytes = array_merge(unpack('C*', inet_pton($ipAddress)));
        $networkBytes = array_fill(0, \count($ipBytes), 0);

        $curPrefix = $prefixLen;
        for ($i = 0; $i < \count($ipBytes) && $curPrefix > 0; $i++) {
            $b = $ipBytes[$i];
            if ($curPrefix < 8) {
                $shiftN = 8 - $curPrefix;
                $b = (0xFF & ($b >> $shiftN) << $shiftN);
            }
            $networkBytes[$i] = $b;
            $curPrefix -= 8;
        }

        $network = inet_ntop(pack('C*', ...$networkBytes));

        return "$network/$prefixLen";
    }
}
