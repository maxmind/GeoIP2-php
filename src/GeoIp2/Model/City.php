<?php

namespace GeoIp2\Model;

/**
 * This class provides a model for the data returned by the GeoIP2
 * City end point.
 *
 * The only difference between the City, City/ISP/Org, and Omni model
 * classes is which fields in each record may be populated. See
 * http://dev.maxmind.com/geoip/geoip2/web-services more details.
 *
 * @property \GeoIp2\Record\City $city City data for the requested IP
 * address.
 *
 * @property \GeoIp2\Record\Continent $continent Continent data for the
 * requested IP address.
 *
 * @property \GeoIp2\Record\Country $country Country data for the requested
 * IP address. This object represents the country where MaxMind believes the
 * end user is located.
 *
 * @property \GeoIp2\Record\Location $location Location data for the
 * requested IP address.
 *
 * @property \GeoIp2\Record\MaxMind $maxmind Data related to your MaxMind
 * account.
 *
 * @property \GeoIp2\Record\Country $registeredCountry Registered country
 * data for the requested IP address. This record represents the country
 * where the ISP has registered a given IP block and may differ from the
 * user's country.
 *
 * @property \GeoIp2\Record\RepresentedCountry $representedCountry
 * Represented country data for the requested IP address. The represented
 * country is used for things like military bases or embassies. It is only
 * present when the represented country differs from the country.
 *
 * @property array $subdivisions An array of {@link \GeoIp2\Record\Subdivision}
 * objects representing the country subdivisions for the requested IP
 * address. The number and type of subdivisions varies by country, but a
 * subdivision is typically a state, province, county, etc. Subdivisions
 * are ordered from most general (largest) to most specific (smallest).
 * If the response did not contain any subdivisions, this method returns
 * an empty array.
 *
 * @property \GeoIp2\Record\Subdivision $mostSpecificSubdivision An  object
 * representing the most specific subdivision returned. If the response
 * did not contain any subdivisions, this method returns an empty
 * {@link \GeoIp2\Record\Subdivision} object.
 *
 * @property \GeoIp2\Record\Traits $traits Data for the traits of the
 * requested IP address.
 */
class City extends Country
{
    /**
     * @ignore
     */
    protected $city;
    /**
     * @ignore
     */
    protected $location;
    /**
     * @ignore
     */
    protected $postal;
    /**
     * @ignore
     */
    protected $subdivisions = array();

    /**
     * @ignore
     */
    public function __construct($raw, $locales = array('en'))
    {
        parent::__construct($raw, $locales);

        $this->city = new \GeoIp2\Record\City($this->get('city'), $locales);
        $this->location = new \GeoIp2\Record\Location($this->get('location'));
        $this->postal = new \GeoIp2\Record\Postal($this->get('postal'));

        $this->createSubdivisions($raw, $locales);
    }

    private function createSubdivisions($raw, $locales)
    {
        if (!isset($raw['subdivisions'])) {
            return;
        }

        foreach ($raw['subdivisions'] as $sub) {
            array_push(
                $this->subdivisions,
                new \GeoIp2\Record\Subdivision($sub, $locales)
            );
        }
    }

    /**
     * @ignore
     */
    public function __get($attr)
    {
        if ($attr == 'mostSpecificSubdivision') {
            return $this->$attr();
        } else {
            return parent::__get($attr);
        }
    }

    private function mostSpecificSubdivision()
    {
        return empty($this->subdivisions) ?
            new \GeoIp2\Record\Subdivision(array(), $this->locales) :
            end($this->subdivisions);
    }
}
