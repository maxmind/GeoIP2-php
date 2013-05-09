<?php

namespace GeoIP2\Test\Model;

use GeoIP2\Model\Omni;

class OmniTest extends \PHPUnit_Framework_TestCase
{

    public function testFull()
    {

        $raw = array(
            'city' => array(
                'confidence' => 76,
                'geoname_id' => 9876,
                'names'      => array( 'en' => 'Minneapolis' ),
            ),
            'continent' => array(
                'continent_code' => 'NA',
                'geoname_id'     => 42,
                'names'          => array( 'en' => 'North America' ),
            ),
            'country' => array(
                'confidence' => 99,
                'geoname_id' => 1,
                'iso_code'   => 'US',
                'names'      => array( 'en' => 'United States of America' ),
            ),
            'location' => array(
                'accuracy_radius'   => 1500,
                'latitude'          => 44.98,
                'longitude'         => 93.2636,
                'metro_code'        => 765,
                'postal_code'       => '55401',
                'postal_confidence' => 33,
                'time_zone'         => 'America/Chicago',
            ),
            'registered_country' => array(
                'geoname_id' => 2,
                'iso_code'   => 'CA',
                'names'      => array( 'en' => 'Canada' ),
            ),
            'represented_country' => array(
                'geoname_id' => 3,
                'iso_code'   => 'GB',
                'names'      => array( 'en' => 'United Kingdom' ),
            ),
            'subdivisions' => array(
                array(
                    'confidence' => 88,
                    'geoname_id' => 574635,
                    'iso_code'   => 'MN',
                    'names'      => array( 'en' => 'Minnesota' ),
                )
            ),
            'traits' => array(
                'autonomous_system_number'       => 1234,
                'autonomous_system_organization' => 'AS Organization',
                'domain'                         => 'example.com',
                'ip_address'                     => '1.2.3.4',
                'is_satellite_provider'          => 1,
                'isp'                            => 'Comcast',
                'organization'                   => 'Blorg',
                'user_type'                      => 'college',
            ),
        );

        $model = new Omni($raw, array('en'));

        $this->assertInstanceOf(
            'GeoIP2\Model\Omni',
            $model,
            'GeoIP2\Model\Omni object'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\City',
            $model->city,
            '$model->city'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Continent',
            $model->continent,
            '$model->continent'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Country',
            $model->country,
            '$model->country'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Location',
            $model->location,
            '$model->location'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Country',
            $model->registeredCountry,
            '$model->registeredCountry'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\RepresentedCountry',
            $model->representedCountry,
            '$model->representedCountry'
        );

        $subdivisions = $model->subdivisions;
        foreach ($subdivisions as $subdiv) {
            $this->assertInstanceOf('GeoIP2\Record\Subdivision', $subdiv);
        }

        $this->assertInstanceOf(
            'GeoIP2\Record\Subdivision',
            $model->mostSpecificSubdivision,
            '$model->mostSpecificSubdivision'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Traits',
            $model->traits,
            '$model->traits'
        );

        $this->assertEquals(
            $raw,
            $model->raw,
            'raw method returns raw input'
        );
    }

    public function testEmptyObjects()
    {
        $raw = array( 'traits' => array( 'ip_address' => '5.6.7.8' ) );

        $model = new Omni($raw, array('en'));

        $this->assertInstanceOf(
            'GeoIP2\Model\Omni',
            $model,
            'GeoIP2\Model\Omni object with no data except traits.ipAddress'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\City',
            $model->city,
            '$model->city'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Continent',
            $model->continent,
            '$model->continent'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Country',
            $model->country,
            '$model->country'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Location',
            $model->location,
            '$model->location'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Country',
            $model->registeredCountry,
            '$model->registeredCountry'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\RepresentedCountry',
            $model->representedCountry,
            '$model->representedCountry'
        );
        
        $this->assertCount(
            0,
            $model->subdivisions,
            '$model->subdivisions returns an empty list'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Subdivision',
            $model->mostSpecificSubdivision,
            '$model->mostSpecificSubdivision'
        );

        $this->assertInstanceOf(
            'GeoIP2\Record\Traits',
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
            'new_top_level' => array( 'foo' => 42 ),
            'city'          => array(
                'confidence' => 76,
            'geoname_id_id' => 9876,
                'names'      => array( 'en' => 'Minneapolis' ),
                'population' => 50,
            ),
            'traits' => array( 'ip_address' => '5.6.7.8' )
        );

        // checking whether there are exceptions with unknown keys
        $model = new Omni($raw, array('en'));

        $this->assertInstanceOf(
            'GeoIP2\Model\Omni',
            $model,
            'no exception when Omni model gets raw data with unknown keys'
        );

        $this->assertEquals(
            $raw,
            $model->raw,
            'raw method returns raw input'
        );
    }
}
