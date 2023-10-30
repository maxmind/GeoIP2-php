<?php

declare(strict_types=1);

namespace GeoIp2\Model;

/**
 * Model class for the data returned by GeoIP2 Country web service and database.
 *
 * See https://dev.maxmind.com/geoip/docs/web-services?lang=en for more details.
 *
 * @property-read \GeoIp2\Record\Continent $continent Continent data for the
 * requested IP address.
 * @property-read \GeoIp2\Record\Country $country Country data for the requested
 * IP address. This object represents the country where MaxMind believes the
 * end user is located.
 * @property-read \GeoIp2\Record\MaxMind $maxmind Data related to your MaxMind
 * account.
 * @property-read \GeoIp2\Record\Country $registeredCountry Registered country
 * data for the requested IP address. This record represents the country
 * where the ISP has registered a given IP block and may differ from the
 * user's country.
 * @property-read \GeoIp2\Record\RepresentedCountry $representedCountry
 * Represented country data for the requested IP address. The represented
 * country is used for things like military bases. It is only present when
 * the represented country differs from the country.
 * @property-read \GeoIp2\Record\Traits $traits Data for the traits of the
 * requested IP address.
 * @property-read array $raw The raw data from the web service.
 */
class Country implements \JsonSerializable
{
    public readonly \GeoIp2\Record\Continent $continent;
    public readonly \GeoIp2\Record\Country $country;
    public readonly \GeoIp2\Record\MaxMind $maxmind;
    public readonly \GeoIp2\Record\Country $registeredCountry;
    public readonly \GeoIp2\Record\RepresentedCountry $representedCountry;
    public readonly \GeoIp2\Record\Traits $traits;

    /**
     * @ignore
     */
    public function __construct(array $raw, array $locales = ['en'])
    {
        $this->continent = new \GeoIp2\Record\Continent(
            $raw['continent'] ?? [],
            $locales
        );
        $this->country = new \GeoIp2\Record\Country(
            $raw['country'] ?? [],
            $locales
        );
        $this->maxmind = new \GeoIp2\Record\MaxMind($raw['maxmind'] ?? []);
        $this->registeredCountry = new \GeoIp2\Record\Country(
            $raw['registered_country'] ?? [],
            $locales
        );
        $this->representedCountry = new \GeoIp2\Record\RepresentedCountry(
            $raw['represented_country'] ?? [],
            $locales
        );
        $this->traits = new \GeoIp2\Record\Traits($raw['traits'] ?? []);
    }

    public function jsonSerialize(): ?array
    {
        return [
            'continent' => $this->continent->jsonSerialize(),
            'country' => $this->country->jsonSerialize(),
            'maxmind' => $this->maxmind->jsonSerialize(),
            'registered_country' => $this->registeredCountry->jsonSerialize(),
            'represented_country' => $this->representedCountry->jsonSerialize(),
            'traits' => $this->traits->jsonSerialize(),
        ];
    }
}
