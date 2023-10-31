<?php

declare(strict_types=1);

namespace GeoIp2\Record;

/**
 * Contains data for the represented country associated with an IP address.
 *
 * This class contains the country-level data associated with an IP address
 * for the IP's represented country. The represented country is the country
 * represented by something like a military base.
 *
 * @property-read string|null $type A string indicating the type of entity that is
 * representing the country. Currently we only return <code>military</code>
 * but this could expand to include other types in the future.
 */
class RepresentedCountry extends Country
{
    public readonly ?string $type;

    /**
     * @ignore
     */
    public function __construct(array $record, array $locales = ['en'])
    {
        parent::__construct($record, $locales);

        $this->type = $record['type'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();
        $js['type'] = $this->type;

        return $js;
    }
}
