<?php

declare(strict_types=1);

namespace GeoIp2\Test\Model;

use GeoIp2\Model\Insights;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class InsightsTest extends TestCase
{
    public function testFull(): void
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
                'time_zone' => 'America/Chicago',
            ],
            'maxmind' => [
                'queries_remaining' => 22,
            ],
            'postal' => [
                'code' => '55401',
                'confidence' => 33,
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
                'type' => 'military',
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
                'connection_type' => 'Cable/DSL',
                'domain' => 'example.com',
                'ip_address' => '1.2.3.4',
                'is_anonymous' => true,
                'is_anonymous_vpn' => true,
                'is_anycast' => true,
                'is_hosting_provider' => true,
                'is_legitimate_proxy' => true,
                'is_public_proxy' => true,
                'is_residential_proxy' => true,
                'is_tor_exit_node' => true,
                'isp' => 'Comcast',
                'mobile_country_code' => '310',
                'mobile_network_code' => '004',
                'network' => '1.2.3.0/24',
                'organization' => 'Blorg',
                'static_ip_score' => 1.3,
                'user_count' => 2,
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
            $model->traits->isAnycast,
            '$model->traits->isAnycast is true'
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
            $model->traits->isResidentialProxy,
            '$model->traits->isResidentialProxy is true'
        );

        $this->assertTrue(
            $model->traits->isTorExitNode,
            '$model->traits->isTorExitNode is true'
        );

        $this->assertSame(
            '310',
            $model->traits->mobileCountryCode,
            'mobileCountryCode is correct'
        );

        $this->assertSame(
            '004',
            $model->traits->mobileNetworkCode,
            'mobileNetworkCode is correct'
        );

        $this->assertSame(
            1.3,
            $model->traits->staticIpScore,
            'staticIPScore is correct'
        );

        $this->assertSame(
            22,
            $model->maxmind->queriesRemaining,
            'queriesRemaining is correct'
        );

        $this->assertSame(
            2,
            $model->traits->userCount,
            'userCount is correct'
        );

        $this->assertSame(
            [
                'continent' => [
                    'names' => ['en' => 'North America'],
                    'code' => 'NA',
                    'geoname_id' => 42,
                ],
                'country' => [
                    'names' => ['en' => 'United States of America'],
                    'confidence' => 99,
                    'geoname_id' => 1,
                    'iso_code' => 'US',
                ],
                'maxmind' => [
                    'queries_remaining' => 22,
                ],
                'registered_country' => [
                    'names' => ['en' => 'Canada'],
                    'geoname_id' => 2,
                    'iso_code' => 'CA',
                ],
                'represented_country' => [
                    'names' => ['en' => 'United Kingdom'],
                    'geoname_id' => 3,
                    'iso_code' => 'GB',
                    'type' => 'military',
                ],
                'traits' => [
                    'autonomous_system_number' => 1234,
                    'autonomous_system_organization' => 'AS Organization',
                    'connection_type' => 'Cable/DSL',
                    'domain' => 'example.com',
                    'ip_address' => '1.2.3.4',
                    'is_anonymous' => true,
                    'is_anonymous_vpn' => true,
                    'is_anycast' => true,
                    'is_hosting_provider' => true,
                    'is_legitimate_proxy' => true,
                    'is_public_proxy' => true,
                    'is_residential_proxy' => true,
                    'is_tor_exit_node' => true,
                    'isp' => 'Comcast',
                    'mobile_country_code' => '310',
                    'mobile_network_code' => '004',
                    'network' => '1.2.3.0/24',
                    'organization' => 'Blorg',
                    'static_ip_score' => 1.3,
                    'user_count' => 2,
                    'user_type' => 'college',
                ],
                'city' => [
                    'names' => ['en' => 'Minneapolis'],
                    'confidence' => 76,
                    'geoname_id' => 9876,
                ],
                'location' => [
                    'average_income' => 24626,
                    'accuracy_radius' => 1500,
                    'latitude' => 44.98,
                    'longitude' => 93.2636,
                    'metro_code' => 765,
                    'population_density' => 1341,
                    'time_zone' => 'America/Chicago',
                ],
                'postal' => [
                    'code' => '55401',
                    'confidence' => 33,
                ],
                'subdivisions' => [
                    [
                        'names' => ['en' => 'Minnesota'],
                        'confidence' => 88,
                        'geoname_id' => 574635,
                        'iso_code' => 'MN',
                    ],
                ],
            ],
            $model->jsonSerialize(),
            'jsonSerialize returns initial array'
        );
    }

    public function testEmptyObjects(): void
    {
        $raw = ['traits' => ['ip_address' => '5.6.7.8', 'network' => '5.6.7.0/24']];

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

        $this->assertInstanceOf(
            'GeoIp2\Record\Traits',
            $model->traits,
            '$model->traits'
        );

        $this->assertSame(
            $raw,
            $model->jsonSerialize(),
            'jsonSerialize',
        );
    }

    public function testUnknown(): void
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
    }

    public function testMostSpecificSubdivisionWithNoSubdivisions(): void
    {
        $model = new Insights(['traits' => ['ip_address' => '1.1.1.1']], ['en']);

        $this->assertTrue(
            isset($model->mostSpecificSubdivision),
            'mostSpecificSubdivision is set even on an empty response'
        );
    }
}
