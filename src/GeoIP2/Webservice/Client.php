<?php

namespace GeoIP2\Webservice;

use GeoIP2\Error\Generic;
use GeoIP2\Error\HTTP;
use GeoIP2\Error\Webservice;
use GeoIP2\Model\City;
use GeoIP2\Model\CityISPOrg;
use GeoIP2\Model\Country;
use GeoIP2\Model\Omni;
use Guzzle\Http\Client as GuzzleClient;

class Client
{

  private $user_id;
  private $license_key;
  private $base_uri = 'https://geoip.maxmind.com/geoip/v2.0';

  function __construct($user_id, $license_key)
  {
    $this->user_id = $user_id;
    $this->license_key = $license_key;
  }

  public function city($ip_address = 'me')
  {
    return $this->response_for('city', $ip_address);
  }

  public function country($ip_address = 'me')
  {
    return $this->response_for('country', $ip_address);
  }

  public function cityISPOrg($ip_address = 'me')
  {
    return $this->response_for('city_isp_org', $ip_address);
  }

  public function omni($ip_address = 'me')
  {
    return $this->response_for('omni', $ip_address);
  }

  private function response_for($path, $ip_address)
  {
    $uri = implode('/', array($this->base_uri, $path, $ip_address));
    $client = new GuzzleClient();
    $request = $client->get($uri, array('Accept' => 'application/json'));
    $request->setAuth($this->user_id, $this->license_key);
    $response = $request->send();
    echo $response->getBody();
  }

  private function handle_success($response, $uri)
  {
  }

  private function handle_error($response, $uri)
  {
  }

  private function handle_4xx($response, $uri)
  {
  }

  private function handle_5xx($response, $uri)
  {
  }

}