<?php

namespace GeoIp2\Test\Model;

use GeoIp2\Model\Country;

/**
 * @coversNothing
 */
class NameTest extends \PHPUnit_Framework_TestCase
{
    public $raw = [
        'continent' => [
            'code' => 'NA',
            'geoname_id' => 42,
            'names' => [
                'en' => 'North America',
                'zh-CN' => '北美洲',
            ],
        ],
        'country' => [
            'geoname_id' => 1,
            'iso_code' => 'US',
            'names' => [
                'en' => 'United States of America',
                'ru' => 'объединяет государства',
                'zh-CN' => '美国',
            ],
        ],
        'traits' => [
            'ip_address' => '1.2.3.4',
        ],
    ];

    public function testFallback()
    {
        $model = new Country($this->raw, ['ru', 'zh-CN', 'en']);

        $this->assertSame(
            '北美洲',
            $model->continent->name,
            'continent name is in Chinese (no Russian available)'
        );

        $this->assertTrue(
            isset($model->continent->name),
            'continent name is set'
        );

        $this->assertNotEmpty(
            $model->continent->name,
            'continent name is not empty'
        );

        $this->assertSame(
            'объединяет государства',
            $model->country->name,
            'country name is in Russian'
        );
    }

    public function testTwoFallbacks()
    {
        $model = new Country($this->raw, ['ru', 'ja']);

        $this->assertNull(
            $model->continent->name,
            'continent name is undef (no Russian or Japanese available)'
        );

        $this->assertFalse(
            isset($model->continent->name),
            'continent name is not set'
        );

        $this->assertEmpty(
            $model->continent->name,
            'continent name is empty'
        );

        $this->assertSame(
            'объединяет государства',
            $model->country->name,
            'country name is in Russian'
        );
    }

    public function testNoFallbacks()
    {
        $model = new Country($this->raw, ['ja']);

        $this->assertNull(
            $model->continent->name,
            'continent name is undef (no Japanese available) '
        );

        $this->assertNull(
            $model->country->name,
            'country name is undef (no Japanese available) '
        );
    }
}
