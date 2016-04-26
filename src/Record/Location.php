<?php

namespace GeoIp2\Record;

/**
 * Contains data for the location record associated with an IP address
 *
 * This record is returned by all location services and databases besides
 * Country.
 *
 * @property int|null $averageIncome The average income in US dollars
 * associated with the requested IP address. This attribute is only available
 * from the Insights service.
 *
 * @property int|null $accuracyRadius The radius in kilometers around the
 * specified location where the IP address is likely to be.
 *
 * @property float|null $latitude The approximate latitude of the location
 * associated with the IP address. This value is not precise and should not be
 * used to identify a particular address or household.
 *
 * @property float|null $longitude The approximate longitude of the location
 * associated with the IP address. This value is not precise and should not be
 * used to identify a particular address or household.
 *
 * @property int|null $populationDensity The estimated population per square
 * kilometer associated with the IP address. This attribute is only available
 * from the Insights service.
 *
 * @property int|null $metroCode The metro code of the location if the location
 * is in the US. MaxMind returns the same metro codes as the
 * {@link
 * https://developers.google.com/adwords/api/docs/appendix/cities-DMAregions
 * Google AdWords API}.
 *
 * @property string|null $timeZone The time zone associated with location, as
 * specified by the {@link http://www.iana.org/time-zones IANA Time Zone
 * Database}, e.g., "America/New_York".
 */
class Location extends AbstractRecord
{
    /**
     * @ignore
     */
    protected $validAttributes = array(
        'averageIncome',
        'accuracyRadius',
        'latitude',
        'longitude',
        'metroCode',
        'populationDensity',
        'postalCode',
        'postalConfidence',
        'timeZone'
    );
}
