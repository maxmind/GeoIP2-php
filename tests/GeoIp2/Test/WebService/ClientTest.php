<?php

namespace GeoIp2\Test\WebService;

use GeoIp2\WebService\Client;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

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


    private function getResponse($ip)
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
        return $responses[$ip];
    }

    public function testCountry()
    {
        $country = $this->client($this->getResponse('1.2.3.4'))
            ->country('1.2.3.4');

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

        $record = $this->client($this->getResponse('1.2.3.4'))
            ->insights('1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\Insights', $record);

        $this->assertEquals(
            42,
            $record->continent->geonameId,
            'continent geoname_id is 42'
        );
    }

    public function testCity()
    {
        $city = $this->client($this->getResponse('1.2.3.4'))
            ->city('1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\City', $city);
    }

    public function testMe()
    {
        $client = $this->client($this->getResponse('me'));

        $this->assertInstanceOf(
            'GeoIp2\Model\City',
            $client->city('me'),
            'can set ip parameter to me'
        );
    }

    /**
     * @expectedException GeoIp2\Exception\GeoIp2Exception
     * @expectedExceptionMessage Received a 200 response for https://geoip.maxmind.com/geoip/v2.1/country/1.2.3.5 but did not receive a HTTP body.
     */
    public function testNoBodyException()
    {
        $client = $this->client($this->getResponse('1.2.3.5'));

        $client->country('1.2.3.5');
    }

    /**
     * @expectedException GeoIp2\Exception\GeoIp2Exception
     * @expectedExceptionMessage Received a 200 response for https://geoip.maxmind.com/geoip/v2.1/country/2.2.3.5 but could not decode the response as JSON:
     */
    public function testBadBodyException()
    {
        $client = $this->client($this->getResponse('2.2.3.5'));

        $client->country('2.2.3.5');
    }


    /**
     * @expectedException GeoIp2\Exception\InvalidRequestException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage The value "1.2.3" is not a valid ip address
     */
    public function testInvalidIPException()
    {
        $client = $this->client($this->getResponse('1.2.3.6'));

        $client->country('1.2.3.6');
    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage with no body
     */
    public function testNoErrorBodyIPException()
    {
        $client = $this->client($this->getResponse('1.2.3.7'));

        $client->country('1.2.3.7');
    }

    /**
     * @expectedException GeoIp2\Exception\GeoIp2Exception
     * @expectedExceptionMessage Response contains JSON but it does not specify code or error keys
     */
    public function testWeirdErrorBodyIPException()
    {
        $client = $this->client($this->getResponse('1.2.3.8'));

        $client->country('1.2.3.8');

    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage did not include the expected JSON body
     */
    public function testInvalidErrorBodyIPException()
    {
        $client = $this->client($this->getResponse('1.2.3.9'));

        $client->country('1.2.3.9');

    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Received a server error (500)
     */
    public function test500PException()
    {
        $client = $this->client($this->getResponse('1.2.3.10'));

        $client->country('1.2.3.10');

    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 300
     * @expectedExceptionMessage Received a very surprising HTTP status (300)
     */
    public function test3xxException()
    {
        $client = $this->client($this->getResponse('1.2.3.11'));

        $client->country('1.2.3.11');

    }

    /**
     * @expectedException GeoIp2\Exception\HttpException
     * @expectedExceptionCode 406
     * @expectedExceptionMessage Received a 406 error for https://geoip.maxmind.com/geoip/v2.1/country/1.2.3.12 with the following body: Cannot satisfy your Accept-Charset requirements
     */
    public function test406Exception()
    {
        $client = $this->client($this->getResponse('1.2.3.12'));
        $client->country('1.2.3.12');
    }

    /**
     * @expectedException GeoIp2\Exception\AddressNotFoundException
     * @expectedExceptionMessage The address "1.2.3.13" is not in our database.
     */
    public function testAddressNotFoundException()
    {
        $client = $this->client($this->getResponse('1.2.3.13'));

        $client->country('1.2.3.13');
    }

    /**
     * @expectedException GeoIp2\Exception\AddressNotFoundException
     * @expectedExceptionMessage The address "1.2.3.14" is a private address.
     */
    public function testAddressReservedException()
    {
        $client = $this->client($this->getResponse('1.2.3.14'));

        $client->country('1.2.3.14');
    }

    /**
     * @expectedException GeoIp2\Exception\AuthenticationException
     * @expectedExceptionMessage A user ID and license key are required to use this service
     */
    public function testAuthorizationException()
    {
        $client = $this->client($this->getResponse('1.2.3.15'));

        $client->country('1.2.3.15');
    }

    /**
     * @expectedException GeoIp2\Exception\AuthenticationException
     * @expectedExceptionMessage A license key is required to use this service
     */
    public function testMissingLicenseKeyException()
    {
        $client = $this->client($this->getResponse('1.2.3.16'));

        $client->country('1.2.3.16');
    }

    /**
     * @expectedException GeoIp2\Exception\AuthenticationException
     * @expectedExceptionMessage A user ID is required to use this service
     */
    public function testMissingUserIdException()
    {
        $client = $this->client($this->getResponse('1.2.3.17'));

        $client->country('1.2.3.17');
    }

    /**
     * @expectedException GeoIp2\Exception\OutOfQueriesException
     * @expectedExceptionMessage The license key you have provided is out of queries.
     */
    public function testOutOfQueriesException()
    {
        $client = $this->client($this->getResponse('1.2.3.18'));

        $client->country('1.2.3.18');
    }

    public function testParams()
    {
        $plugin = new MockPlugin();
        $plugin->addResponse($this->getResponse('1.2.3.4'));
        $guzzleClient = new GuzzleClient();
        $guzzleClient->addSubscriber($plugin);

        $client = new Client(
            42,
            'abcdef123456',
            array('en'),
            'geoip.maxmind.com',
            $guzzleClient
        );
        $client->country('1.2.3.4');

        $all_requests = $plugin->getReceivedRequests();
        $request = $all_requests[0];

        $this->assertEquals(
            'https://geoip.maxmind.com/geoip/v2.1/country/1.2.3.4',
            $request->getUrl(),
            'got expected URI for Country request'
        );
        $this->assertEquals(
            'GET',
            $request->getMethod(),
            'request is a GET'
        );

        $this->assertEquals(
            'application/json',
            $request->getHeader('Accept'),
            'request sets Accept header to application/json'
        );

        $this->assertStringMatchesFormat(
            'GeoIP2 PHP API (Guzzle%s)',
            $request->getHeader('User-Agent') . '',
            'request sets Accept header to application/json'
        );
    }


    private function client($response, $locales = array('en'))
    {
        $plugin = new MockPlugin();
        $plugin->addResponse($response);
        $guzzleClient = new GuzzleClient();
        $guzzleClient->addSubscriber($plugin);

        $client = new Client(
            42,
            'abcdef123456',
            $locales,
            'geoip.maxmind.com',
            $guzzleClient
        );

        return $client;
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

        return new Response($status, $headers, $body);
    }

    public function testTest()
    {
        $this->assertEquals(1, 1);
    }
}
