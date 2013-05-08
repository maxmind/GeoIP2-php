<?php

namespace GeoIP2\Exception;

class WebserviceException extends HttpException
{
  public $httpStatus;

  public function __construct($message, $code, $httpStatus, $uri,
                              Exception $previous = null)
  {
    $this->httpStatus = $httpStatus;
    parent::__construct($message, $code, $uri, $previous);
  }

}