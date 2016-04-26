<?php

namespace GeoIp2\Record;

/**
 * City-level data associated with an IP address.
 *
 * This record is returned by all location services and databases besides
 * Country.
 *
 * @property int|null $confidence A value from 0-100 indicating MaxMind's
 * confidence that the city is correct. This attribute is only available
 * from the Insights service and the GeoIP2 Enterprise database.
 *
 * @property int|null $geonameId The GeoName ID for the city. This attribute
 * is returned by all location services and databases.
 *
 * @property string|null $name The name of the city based on the locales list
 * passed to the constructor. This attribute is returned by all location
 * services and databases.
 *
 * @property array|null $names A array map where the keys are locale codes
 * and the values are names. This attribute is returned by all location
 * services and databases.
 */
class City extends AbstractPlaceRecord
{
    /**
     * @ignore
     */
    protected $validAttributes = array('confidence', 'geonameId', 'names');
}
