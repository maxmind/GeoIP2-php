<?php

namespace GeoIp2\Test\Model;

use GeoIp2\Model\Insights;

class InsightsTest extends \PHPUnit_Framework_TestCase
{

    public function testFull()
    {

        $raw = array(
            'city' => array(
                'confidence' => 76,
                'geoname_id' => 9876,
                'names' => array('en' => 'Minneapolis'),
            ),
            'continent' => array(
                'code' => 'NA',
                'geoname_id' => 42,
                'names' => array('en' => 'North America'),
            ),
            'country' => array(
                'confidence' => 99,
                'geoname_id' => 1,
                'iso_code' => 'US',
                'names' => array('en' => 'United States of America'),
            ),
            'location' => array(
                'average_income' => 24626,
                'accuracy_radius' => 1500,
                'latitude' => 44.98,
                'longitude' => 93.2636,
                'metro_code' => 765,
                'population_density' => 1341,
                'postal_code' => '55401',
                'postal_confidence' => 33,
                'time_zone' => 'America/Chicago',
            ),
            'maxmind' => array(
                'queries_remaining' => 22,
            ),
            'registered_country' => array(
                'geoname_id' => 2,
                'iso_code' => 'CA',
                'names' => array('en' => 'Canada'),
            ),
            'represented_country' => array(
                'geoname_id' => 3,
                'iso_code' => 'GB',
                'names' => array('en' => 'United Kingdom'),
            ),
            'subdivisions' => array(
                array(
                    'confidence' => 88,
                    'geoname_id' => 574635,
                    'iso_code' => 'MN',
                    'names' => array('en' => 'Minnesota'),
                )
            ),
            'traits' => array(
                'autonomous_system_number' => 1234,
                'autonomous_system_organization' => 'AS Organization',
                'domain' => 'example.com',
                'ip_address' => '1.2.3.4',
                'is_satellite_provider' => true,
                'isp' => 'Comcast',
                'organization' => 'Blorg',
                'user_type' => 'college',
            ),
        );

        $model = new Insights($raw, array('en'));

        $this->assertInstanceOf(
            'GeoIp2\Model\Insights',
            $model,
            'GeoIp2\Model\Insights object'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\City',
            $model->city,
            '$model->city'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Continent',
            $model->continent,
            '$model->continent'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Country',
            $model->country,
            '$model->country'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Location',
            $model->location,
            '$model->location'
        );

        $this->assertSame(
            24626,
            $model->location->averageIncome,
            '$model->location->averageIncome is 24626'
        );

        $this->assertSame(
            1341,
            $model->location->populationDensity,
            '$model->location->populationDensity is 1341'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Country',
            $model->registeredCountry,
            '$model->registeredCountry'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\RepresentedCountry',
            $model->representedCountry,
            '$model->representedCountry'
        );

        $subdivisions = $model->subdivisions;
        foreach ($subdivisions as $subdiv) {
            $this->assertInstanceOf('GeoIp2\Record\Subdivision', $subdiv);
        }

        $this->assertInstanceOf(
            'GeoIp2\Record\Subdivision',
            $model->mostSpecificSubdivision,
            '$model->mostSpecificSubdivision'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Traits',
            $model->traits,
            '$model->traits'
        );

        $this->assertSame(
            true,
            $model->traits->isSatelliteProvider,
            '$model->traits->isSatelliteProvider is true'
        );

        $this->assertSame(
            false,
            $model->traits->isAnonymousProxy,
            '$model->traits->isAnonymousProxy is false'
        );

        $this->assertEquals(
            22,
            $model->maxmind->queriesRemaining,
            'queriesRemaining is correct'
        );

        $this->assertEquals(
            $raw,
            $model->raw,
            'raw method returns raw input'
        );
    }

    public function testEmptyObjects()
    {
        $raw = array('traits' => array('ip_address' => '5.6.7.8'));

        $model = new Insights($raw, array('en'));

        $this->assertInstanceOf(
            'GeoIp2\Model\Insights',
            $model,
            'GeoIp2\Model\Insights object with no data except traits.ipAddress'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\City',
            $model->city,
            '$model->city'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Continent',
            $model->continent,
            '$model->continent'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Country',
            $model->country,
            '$model->country'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Location',
            $model->location,
            '$model->location'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Country',
            $model->registeredCountry,
            '$model->registeredCountry'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\RepresentedCountry',
            $model->representedCountry,
            '$model->representedCountry'
        );

        $this->assertCount(
            0,
            $model->subdivisions,
            '$model->subdivisions returns an empty list'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Subdivision',
            $model->mostSpecificSubdivision,
            '$model->mostSpecificSubdivision'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Traits',
            $model->traits,
            '$model->traits'
        );

        $this->assertEquals(
            $raw,
            $model->raw,
            'raw method returns raw input with no added empty values'
        );
    }


    public function testUnknown()
    {
        $raw = array(
            'new_top_level' => array('foo' => 42),
            'city' => array(
                'confidence' => 76,
                'geoname_id_id' => 9876,
                'names' => array('en' => 'Minneapolis'),
                'population' => 50,
            ),
            'traits' => array('ip_address' => '5.6.7.8')
        );

        // checking whether there are exceptions with unknown keys
        $model = new Insights($raw, array('en'));

        $this->assertInstanceOf(
            'GeoIp2\Model\Insights',
            $model,
            'no exception when Insights model gets raw data with unknown keys'
        );

        $this->assertEquals(
            $raw,
            $model->raw,
            'raw method returns raw input'
        );
    }
}
