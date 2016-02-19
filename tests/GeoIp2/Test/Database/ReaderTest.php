<?php

namespace GeoIp2\Test\WebService;

use GeoIp2\Database\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultLocale()
    {
        foreach (array('City', 'Country') as $type) {
            $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
            $method = lcfirst($type);
            $record = $reader->$method('81.2.69.160');
            $this->assertSame('United Kingdom', $record->country->name);
        }
        $reader->close();
    }

    public function testLocaleList()
    {

        foreach (array('City', 'Country') as $type) {
            $reader = new Reader(
                "maxmind-db/test-data/GeoIP2-$type-Test.mmdb",
                array('xx', 'ru', 'pt-BR', 'es', 'en')
            );
            $method = lcfirst($type);

            $record = $reader->$method('81.2.69.160');
            $this->assertSame('Великобритания', $record->country->name);
        }
        $reader->close();
    }

    public function testHasIpAddress()
    {
        foreach (array('City', 'Country') as $type) {
            $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
            $method = lcfirst($type);
            $record = $reader->$method('81.2.69.160');
            $this->assertSame('81.2.69.160', $record->traits->ipAddress);
        }
        $reader->close();
    }

    /**
     * @expectedException GeoIp2\Exception\AddressNotFoundException
     * @expectedExceptionMessage The address 10.10.10.10 is not in the database.
     */
    public function testUnknownAddress()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->city('10.10.10.10');
        $reader->close();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage The country method cannot be used to open a GeoIP2-City database
     */
    public function testIncorrectDatabase()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->country('10.10.10.10');
        $reader->close();
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage The domain method cannot be used to open a GeoIP2-City database
     */
    public function testIncorrectDatabaseFlat()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->domain('10.10.10.10');
        $reader->close();
    }

    /**
     * @expectedException InvalidArgumentException
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
        $this->assertSame(true, $record->isAnonymous);
        $this->assertSame(true, $record->isAnonymousVpn);
        $this->assertSame(false, $record->isHostingProvider);
        $this->assertSame(false, $record->isPublicProxy);
        $this->assertSame(false, $record->isTorExitNode);
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

        $this->assertSame(27, $record->location->accuracyRadius);

        $this->assertSame('Cable/DSL', $record->traits->connectionType);
        $this->assertSame(true, $record->traits->isLegitimateProxy);

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
