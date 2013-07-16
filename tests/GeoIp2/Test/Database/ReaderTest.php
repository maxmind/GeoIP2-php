<?php

namespace GeoIp2\Test\WebService;

use GeoIp2\Database\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultLanguage()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        // Needed for PHP 5.3
        $that = $this;
        $this->checkAllMethods(
            function ($method) use (&$that, &$reader) {
                $record = $reader->$method('81.2.69.160');
                $that->assertEquals('United Kingdom', $record->country->name);
            }
        );
        $reader->close();
    }

    public function testLanguageList()
    {
        $reader = new Reader(
            'maxmind-db/test-data/GeoIP2-City-Test.mmdb',
            array('xx', 'ru', 'pt-BR', 'es', 'en')
        );
        $that = $this;
        $this->checkAllMethods(
            function ($method) use (&$that, &$reader) {
                $record = $reader->$method('81.2.69.160');
                $that->assertEquals('Великобритания', $record->country->name);
            }
        );
        $reader->close();
    }

    public function testHasIpAddress()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $that = $this;
        $this->checkAllMethods(
            function ($method) use (&$that, &$reader) {
                $record = $reader->$method('81.2.69.160');
                $that->assertEquals('81.2.69.160', $record->traits->ipAddress);
            }
        );
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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid is not a valid IP address
     */
    public function testInvalidAddress()
    {
        $reader = new Reader('maxmind-db/test-data/GeoIP2-City-Test.mmdb');
        $reader->city('invalid');
        $reader->close();
    }

    public function checkAllMethods($testCb)
    {
        foreach (array('city', 'cityIspOrg', 'country', 'omni') as $method) {
            call_user_func_array($testCb, array($method));
        }
    }
}
