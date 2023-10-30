<?php

declare(strict_types=1);

namespace GeoIp2\Record;

/**
 * Contains data for the continent record associated with an IP address.
 *
 * This record is returned by all location services and databases.
 *
 * @property-read string|null $code A two character continent code like "NA" (North
 * America) or "OC" (Oceania). This attribute is returned by all location
 * services and databases.
 * @property-read int|null $geonameId The GeoName ID for the continent. This
 * attribute is returned by all location services and databases.
 * @property-read string|null $name Returns the name of the continent based on the
 * locales list passed to the constructor. This attribute is returned by all location
 * services and databases.
 * @property-read array|null $names An array map where the keys are locale codes
 * and the values are names. This attribute is returned by all location
 * services and databases.
 */
class Continent extends AbstractNamedRecord
{
    public readonly ?string $code;
    public readonly ?int $geonameId;

    /**
     * @ignore
     */
    public function __construct(array $record, array $locales = ['en'])
    {
        parent::__construct($record, $locales);

        $this->code = $record['code'] ?? null;
        $this->geonameId = $record['geoname_id'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();
        if ($this->code !== null) {
            $js['code'] = $this->code;
        }
        if ($this->geonameId !== null) {
            $js['geoname_id'] = $this->geonameId;
        }

        return $js;
    }
}
