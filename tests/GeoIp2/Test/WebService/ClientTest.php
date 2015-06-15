<?php

namespace GeoIp2\Test\WebService;

use GeoIp2\WebService\Client;
use MaxMind\WebService\Client as WsClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    private $country
        = array(
            'continent' => array(
                'code' => 'NA',
                'geoname_id' => 42,
                'names' => array('en' => 'North America'),
            ),
            'country' => array(
                'geoname_id' => 1,
                'iso_code' => 'US',
                'names' => array('en' => 'United States of America'),
            ),
            'maxmind' => array('queries_remaining' => 11),
            'traits' => array(
                'ip_address' => '1.2.3.4',
            ),
        );


    private function getResponse($ipAddress)
    {
        $responses = array(
            '1.2.3.4' => $this->response(
                'country',
                200,
                $this->country
            ),
            'me' => $this->response(
                'country',
                200,
                $this->country
            ),
            '1.2.3.5' => $this->response('country', 200),
            '2.2.3.5' => $this->response('country', 200, 'bad body'),
            '1.2.3.6' => $this->response(
                'error',
                400,
                array(
                    'code' => 'IP_ADDRESS_INVALID',
                    'error' => 'The value "1.2.3" is not a valid ip address'
                )
            ),
            '1.2.3.7' => $this->response(
                'error',
                400
            ),
            '1.2.3.8' => $this->response(
                'error',
                400,
                array('weird' => 42)
            ),
            '1.2.3.9' => $this->response(
                'error',
                400,
                null,
                'bad body'
            ),
            '1.2.3.10' => $this->response(
                null,
                500
            ),
            '1.2.3.11' => $this->response(
                null,
                300
            ),
            '1.2.3.12' => $this->response(
                'error',
                406,
                'Cannot satisfy your Accept-Charset requirements',
                null,
                'text/plain'
            ),
            '1.2.3.13' => $this->response(
                'error',
                404,
                array(
                    'code' => 'IP_ADDRESS_NOT_FOUND',
                    'error' => 'The address "1.2.3.13" is not in our database.'
                )
            ),
            '1.2.3.14' => $this->response(
                'error',
                400,
                array(
                    'code' => 'IP_ADDRESS_RESERVED',
                    'error' => 'The address "1.2.3.14" is a private address.'
                )
            ),
            '1.2.3.15' => $this->response(
                'error',
                401,
                array(
                    'code' => 'AUTHORIZATION_INVALID',
                    'error' => 'A user ID and license key are required to use this service'
                )
            ),
            '1.2.3.16' => $this->response(
                'error',
                401,
                array(
                    'code' => 'LICENSE_KEY_REQUIRED',
                    'error' => 'A license key is required to use this service'
                )
            ),
            '1.2.3.17' => $this->response(
                'error',
                401,
                array(
                    'code' => 'USER_ID_REQUIRED',
                    'error' => 'A user ID is required to use this service'
                )
            ),
            '1.2.3.18' => $this->response(
                'error',
                402,
                array(
                    'code' => 'OUT_OF_QUERIES',
                    'error' => 'The license key you have provided is out of queries.'
                )
            ),
        );
        return $responses[$ipAddress];
    }

    public function testCountry()
    {
        $country = $this->makeRequest('Country', '1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\Country', $country);

        $this->assertEquals(
            42,
            $country->continent->geonameId,
            'continent geoname_id is 42'
        );

        $this->assertEquals(
            'NA',
            $country->continent->code,
            'continent code is NA'
        );

        $this->assertEquals(
            array('en' => 'North America'),
            $country->continent->names,
            'continent names'
        );

        $this->assertEquals(
            'North America',
            $country->continent->name,
            'continent name is North America'
        );

        $this->assertEquals(
            1,
            $country->country->geonameId,
            'country geoname_id is 1'
        );

        $this->assertEquals(
            'US',
            $country->country->isoCode,
            'country iso_code is US'
        );

        $this->assertEquals(
            array('en' => 'United States of America'),
            $country->country->names,
            'country names'
        );

        $this->assertEquals(
            'United States of America',
            $country->country->name,
            'country name is United States of America'
        );

        $this->assertEquals(
            11,
            $country->maxmind->queriesRemaining,
            'queriesRemaining is correct'
        );

    }


    public function testInsights()
    {
        $record = $this->makeRequest('Insights', '1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\Insights', $record);

        $this->assertEquals(
            42,
            $record->continent->geonameId,
            'continent geoname_id is 42'
        );
    }

    public function testCity()
    {
        $city = $this->makeRequest('City', '1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\City', $city);
    }

    public function testMe()
    {
        $city = $this->makeRequest('City', 'me');

        $this->assertInstanceOf(
            'GeoIp2\Model\City',
            $city,
            'can set ip parameter to me'
        );
    }

    /**
     * @expectedException GeoIp2\Exception\GeoIp2Exception
     * @expectedExceptionMessage Received a 200 response for GeoIP2 Country but did not receive a HTTP body.
     */
    public function testNoBodyException()
    {
        $this->makeRequest('Country', '1.2.3.5');
    }

    /**
     * @expectedException GeoIp2\Exception\GeoIp2Exception
     * @expectedExceptionMessage Received a 200 response for GeoIP2 Country but could not decode the response as JSON:
     */
    public function testBadBodyException()
    {
        $this->makeRequest('Country', '2.2.3.5');
    }


    /**
     * @expectedException GeoIp2\Exception\InvalidRequestException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage The value "1.2.3" is not a valid ip address
     */
    public function testInvalidIPException()
    {
        $this->makeRequest('Country', '1.2.3.6');
    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage with no body
     */
    public function testNoErrorBodyIPException()
    {
        $this->makeRequest('Country', '1.2.3.7');
    }

    /**
     * @expectedException GeoIp2\Exception\GeoIp2Exception
     * @expectedExceptionMessage Error response contains JSON but it does not specify code or error keys: {"weird":42}
     */
    public function testWeirdErrorBodyIPException()
    {
        $this->makeRequest('Country', '1.2.3.8');

    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage Received a 400 error for GeoIP2 Country but could not decode the response as JSON: Syntax error. Body: { invalid: }
     */
    public function testInvalidErrorBodyIPException()
    {
        $this->makeRequest('Country', '1.2.3.9');

    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Received a server error (500)
     */
    public function test500PException()
    {
        $this->makeRequest('Country', '1.2.3.10');
    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 300
     * @expectedExceptionMessage Received an unexpected HTTP status (300) for GeoIP2 Country
     */
    public function test3xxException()
    {
        $this->makeRequest('Country', '1.2.3.11');
    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 406
     * @expectedExceptionMessage Received a 406 error for GeoIP2 Country with the following body: Cannot satisfy your Accept-Charset requirements
     */
    public function test406Exception()
    {
        $this->makeRequest('Country','1.2.3.12');
    }

    /**
     * @expectedException GeoIp2\Exception\AddressNotFoundException
     * @expectedExceptionMessage The address "1.2.3.13" is not in our database.
     */
    public function testAddressNotFoundException()
    {
        $this->makeRequest('Country', '1.2.3.13');
    }

    /**
     * @expectedException GeoIp2\Exception\AddressNotFoundException
     * @expectedExceptionMessage The address "1.2.3.14" is a private address.
     */
    public function testAddressReservedException()
    {
        $this->makeRequest('Country', '1.2.3.14');
    }

    /**
     * @expectedException GeoIp2\Exception\AuthenticationException
     * @expectedExceptionMessage A user ID and license key are required to use this service
     */
    public function testAuthorizationException()
    {
        $this->makeRequest('Country', '1.2.3.15');
    }

    /**
     * @expectedException GeoIp2\Exception\AuthenticationException
     * @expectedExceptionMessage A license key is required to use this service
     */
    public function testMissingLicenseKeyException()
    {
        $this->makeRequest('Country', '1.2.3.16');
    }

    /**
     * @expectedException GeoIp2\Exception\AuthenticationException
     * @expectedExceptionMessage A user ID is required to use this service
     */
    public function testMissingUserIdException()
    {
        $this->makeRequest('Country', '1.2.3.17');
    }

    /**
     * @expectedException GeoIp2\Exception\OutOfQueriesException
     * @expectedExceptionMessage The license key you have provided is out of queries.
     */
    public function testOutOfQueriesException()
    {
        $this->makeRequest('Country', '1.2.3.18');
    }

    public function testParams()
    {
        $this->makeRequest(
            'Country',
            '1.2.3.4',
            array('en'),
            array(
                'host' => 'api.maxmind.com',
                'timeout' => 27,
                'connectTimeout' => 72,
            )
        );
    }


    private function response(
        $endpoint,
        $status,
        $body = null,
        $bad = null,
        $contentType = null
    ) {
        $headers = array();
        if ($contentType) {
            $headers['Content-Type'] = $contentType;
        } elseif ($status == 200 || ($status >= 400 && $status < 500)) {
            $headers['Content-Type'] = 'application/vnd.maxmind.com-'
                . $endpoint . '+json; charset=UTF-8; version=1.0;';
        }

        if ($bad) {
            $body = '{ invalid: }';
        } elseif (is_array($body)) {
            $body = json_encode($body);
        }

        $headers['Content-Length'] = strlen($body);

        return array($status, $headers,  $body, );
    }

    private function makeRequest(
        $service,
        $ipAddress,
        $locales = array('en'),
        $options = array(),
        $callsToRequest = 1
    ) {
        $userId = 42;
        $licenseKey = 'abcdef123456';

        list($statusCode, $headers, $responseBody)
            = $this->getResponse($ipAddress);

        $stub = $this->getMockForAbstractClass(
            'MaxMind\\WebService\\Http\\Request'
        );
        $contentType = isset($headers['Content-Type'])
            ? $headers['Content-Type']
            : null;
        $stub->expects($this->exactly($callsToRequest))
            ->method('get')
            ->willReturn(array($statusCode, $contentType, $responseBody));
        $factory = $this->getMockBuilder(
            'MaxMind\\WebService\\Http\\RequestFactory'
        )->getMock();
        $host = isset($options['host']) ? $options['host'] : 'geoip.maxmind.com';
        $url = 'https://' . $host . '/geoip/v2.1/' . strtolower($service)
            . '/' . $ipAddress;
        $headers = array(
            'Authorization: Basic '
            . base64_encode($userId . ':' . $licenseKey),
            'Accept: application/json',
        );

        $reflectionClass = new \ReflectionClass('MaxMind\\WebService\\Client');
        $file = $reflectionClass->getFileName();
        $caBundle = dirname($file) . '/cacert.pem';

        $curlVersion = curl_version();
        $factory->expects($this->exactly($callsToRequest))
            ->method('request')
            ->with(
                $this->equalTo($url),
                $this->equalTo(
                    array(
                        'headers' => $headers,
                        'userAgent' => 'GeoIP2-API/' . \GeoIp2\WebService\Client::VERSION
                            . ' MaxMind-WS-API/' . WsClient::VERSION
                            . ' PHP/' . PHP_VERSION
                            . ' curl/' . $curlVersion['version'],
                        'connectTimeout' => isset($options['connectTimeout'])
                            ? $options['connectTimeout'] : null,
                        'timeout' => isset($options['timeout'])
                            ? $options['timeout'] : null,
                        'caBundle' => $caBundle,
                    )
                )
            )->willReturn($stub);
        $options['httpRequestFactory'] = $factory;

        $method = strtolower($service);

        $client = new \GeoIp2\WebService\Client(
            $userId,
            $licenseKey,
            $locales,
            $options
        );
        return $client->$method($ipAddress);
    }

}
