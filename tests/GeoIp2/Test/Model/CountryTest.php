<?php

declare(strict_types=1);

namespace GeoIp2\Test\Model;

use GeoIp2\Model\Country;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class CountryTest extends TestCase
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private $raw = [
        'continent' => [
            'code' => 'NA',
            'geoname_id' => 42,
            'names' => ['en' => 'North America'],
        ],
        'country' => [
            'geoname_id' => 1,
            'iso_code' => 'US',
            'names' => ['en' => 'United States of America'],
        ],
        'registered_country' => [
            'geoname_id' => 2,
            'is_in_european_union' => true,
            'iso_code' => 'DE',
            'names' => ['en' => 'Germany'],
        ],
        'traits' => [
            'ip_address' => '1.2.3.4',
            'is_anycast' => true,
            'prefix_len' => 24,
        ],
    ];

    // @phpstan-ignore-next-line
    private ?Country $model;

    protected function setUp(): void
    {
        $this->model = new Country($this->raw, ['en']);
    }

    public function testObjects(): void
    {
        $this->assertInstanceOf(
            'GeoIp2\Model\Country',
            $this->model,
            'minimal GeoIp2::Model::Country object'
        );
        $this->assertInstanceOf(
            'GeoIp2\Record\Continent',
            $this->model->continent
        );
        $this->assertInstanceOf(
            'GeoIp2\Record\Country',
            $this->model->country
        );
        $this->assertInstanceOf(
            'GeoIp2\Record\Country',
            $this->model->registeredCountry
        );
        $this->assertInstanceOf(
            'GeoIp2\Record\RepresentedCountry',
            $this->model->representedCountry
        );
        $this->assertInstanceOf(
            'GeoIp2\Record\Traits',
            $this->model->traits
        );
    }

    public function testValues(): void
    {
        $this->assertSame(
            42,
            $this->model->continent->geonameId,
            'continent geoname_id is 42'
        );

        $this->assertSame(
            'NA',
            $this->model->continent->code,
            'continent code is NA'
        );

        $this->assertSame(
            ['en' => 'North America'],
            $this->model->continent->names,
            'continent names'
        );

        $this->assertSame(
            'North America',
            $this->model->continent->name,
            'continent name is North America'
        );

        $this->assertSame(
            1,
            $this->model->country->geonameId,
            'country geoname_id is 1'
        );

        $this->assertFalse(
            $this->model->country->isInEuropeanUnion,
            'country is_in_european_union is false'
        );

        $this->assertSame(
            'US',
            $this->model->country->isoCode,
            'country iso_code is US'
        );

        $this->assertSame(
            ['en' => 'United States of America'],
            $this->model->country->names,
            'country name'
        );

        $this->assertSame(
            $this->model->country->name,
            'United States of America',
            'country name is United States of America'
        );

        $this->assertNull(
            $this->model->country->confidence,
            'country confidence is undef'
        );

        $this->assertSame(
            2,
            $this->model->registeredCountry->geonameId,
            'registered_country geoname_id is 2'
        );

        $this->assertTrue(
            $this->model->registeredCountry->isInEuropeanUnion,
            'registered_country is_in_european_union is true'
        );

        $this->assertSame(
            'DE',
            $this->model->registeredCountry->isoCode,
            'registered_country iso_code is Germany'
        );

        $this->assertSame(
            ['en' => 'Germany'],
            $this->model->registeredCountry->names,
            'registered_country names'
        );

        $this->assertSame(
            'Germany',
            $this->model->registeredCountry->name,
            'registered_country name is Germany'
        );
    }

    public function testJsonSerialize(): void
    {
        $js =
        [
            'continent' => [
                'names' => ['en' => 'North America'],
                'code' => 'NA',
                'geoname_id' => 42,
            ],
            'country' => [
                'names' => ['en' => 'United States of America'],
                'geoname_id' => 1,
                'iso_code' => 'US',
            ],
            'registered_country' => [
                'names' => ['en' => 'Germany'],
                'geoname_id' => 2,
                'is_in_european_union' => true,
                'iso_code' => 'DE',
            ],
            'traits' => [
                'ip_address' => '1.2.3.4',
                'is_anycast' => true,
                'network' => '1.2.3.0/24',
            ],
        ];
        $this->assertSame(
            $js,
            $this->model->jsonSerialize(),
            'jsonSerialize returns initial array'
        );

        $this->assertSame(
            $js['country'],
            $this->model->country->jsonSerialize(),
            'jsonSerialize returns initial array for the record'
        );

        $this->assertSame(
            json_encode($js),
            json_encode($this->model),
            'json_encode can be called on the model object directly'
        );

        $this->assertSame(
            json_encode($js['country']),
            json_encode($this->model->country),
            'json_encode can be called on the record object directly'
        );
    }

    public function testIsSet(): void
    {
        $this->assertTrue(isset($this->model->traits), 'traits is set');
        $this->assertFalse(isset($this->model->unknown), 'unknown is not set');

        $this->assertTrue(
            isset($this->model->traits->ipAddress),
            'ip_address is set'
        );
        $this->assertTrue(
            isset($this->model->traits->network),
            'network is set'
        );
        $this->assertFalse(
            isset($this->model->traits->unknown),
            'unknown trait is not set'
        );
    }
}
