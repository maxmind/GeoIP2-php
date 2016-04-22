<?php

namespace GeoIp2\Record;

/**
 * Contains data for the country record associated with an IP address
 *
 * This record is returned by all location services and databases.
 *
 * @property int|null $confidence A value from 0-100 indicating MaxMind's
 * confidence that the country is correct. This attribute is only available
 * from the Insights service and the GeoIP2 Enterprise database.
 *
 * @property int|null $geonameId The GeoName ID for the country. This
 * attribute is returned by location services and databases.
 *
 * @property string|null $isoCode The {@link
 * http://en.wikipedia.org/wiki/ISO_3166-1 two-character ISO 3166-1 alpha
 * code} for the country. This attribute is returned by all location services
 * and databases.
 *
 * @property string|null $name The name of the country based on the locales
 * list passed to the constructor. This attribute is returned by all location
 * services and databases.
 *
 * @property array|null $names An array map where the keys are locale codes
 * and the values are names. This attribute is returned by all location
 * services and databases.
 */
class Country extends AbstractPlaceRecord
{
    /**
     * @ignore
     */
    protected $validAttributes = array(
        'confidence',
        'geonameId',
        'isoCode',
        'names'
    );
}
