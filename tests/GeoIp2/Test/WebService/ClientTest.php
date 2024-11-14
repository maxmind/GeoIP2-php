<?php

declare(strict_types=1);

namespace GeoIp2\Test\WebService;

use Composer\CaBundle\CaBundle;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Exception\AuthenticationException;
use GeoIp2\Exception\GeoIp2Exception;
use GeoIp2\Exception\HttpException;
use GeoIp2\Exception\OutOfQueriesException;
use GeoIp2\WebService\Client;
use MaxMind\WebService\Client as WsClient;
use MaxMind\WebService\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class ClientTest extends TestCase
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private $country = [
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
        'maxmind' => ['queries_remaining' => 11],
        'traits' => [
            'ip_address' => '1.2.3.4',
            'is_anycast' => true,
            'network' => '1.2.3.0/24',
        ],
    ];

    /**
     * @return list<array<string, mixed>>
     */
    private function getResponse(string $service, string $ipAddress): array
    {
        if ($service === 'Insights') {
            $insights = unserialize(serialize($this->country));
            $insights['traits']['static_ip_score'] = 1.3;
            $insights['traits']['user_count'] = 2;

            $responses = [
                '1.2.3.4' => $this->response(
                    'insights',
                    200,
                    $insights
                ),
            ];

            return $responses[$ipAddress];
        }

        $responses = [
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
            '1.2.3' => $this->response(
                'error',
                400,
                [
                    'code' => 'IP_ADDRESS_INVALID',
                    'error' => 'The value "1.2.3" is not a valid ip address',
                ]
            ),
            '1.2.3.7' => $this->response(
                'error',
                400
            ),
            '1.2.3.8' => $this->response(
                'error',
                400,
                ['weird' => 42]
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
                [
                    'code' => 'IP_ADDRESS_NOT_FOUND',
                    'error' => 'The address "1.2.3.13" is not in our database.',
                ]
            ),
            '1.2.3.14' => $this->response(
                'error',
                400,
                [
                    'code' => 'IP_ADDRESS_RESERVED',
                    'error' => 'The address "1.2.3.14" is a private address.',
                ]
            ),
            '1.2.3.15' => $this->response(
                'error',
                401,
                [
                    'code' => 'AUTHORIZATION_INVALID',
                    'error' => 'A user ID and license key are required to use this service',
                ]
            ),
            '1.2.3.16' => $this->response(
                'error',
                401,
                [
                    'code' => 'LICENSE_KEY_REQUIRED',
                    'error' => 'A license key is required to use this service',
                ]
            ),
            '1.2.3.17' => $this->response(
                'error',
                401,
                [
                    'code' => 'USER_ID_REQUIRED',
                    'error' => 'A user ID is required to use this service',
                ]
            ),
            '1.2.3.18' => $this->response(
                'error',
                402,
                [
                    'code' => 'OUT_OF_QUERIES',
                    'error' => 'The license key you have provided is out of queries.',
                ]
            ),
            '1.2.3.19' => $this->response(
                'error',
                401,
                [
                    'code' => 'ACCOUNT_ID_REQUIRED',
                    'error' => 'A account ID is required to use this service',
                ]
            ),
        ];

        return $responses[$ipAddress];
    }

    public function testCountry(): void
    {
        $country = $this->makeRequest('Country', '1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\Country', $country);

        $this->assertSame(
            42,
            $country->continent->geonameId,
            'continent geoname_id is 42'
        );

        $this->assertSame(
            'NA',
            $country->continent->code,
            'continent code is NA'
        );

        $this->assertSame(
            ['en' => 'North America'],
            $country->continent->names,
            'continent names'
        );

        $this->assertSame(
            'North America',
            $country->continent->name,
            'continent name is North America'
        );

        $this->assertSame(
            1,
            $country->country->geonameId,
            'country geoname_id is 1'
        );

        $this->assertFalse(
            $country->country->isInEuropeanUnion,
            'country is_in_european_union is false'
        );

        $this->assertSame(
            'US',
            $country->country->isoCode,
            'country iso_code is US'
        );

        $this->assertSame(
            ['en' => 'United States of America'],
            $country->country->names,
            'country names'
        );

        $this->assertSame(
            'United States of America',
            $country->country->name,
            'country name is United States of America'
        );

        $this->assertSame(
            11,
            $country->maxmind->queriesRemaining,
            'queriesRemaining is correct'
        );

        $this->assertFalse(
            $country->registeredCountry->isInEuropeanUnion,
            'registered_country is_in_european_union is false'
        );

        $this->assertTrue(
            $country->traits->isAnycast,
            'is_anycast'
        );

        $this->assertSame(
            '1.2.3.0/24',
            $country->traits->network,
            'network'
        );
    }

    public function testInsights(): void
    {
        $record = $this->makeRequest('Insights', '1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\Insights', $record);

        $this->assertSame(
            42,
            $record->continent->geonameId,
            'continent geoname_id is 42'
        );

        $this->assertTrue(
            $record->traits->isAnycast,
            'is_anycast'
        );

        $this->assertSame(
            '1.2.3.0/24',
            $record->traits->network,
            'network'
        );

        $this->assertSame(
            1.3,
            $record->traits->staticIpScore,
            'staticIPScore is 1.3'
        );

        $this->assertSame(
            2,
            $record->traits->userCount,
            'user_count is 2'
        );
    }

    public function testCity(): void
    {
        $city = $this->makeRequest('City', '1.2.3.4');

        $this->assertInstanceOf('GeoIp2\Model\City', $city);

        $this->assertSame(
            '1.2.3.0/24',
            $city->traits->network,
            'network'
        );
    }

    public function testMe(): void
    {
        $city = $this->makeRequest('City', 'me');

        $this->assertInstanceOf(
            'GeoIp2\Model\City',
            $city,
            'can set ip parameter to me'
        );
    }

    public function testNoBodyException(): void
    {
        $this->expectException(GeoIp2Exception::class);
        $this->expectExceptionMessage('Received a 200 response for GeoIP2 Country but did not receive a HTTP body.');

        $this->makeRequest('Country', '1.2.3.5');
    }

    public function testBadBodyException(): void
    {
        $this->expectException(GeoIp2Exception::class);
        $this->expectExceptionMessage('Received a 200 response for GeoIP2 Country but could not decode the response as JSON:');

        $this->makeRequest('Country', '2.2.3.5');
    }

    public function testInvalidIPException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value "1.2.3" is not a valid IP address');

        $this->makeRequest('Country', '1.2.3', callsToRequest: 0);
    }

    public function testNoErrorBodyIPException(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('with no body');

        $this->makeRequest('Country', '1.2.3.7');
    }

    public function testWeirdErrorBodyIPException(): void
    {
        $this->expectException(GeoIp2Exception::class);
        $this->expectExceptionMessage('Error response contains JSON but it does not specify code or error keys: {"weird":42}');

        $this->makeRequest('Country', '1.2.3.8');
    }

    public function testInvalidErrorBodyIPException(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('Received a 400 error for GeoIP2 Country but could not decode the response as JSON: Syntax error. Body: { invalid: }');

        $this->makeRequest('Country', '1.2.3.9');
    }

    public function test500PException(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Received a server error (500)');

        $this->makeRequest('Country', '1.2.3.10');
    }

    public function test3xxException(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(300);
        $this->expectExceptionMessage('Received an unexpected HTTP status (300) for GeoIP2 Country');

        $this->makeRequest('Country', '1.2.3.11');
    }

    public function test406Exception(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(406);
        $this->expectExceptionMessage('Received a 406 error for GeoIP2 Country with the following body: Cannot satisfy your Accept-Charset requirements');

        $this->makeRequest('Country', '1.2.3.12');
    }

    public function testAddressNotFoundException(): void
    {
        $this->expectException(AddressNotFoundException::class);
        $this->expectExceptionMessage('The address "1.2.3.13" is not in our database.');

        $this->makeRequest('Country', '1.2.3.13');
    }

    public function testAddressReservedException(): void
    {
        $this->expectException(AddressNotFoundException::class);
        $this->expectExceptionMessage('The address "1.2.3.14" is a private address.');

        $this->makeRequest('Country', '1.2.3.14');
    }

    public function testAuthorizationException(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('A user ID and license key are required to use this service');

        $this->makeRequest('Country', '1.2.3.15');
    }

    public function testMissingLicenseKeyException(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('A license key is required to use this service');

        $this->makeRequest('Country', '1.2.3.16');
    }

    public function testMissingUserIdException(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('A user ID is required to use this service');

        $this->makeRequest('Country', '1.2.3.17');
    }

    public function testMissingAccountIdException(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('A account ID is required to use this service');

        $this->makeRequest('Country', '1.2.3.19');
    }

    public function testOutOfQueriesException(): void
    {
        $this->expectException(OutOfQueriesException::class);
        $this->expectExceptionMessage('The license key you have provided is out of queries.');

        $this->makeRequest('Country', '1.2.3.18');
    }

    public function testParams(): void
    {
        $this->makeRequest(
            'Country',
            '1.2.3.4',
            ['en'],
            [
                'host' => 'api.maxmind.com',
                'timeout' => 27,
                'connectTimeout' => 72,
            ]
        );
    }

    // @phpstan-ignore-next-line
    private function response(
        ?string $endpoint,
        int $status,
        $body = null,
        $bad = null,
        ?string $contentType = null
    ): array {
        $headers = [];
        if ($contentType) {
            $headers['Content-Type'] = $contentType;
        } elseif ($status === 200 || ($status >= 400 && $status < 500)) {
            $headers['Content-Type'] = 'application/vnd.maxmind.com-'
                . $endpoint . '+json; charset=UTF-8; version=1.0;';
        }

        if ($bad) {
            $body = '{ invalid: }';
        } elseif (\is_array($body)) {
            $body = json_encode($body);
        }

        if ($body !== null) {
            $headers['Content-Length'] = \strlen($body);
        }

        return [$status, $headers, $body];
    }

    /**
     * @param list<string>         $locales
     * @param array<string, mixed> $options
     */
    private function makeRequest(
        string $service,
        string $ipAddress,
        array $locales = ['en'],
        array $options = [],
        int $callsToRequest = 1
    ): object {
        $accountId = 42;
        $licenseKey = 'abcdef123456';

        [$statusCode, $headers, $responseBody]
            = $this->getResponse($service, $ipAddress);

        $stub = $this->createMock(
            Request::class
        );
        $contentType = isset($headers['Content-Type'])
            ? $headers['Content-Type']
            : null;
        $stub->expects($this->exactly($callsToRequest))
            ->method('get')
            ->willReturn([$statusCode, $contentType, $responseBody]);
        $factory = $this->getMockBuilder(
            'MaxMind\WebService\Http\RequestFactory'
        )->getMock();
        $host = isset($options['host']) ? $options['host'] : 'geoip.maxmind.com';
        $url = 'https://' . $host . '/geoip/v2.1/' . strtolower($service)
            . '/' . $ipAddress;
        $headers = [
            'Authorization: Basic '
            . base64_encode($accountId . ':' . $licenseKey),
            'Accept: application/json',
        ];

        $curlVersion = curl_version();

        // On macOS, when the SSL version is "SecureTransport", the system's
        // keychain will be used.
        $caBundle = $curlVersion['ssl_version'] === 'SecureTransport' ?
          null : CaBundle::getSystemCaRootBundlePath();

        $curlVersion = curl_version();
        $factory->expects($this->exactly($callsToRequest))
            ->method('request')
            ->with(
                $this->equalTo($url),
                $this->equalTo(
                    [
                        'headers' => $headers,
                        'userAgent' => 'GeoIP2-API/' . Client::VERSION
                            . ' MaxMind-WS-API/' . WsClient::VERSION
                            . ' PHP/' . \PHP_VERSION
                            . ' curl/' . $curlVersion['version'],
                        'connectTimeout' => isset($options['connectTimeout'])
                            ? $options['connectTimeout'] : null,
                        'timeout' => isset($options['timeout'])
                            ? $options['timeout'] : null,
                        'proxy' => isset($options['proxy'])
                            ? $options['proxy'] : null,
                        'caBundle' => $caBundle,
                    ]
                )
            )->willReturn($stub);
        $options['httpRequestFactory'] = $factory;

        $method = strtolower($service);

        $client = new Client(
            $accountId,
            $licenseKey,
            $locales,
            $options
        );

        return $client->{$method}($ipAddress);
    }
}
