<?php

namespace GeoIp2\Test\Database;

use GeoIp2\Database\Reader;

/**
 * @coversNothing
 */
class ReaderTest extends \PHPUnit_Framework_TestCase
{
    public function databaseTypes()
    {
        return [['City', 'city'], ['Country', 'country']];
    }

    /**
     * @dataProvider databaseTypes
     *
     * @param mixed $type
     * @param mixed $method
     */
    public function testDefaultLocale($type, $method)
    {
        $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
        $record = $reader->$method('81.2.69.160');
        $this->assertSame('United Kingdom', $record->country->name);
        $reader->close();
    }

    /**
     * @dataProvider databaseTypes
     *
     * @param mixed $type
     * @param mixed $method
     */
    public function testLocaleList($type, $method)
    {
        $reader = new Reader(
            "maxmind-db/test-data/GeoIP2-$type-Test.mmdb",
            ['xx', 'ru', 'pt-BR', 'es', 'en']
        );
        $record = $reader->$method('81.2.69.160');
        $this->assertSame('Великобритания', $record->country->name);
        $reader->close();
    }

    /**
     * @dataProvider databaseTypes
     *
     * @param mixed $type
     * @param mixed $method
     */
    public function testHasIpAddress($type, $method)
    {
        $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
        $record = $reader->$method('81.2.69.160');
        $this->assertSame('81.2.69.160', $record->traits->ipAddress);
        $reader->close();
    }

    /**
     * @dataProvider databaseTypes
     *
     * @param mixed $type
     * @param mixed $method
     */
    public function testIsInEuropeanUnion($type, $method)
    {
        $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
        $record = $reader->$method('81.2.69.160');
        $this->assertTrue(
            $record->country->isInEuropeanUnion,
            'country is_in_european_union is true'
        );
        $this->assertFalse(
            $record->registeredCountry->isInEuropeanUnion,
            'registered_country is_in_european_union is false'
        );
        $reader->close();
    }

    /**
     * @expectedException \GeoIp2\Exception\AddressNotFoundException
     * @expectedExceptionMessage The address 10.10.10.10 is not in the database.
     */
    public function testUnknownAddress()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->city('10.10.10.10');
        $reader->close();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage The country method cannot be used to open a GeoIP2-City database
     */
    public function testIncorrectDatabase()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->country('10.10.10.10');
        $reader->close();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage The domain method cannot be used to open a GeoIP2-City database
     */
    public function testIncorrectDatabaseFlat()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->domain('10.10.10.10');
        $reader->close();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage is not a valid IP address
     */
    public function testInvalidAddress()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->city('invalid');
        $reader->close();
    }

    public function testAnonymousIp()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Anonymous-IP-Test.mmdb');
        $ipAddress = '1.2.0.1';

        $record = $reader->anonymousIp($ipAddress);
        $this->assertTrue($record->isAnonymous);
        $this->assertTrue($record->isAnonymousVpn);
        $this->assertFalse($record->isHostingProvider);
        $this->assertFalse($record->isPublicProxy);
        $this->assertFalse($record->isTorExitNode);
        $this->assertSame($ipAddress, $record->ipAddress);
        $reader->close();
    }

    public function testAsn()
    {
        $reader = new Reader('maxmind-db/test-data/GeoLite2-ASN-Test.mmdb');

        $ipAddress = '1.128.0.0';
        $record = $reader->asn($ipAddress);
        $this->assertSame(1221, $record->autonomousSystemNumber);
        $this->assertSame(
            'Telstra Pty Ltd',
            $record->autonomousSystemOrganization
        );

        $this->assertSame($ipAddress, $record->ipAddress);
        $reader->close();
    }

    public function testConnectionType()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Connection-Type-Test.mmdb');
        $ipAddress = '1.0.1.0';

        $record = $reader->connectionType($ipAddress);
        $this->assertSame('Cable/DSL', $record->connectionType);
        $this->assertSame($ipAddress, $record->ipAddress);
        $reader->close();
    }

    public function testDomain()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Domain-Test.mmdb');

        $ipAddress = '1.2.0.0';
        $record = $reader->domain($ipAddress);
        $this->assertSame('maxmind.com', $record->domain);
        $this->assertSame($ipAddress, $record->ipAddress);
        $reader->close();
    }

    public function testEnterprise()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Enterprise-Test.mmdb');

        $ipAddress = '74.209.24.0';
        $record = $reader->enterprise($ipAddress);
        $this->assertSame(11, $record->city->confidence);
        $this->assertSame(99, $record->country->confidence);
        $this->assertSame(6252001, $record->country->geonameId);
        $this->assertFalse($record->country->isInEuropeanUnion);

        $this->assertSame(27, $record->location->accuracyRadius);

        $this->assertFalse($record->registeredCountry->isInEuropeanUnion);

        $this->assertSame('Cable/DSL', $record->traits->connectionType);
        $this->assertTrue($record->traits->isLegitimateProxy);

        $this->assertSame($ipAddress, $record->traits->ipAddress);
        $reader->close();
    }

    public function testIsp()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-ISP-Test.mmdb');

        $ipAddress = '1.128.0.0';
        $record = $reader->isp($ipAddress);
        $this->assertSame(1221, $record->autonomousSystemNumber);
        $this->assertSame(
            'Telstra Pty Ltd',
            $record->autonomousSystemOrganization
        );

        $this->assertSame('Telstra Internet', $record->isp);
        $this->assertSame('Telstra Internet', $record->organization);

        $this->assertSame($ipAddress, $record->ipAddress);
        $reader->close();
    }

    public function testMetadata()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $this->assertSame('GeoIP2-City', $reader->metadata()->databaseType);

        $reader->close();
    }
}
