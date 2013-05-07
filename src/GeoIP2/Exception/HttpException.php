<?php

namespace GeoIP2\Exception;

class HttpException extends \Exception
{

  public function __construct($message, $code, $uri,
                              Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }

}