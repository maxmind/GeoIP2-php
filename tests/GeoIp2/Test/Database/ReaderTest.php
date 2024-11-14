<?php

declare(strict_types=1);

namespace GeoIp2\Test\Database;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class ReaderTest extends TestCase
{
    /**
     * @return array<list<string>>
     */
    public static function databaseTypes(): array
    {
        return [['City', 'city'], ['Country', 'country']];
    }

    /**
     * @dataProvider databaseTypes
     */
    public function testDefaultLocale(string $type, string $method): void
    {
        $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
        $record = $reader->{$method}('81.2.69.160');
        $this->assertSame('United Kingdom', $record->country->name);
        $reader->close();
    }

    /**
     * @dataProvider databaseTypes
     */
    public function testLocaleList(string $type, string $method): void
    {
        $reader = new Reader(
            "maxmind-db/test-data/GeoIP2-$type-Test.mmdb",
            ['xx', 'ru', 'pt-BR', 'es', 'en']
        );
        $record = $reader->{$method}('81.2.69.160');
        $this->assertSame('Великобритания', $record->country->name);
        $reader->close();
    }

    /**
     * @dataProvider databaseTypes
     */
    public function testHasIpAddressAndNetwork(string $type, string $method): void
    {
        $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
        $record = $reader->{$method}('81.2.69.163');
        $this->assertSame('81.2.69.163', $record->traits->ipAddress);
        $this->assertSame('81.2.69.160/27', $record->traits->network);
        $reader->close();
    }

    /**
     * @dataProvider databaseTypes
     */
    public function testIsInEuropeanUnion(string $type, string $method): void
    {
        $reader = new Reader("maxmind-db/test-data/GeoIP2-$type-Test.mmdb");
        $record = $reader->{$method}('2a02:cfc0::');
        $this->assertTrue(
            $record->country->isInEuropeanUnion,
            'country is_in_european_union is true'
        );
        $this->assertTrue(
            $record->registeredCountry->isInEuropeanUnion,
            'registered_country is_in_european_union is true'
        );
        $reader->close();
    }

    public function testUnknownAddress(): void
    {
        $this->expectException(AddressNotFoundException::class);
        $this->expectExceptionMessage('The address 10.10.10.10 is not in the database.');

        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->city('10.10.10.10');
        $reader->close();
    }

    public function testIncorrectDatabase(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('The country method cannot be used to open a GeoIP2-City database');

        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->country('10.10.10.10');
        $reader->close();
    }

    public function testIncorrectDatabaseFlat(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('The domain method cannot be used to open a GeoIP2-City database');

        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->domain('10.10.10.10');
        $reader->close();
    }

    public function testInvalidAddress(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('is not a valid IP address');

        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->city('invalid');
        $reader->close();
    }

    public function testAnonymousIp(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Anonymous-IP-Test.mmdb');
        $ipAddress = '1.2.0.1';

        $record = $reader->anonymousIp($ipAddress);
        $this->assertTrue($record->isAnonymous);
        $this->assertTrue($record->isAnonymousVpn);
        $this->assertFalse($record->isHostingProvider);
        $this->assertFalse($record->isPublicProxy);
        $this->assertFalse($record->isResidentialProxy);
        $this->assertFalse($record->isTorExitNode);
        $this->assertSame($ipAddress, $record->ipAddress);
        $this->assertSame('1.2.0.0/16', $record->network);
        $reader->close();
    }

    public function testAnonymousIpAllTrue(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Anonymous-IP-Test.mmdb');
        $ipAddress = '81.2.69.1';

        $record = $reader->anonymousIp($ipAddress);
        $this->assertTrue($record->isAnonymous);
        $this->assertTrue($record->isAnonymousVpn);
        $this->assertTrue($record->isHostingProvider);
        $this->assertTrue($record->isPublicProxy);
        $this->assertTrue($record->isResidentialProxy);
        $this->assertTrue($record->isTorExitNode);
        $this->assertSame($ipAddress, $record->ipAddress);
        $this->assertSame('81.2.69.0/24', $record->network);
        $reader->close();
    }

    public function testAsn(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoLite2-ASN-Test.mmdb');

        $ipAddress = '1.128.0.1';
        $record = $reader->asn($ipAddress);
        $this->assertSame(1221, $record->autonomousSystemNumber);
        $this->assertSame(
            'Telstra Pty Ltd',
            $record->autonomousSystemOrganization
        );

        $this->assertSame($ipAddress, $record->ipAddress);
        $this->assertSame('1.128.0.0/11', $record->network);
        $reader->close();
    }

    public function testConnectionType(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Connection-Type-Test.mmdb');
        $ipAddress = '1.0.1.1';

        $record = $reader->connectionType($ipAddress);
        $this->assertSame('Cellular', $record->connectionType);
        $this->assertSame($ipAddress, $record->ipAddress);
        $this->assertSame('1.0.1.0/24', $record->network);
        $reader->close();
    }

    public function testCity(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');

        // This IP has is_anycast
        $record = $reader->city('214.1.1.0');
        $this->assertTrue($record->traits->isAnycast);

        $reader->close();
    }

    public function testCountry(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Country-Test.mmdb');

        // This IP has is_anycast
        $record = $reader->country('214.1.1.0');
        $this->assertTrue($record->traits->isAnycast);

        $reader->close();
    }

    public function testDomain(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-Domain-Test.mmdb');

        $ipAddress = '1.2.0.1';
        $record = $reader->domain($ipAddress);
        $this->assertSame('maxmind.com', $record->domain);
        $this->assertSame($ipAddress, $record->ipAddress);
        $this->assertSame('1.2.0.0/16', $record->network);
        $reader->close();
    }

    public function testEnterprise(): void
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
        $this->assertSame('74.209.16.0/20', $record->traits->network);

        $record = $reader->enterprise('149.101.100.0');
        $this->assertSame('310', $record->traits->mobileCountryCode);
        $this->assertSame('004', $record->traits->mobileNetworkCode);

        // This IP has is_anycast
        $record = $reader->enterprise('214.1.1.0');
        $this->assertTrue($record->traits->isAnycast);

        $reader->close();
    }

    public function testIsp(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-ISP-Test.mmdb');

        $ipAddress = '1.128.1.1';
        $record = $reader->isp($ipAddress);
        $this->assertSame(1221, $record->autonomousSystemNumber);
        $this->assertSame(
            'Telstra Pty Ltd',
            $record->autonomousSystemOrganization
        );

        $this->assertSame('Telstra Internet', $record->isp);
        $this->assertSame('Telstra Internet', $record->organization);

        $this->assertSame($ipAddress, $record->ipAddress);
        $this->assertSame('1.128.0.0/11', $record->network);

        $record = $reader->isp('149.101.100.0');
        $this->assertSame('310', $record->mobileCountryCode);
        $this->assertSame('004', $record->mobileNetworkCode);

        $reader->close();
    }

    public function testMetadata(): void
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $this->assertSame('GeoIP2-City', $reader->metadata()->databaseType);

        $reader->close();
    }
}
