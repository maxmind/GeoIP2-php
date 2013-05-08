<?php

namespace GeoIP2\Webservice;

use GeoIP2\Error\Generic;
use GeoIP2\Exception\HttpException;
use GeoIP2\Error\Webservice;
use GeoIP2\Model\City;
use GeoIP2\Model\CityISPOrg;
use GeoIP2\Model\Country;
use GeoIP2\Model\Omni;
use Guzzle\Http\Client as GuzzleClient;

class Client
{

  private $userId;
  private $licenseKey;
  private $languages;
  private $baseUri = 'https://geoip.maxmind.com/geoip/v2.0';

  public function __construct($userId, $licenseKey, $languages=array('en'))
  {
    $this->userId = $userId;
    $this->licenseKey = $licenseKey;
    $this->languages = $languages;
  }

  public function city($ipAddress = 'me')
  {
    return $this->responseFor('city', 'City', $ipAddress);
  }

  public function country($ipAddress = 'me')
  {
    return $this->responseFor('country', 'Country', $ipAddress);
  }

  public function cityISPOrg($ipAddress = 'me')
  {
    return $this->responseFor('city_isp_org', 'CityISPOrg', $ipAddress);
  }

  public function omni($ipAddress = 'me')
  {
    return $this->responseFor('omni', 'Omni', $ipAddress);
  }

  private function responseFor($path, $class, $ipAddress)
  {
    $uri = implode('/', array($this->baseUri, $path, $ipAddress));

    $client = new GuzzleClient();
    $request = $client->get($uri, array('Accept' => 'application/json'));
    $request->setAuth($this->userId, $this->licenseKey);
    $ua = $request->getHeader('User-Agent');
    $ua = "GeoIP2 PHP API ($ua)";
    $request->setHeader('User-Agent', $ua);

    $response = $request->send();

    if ($response->isSuccessful()) {
      $body = $this->handleSuccess($response, $uri);
      $class = "GeoIP2\\Model\\" . $class;
      return new $class($body, $this->languages);
    }
  }

  private function handleSuccess($response, $uri)
  {
    // XXX - handle exceptions
    try {
      return $response->json();
    }
    // XXX - figure out what sort of exception to catch
    catch (Exception $e) {
      throw new GenericException("Received a 200 response for $uri but could not decode the response as JSON: " . $e->getMessage());

    }
  }

  private function handleError($response, $uri)
  {
    $status = $response->getStatusCode();

    if ($status >= 400 && $status <= 499) {
      $this->handle4xx($response, $uri);
    }
    elseif ($status >= 500 && $status <= 599){
      $this->handle5xx($response, $uri);
    }
    else {
      $this->hanldeNon200($reponse, $uri);
    }
  }

  private function handle4xx($response, $uri)
  {
    if ( $response->getContentLength() > 0 ) {
      if( strstr($response->getContentType(), 'json')) {
        try {
          $body = $response->json();
          if (!$body['code'] || $body['error'] ){
            throw new GenericException('Response contains JSON but it does not specify code or error keys');
          }
        }
        // XXX - don't catch all exceptions
        catch (Exception $e){
          throw new HttpException("Received a $status error for $uri but it did not include the expected JSON body: " . $e->getMessage(), $status, $uri);
        }
      }
      else {
        throw new HttpException("Received a $status error for $uri with the following body: $content",
                                $status, $uri);
      }
    }
    else {
      throw new HttpException("Received a $status error for $uri with no body", 
                              $status, $uri);
    }

    throw new WebserviceException($body['error'], $status, $uri);
  }

  private function handle5xx($response, $uri)
  {
    throw new HttpException("Received a server error ($status) for $uri",
                            $status,$uri);
  }

  private function handleNon200($response, $uri)
  {
    throw new HttpException("Received a very surprising HTTP status " .
                            "($status) for $uri",
                            $status, $uri);
  }
}
