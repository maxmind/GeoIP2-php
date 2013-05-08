<?php

namespace GeoIP2\Webservice;

use GeoIP2\Exception\GenericException;
use GeoIP2\Exception\HttpException;
use GeoIP2\Exception\WebserviceException;
use GeoIP2\Model\City;
use GeoIP2\Model\CityIspOrg;
use GeoIP2\Model\Country;
use GeoIP2\Model\Omni;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

class Client
{

  private $userId;
  private $licenseKey;
  private $languages;
  private $baseUri = 'https://geoip.maxmind.com/geoip/v2.0';
  private $guzzleClient;

  public function __construct($userId, $licenseKey, $languages=array('en'),
                              $guzzleClient = null)
  {
    $this->userId = $userId;
    $this->licenseKey = $licenseKey;
    $this->languages = $languages;
    // To enable unit testing
    $this->guzzleClient = $guzzleClient;
  }

  public function city($ipAddress = 'me')
  {
    return $this->responseFor('city', 'City', $ipAddress);
  }

  public function country($ipAddress = 'me')
  {
    return $this->responseFor('country', 'Country', $ipAddress);
  }

  public function cityIspOrg($ipAddress = 'me')
  {
    return $this->responseFor('city_isp_org', 'CityIspOrg', $ipAddress);
  }

  public function omni($ipAddress = 'me')
  {
    return $this->responseFor('omni', 'Omni', $ipAddress);
  }

  private function responseFor($path, $class, $ipAddress)
  {
    $uri = implode('/', array($this->baseUri, $path, $ipAddress));

    $client = $this->guzzleClient ? $this->guzzleClient : new GuzzleClient();
    $request = $client->get($uri, array('Accept' => 'application/json'));
    $request->setAuth($this->userId, $this->licenseKey);
    $ua = $request->getHeader('User-Agent');
    $ua = "GeoIP2 PHP API ($ua)";
    $request->setHeader('User-Agent', $ua);

    $response = null;
    try{
      $response = $request->send();
    }
    catch (ClientErrorResponseException $e) {
      $this->handle4xx($e->getResponse(), $uri); 
    }
    catch (ServerErrorResponseException $e) {
      $this->handle5xx($e->getResponse(), $uri); 
    }

    if ($response && $response->isSuccessful()) {
      $body = $this->handleSuccess($response, $uri);
      $class = "GeoIP2\\Model\\" . $class;
      return new $class($body, $this->languages);
    }
    else {
      $this->handleNon200($response, $uri);
    }
  }

  private function handleSuccess($response, $uri)
  {
    if ($response->getContentLength() == 0) {
      throw new GenericException("Received a 200 response for $uri but did not receive a HTTP body.");
    }

    try {
      return $response->json();
    }
    catch (RuntimeException $e) {
      throw new GenericException("Received a 200 response for $uri but could not decode the response as JSON: " . $e->getMessage());

    }
  }

  private function handle4xx($response, $uri)
  {
    $status = $response->getStatusCode();

    $body = array();

    if ( $response->getContentLength() > 0 ) {
      if( strstr($response->getContentType(), 'json')) {
        try {
          $body = $response->json();
          if (!isset($body['code']) || !isset($body['error']) ){
            throw new GenericException('Response contains JSON but it does not specify code or error keys: ' . $response->getBody());
          }
        }
        catch (RuntimeException $e){
          throw new HttpException("Received a $status error for $uri but it did not include the expected JSON body: " . $e->getMessage(), $status, $uri);
        }
      }
      else {
        throw new HttpException("Received a $status error for $uri with the following body: " . $response->getBody(),
                                $status, $uri);
      }
    }
    else {
      throw new HttpException("Received a $status error for $uri with no body", 
                              $status, $uri);
    }

    throw new WebserviceException($body['error'], $body['code'], $status, $uri);
  }

  private function handle5xx($response, $uri)
  {
    $status = $response->getStatusCode();

    throw new HttpException("Received a server error ($status) for $uri",
                            $status,$uri);
  }

  private function handleNon200($response, $uri)
  {
    $status = $response->getStatusCode();

    throw new HttpException("Received a very surprising HTTP status " .
                            "($status) for $uri",
                            $status, $uri);
  }
}
