<?php

declare(strict_types=1);

namespace GeoIp2\Record;

/**
 * Contains data for the location record associated with an IP address.
 *
 * This record is returned by all location services and databases besides
 * Country.
 *
 * @property-read int|null $averageIncome The average income in US dollars
 * associated with the requested IP address. This attribute is only available
 * from the Insights service.
 * @property-read int|null $accuracyRadius The approximate accuracy radius in
 * kilometers around the latitude and longitude for the IP address. This is
 * the radius where we have a 67% confidence that the device using the IP
 * address resides within the circle centered at the latitude and longitude
 * with the provided radius.
 * @property-read float|null $latitude The approximate latitude of the location
 * associated with the IP address. This value is not precise and should not be
 * used to identify a particular address or household.
 * @property-read float|null $longitude The approximate longitude of the location
 * associated with the IP address. This value is not precise and should not be
 * used to identify a particular address or household.
 * @property-read int|null $populationDensity The estimated population per square
 * kilometer associated with the IP address. This attribute is only available
 * from the Insights service.
 * @property-read int|null $metroCode The metro code of the location if the location
 * is in the US. MaxMind returns the same metro codes as the
 * Google AdWords API. See
 * https://developers.google.com/adwords/api/docs/appendix/cities-DMAregions.
 * @property-read string|null $timeZone The time zone associated with location, as
 * specified by the IANA Time Zone Database, e.g., "America/New_York". See
 * https://www.iana.org/time-zones.
 */
class Location implements \JsonSerializable
{
    public readonly ?int $averageIncome;
    public readonly ?int $accuracyRadius;
    public readonly ?float $latitude;
    public readonly ?float $longitude;
    public readonly ?int $metroCode;
    public readonly ?int $populationDensity;
    public readonly ?string $timeZone;

    public function __construct(array $record)
    {
        $this->averageIncome = $record['average_income'] ?? null;
        $this->accuracyRadius = $record['accuracy_radius'] ?? null;
        $this->latitude = $record['latitude'] ?? null;
        $this->longitude = $record['longitude'] ?? null;
        $this->metroCode = $record['metro_code'] ?? null;
        $this->populationDensity = $record['population_density'] ?? null;
        $this->timeZone = $record['time_zone'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = [];
        if ($this->averageIncome !== null) {
            $js['average_income'] = $this->averageIncome;
        }
        if ($this->accuracyRadius !== null) {
            $js['accuracy_radius'] = $this->accuracyRadius;
        }
        if ($this->latitude !== null) {
            $js['latitude'] = $this->latitude;
        }
        if ($this->longitude !== null) {
            $js['longitude'] = $this->longitude;
        }
        if ($this->metroCode !== null) {
            $js['metro_code'] = $this->metroCode;
        }
        if ($this->populationDensity !== null) {
            $js['population_density'] = $this->populationDensity;
        }
        if ($this->timeZone !== null) {
            $js['time_zone'] = $this->timeZone;
        }

        return $js;
    }
}
