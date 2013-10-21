<?php

namespace GeoIp2\Test\Model;

use GeoIp2\Model\Country;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public $raw = array(
        'continent' => array(
            'code' => 'NA',
            'geoname_id' => 42,
            'names' => array(
                'en' => 'North America',
                'zh-CN' => '北美洲',
            ),
        ),
        'country' => array(
            'geoname_id' => 1,
            'iso_code' => 'US',
            'names' => array(
                'en' => 'United States of America',
                'ru' => 'объединяет государства',
                'zh-CN' => '美国',
            ),
        ),
        'traits' => array(
            'ip_address' => '1.2.3.4',
        ),
    );

    public function testFallback()
    {
        $model = new Country($this->raw, array('ru', 'zh-CN', 'en'));

        $this->assertEquals(
            '北美洲',
            $model->continent->name,
            'continent name is in Chinese (no Russian available)'
        );

        $this->assertEquals(
            'объединяет государства',
            $model->country->name,
            'country name is in Russian'
        );
    }

    public function testTwoFallbacks()
    {
        $model = new Country($this->raw, array('ru', 'ja'));

        $this->assertEquals(
            null,
            $model->continent->name,
            'continent name is undef (no Russian or Japanese available)'
        );

        $this->assertEquals(
            'объединяет государства',
            $model->country->name,
            'country name is in Russian'
        );
    }

    public function testNoFallbacks()
    {
        $model = new Country($this->raw, array('ja'));

        $this->assertEquals(
            null,
            $model->continent->name,
            'continent name is undef (no Japanese available) '
        );

        $this->assertEquals(
            null,
            $model->country->name,
            'country name is undef (no Japanese available) '
        );
    }
}
