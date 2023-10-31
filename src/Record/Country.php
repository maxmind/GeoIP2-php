<?php

declare(strict_types=1);

namespace GeoIp2\Record;

/**
 * Contains data for the country record associated with an IP address.
 *
 * This record is returned by all location services and databases.
 *
 * @property-read int|null $confidence A value from 0-100 indicating MaxMind's
 * confidence that the country is correct. This attribute is only available
 * from the Insights service and the GeoIP2 Enterprise database.
 * @property-read int|null $geonameId The GeoName ID for the country. This
 * attribute is returned by all location services and databases.
 * @property-read bool $isInEuropeanUnion This is true if the country is a
 * member state of the European Union. This attribute is returned by all
 * location services and databases.
 * @property-read string|null $isoCode The two-character ISO 3166-1 alpha code
 * for the country. See https://en.wikipedia.org/wiki/ISO_3166-1. This
 * attribute is returned by all location services and databases.
 * @property-read string|null $name The name of the country based on the locales
 * list passed to the constructor. This attribute is returned by all location
 * services and databases.
 * @property-read array|null $names An array map where the keys are locale codes
 * and the values are names. This attribute is returned by all location
 * services and databases.
 */
class Country extends AbstractPlaceRecord
{
    public readonly bool $isInEuropeanUnion;
    public readonly ?string $isoCode;

    /**
     * @ignore
     */
    public function __construct(array $record, array $locales = ['en'])
    {
        parent::__construct($record, $locales);

        $this->isInEuropeanUnion = $record['is_in_european_union'] ?? false;
        $this->isoCode = $record['iso_code'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();
        $js['is_in_european_union'] = $this->isInEuropeanUnion;
        $js['iso_code'] = $this->isoCode;

        return $js;
    }
}
