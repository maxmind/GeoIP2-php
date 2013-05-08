<?php

namespace GeoIP2\Exception;

class HttpException extends \Exception
{
  public $code;

  public function __construct($message, $code, $uri,
                              Exception $previous = null)
  {
    $this->code = $code;
    parent::__construct($message, null, $previous);
  }

}