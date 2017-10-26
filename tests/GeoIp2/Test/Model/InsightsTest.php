<?php

namespace GeoIp2\Test\Model;

use GeoIp2\Model\Insights;

/**
 * @coversNothing
 */
class InsightsTest extends \PHPUnit_Framework_TestCase
{
    public function testFull()
    {
        $raw = [
            'city' => [
                'confidence' => 76,
                'geoname_id' => 9876,
                'names' => ['en' => 'Minneapolis'],
            ],
            'continent' => [
                'code' => 'NA',
                'geoname_id' => 42,
                'names' => ['en' => 'North America'],
            ],
            'country' => [
                'confidence' => 99,
                'geoname_id' => 1,
                'iso_code' => 'US',
                'names' => ['en' => 'United States of America'],
            ],
            'location' => [
                'average_income' => 24626,
                'accuracy_radius' => 1500,
                'latitude' => 44.98,
                'longitude' => 93.2636,
                'metro_code' => 765,
                'population_density' => 1341,
                'postal_code' => '55401',
                'postal_confidence' => 33,
                'time_zone' => 'America/Chicago',
            ],
            'maxmind' => [
                'queries_remaining' => 22,
            ],
            'registered_country' => [
                'geoname_id' => 2,
                'iso_code' => 'CA',
                'names' => ['en' => 'Canada'],
            ],
            'represented_country' => [
                'geoname_id' => 3,
                'iso_code' => 'GB',
                'names' => ['en' => 'United Kingdom'],
            ],
            'subdivisions' => [
                [
                    'confidence' => 88,
                    'geoname_id' => 574635,
                    'iso_code' => 'MN',
                    'names' => ['en' => 'Minnesota'],
                ],
            ],
            'traits' => [
                'autonomous_system_number' => 1234,
                'autonomous_system_organization' => 'AS Organization',
                'domain' => 'example.com',
                'ip_address' => '1.2.3.4',
                'is_anonymous' => true,
                'is_anonymous_vpn' => true,
                'is_hosting_provider' => true,
                'is_public_proxy' => true,
                'is_satellite_provider' => true,
                'is_tor_exit_node' => true,
                'isp' => 'Comcast',
                'organization' => 'Blorg',
                'user_type' => 'college',
            ],
        ];

        $model = new Insights($raw, ['en']);

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

        $this->assertTrue(
            $model->traits->isAnonymous,
            '$model->traits->isAnonymous is true'
        );

        $this->assertTrue(
            $model->traits->isHostingProvider,
            '$model->traits->isHostingProvider is true'
        );

        $this->assertTrue(
            $model->traits->isPublicProxy,
            '$model->traits->isPublicProxy is true'
        );

        $this->assertTrue(
            $model->traits->isSatelliteProvider,
            '$model->traits->isSatelliteProvider is true'
        );

        $this->assertTrue(
            $model->traits->isTorExitNode,
            '$model->traits->isTorExitNode is true'
        );

        $this->assertFalse(
            $model->traits->isAnonymousProxy,
            '$model->traits->isAnonymousProxy is false'
        );

        $this->assertSame(
            22,
            $model->maxmind->queriesRemaining,
            'queriesRemaining is correct'
        );

        $this->assertSame(
            $raw,
            $model->raw,
            'raw method returns raw input'
        );
    }

    public function testEmptyObjects()
    {
        $raw = ['traits' => ['ip_address' => '5.6.7.8']];

        $model = new Insights($raw, ['en']);

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

        $this->assertTrue(
            isset($model->mostSpecificSubdivision),
            'mostSpecificSubdivision is set'
        );

        $this->assertInstanceOf(
            'GeoIp2\Record\Traits',
            $model->traits,
            '$model->traits'
        );

        $this->assertSame(
            $raw,
            $model->raw,
            'raw method returns raw input with no added empty values'
        );
    }

    public function testUnknown()
    {
        $raw = [
            'new_top_level' => ['foo' => 42],
            'city' => [
                'confidence' => 76,
                'geoname_id_id' => 9876,
                'names' => ['en' => 'Minneapolis'],
                'population' => 50,
            ],
            'traits' => ['ip_address' => '5.6.7.8'],
        ];

        // checking whether there are exceptions with unknown keys
        $model = new Insights($raw, ['en']);

        $this->assertInstanceOf(
            'GeoIp2\Model\Insights',
            $model,
            'no exception when Insights model gets raw data with unknown keys'
        );

        $this->assertSame(
            $raw,
            $model->raw,
            'raw method returns raw input'
        );
    }

    public function testMostSpecificSubdivisionWithNoSubdivisions()
    {
        $model = new Insights([], ['en']);

        $this->assertTrue(
            isset($model->mostSpecificSubdivision),
            'mostSpecificSubdivision is set even on an empty response'
        );
    }
}
