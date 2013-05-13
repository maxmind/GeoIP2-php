<?php

namespace GeoIP2\Exception;

/**
 * This class represents an error returned by MaxMind's GeoIP2 Precision
 * web service.
 */
class WebServiceException extends HttpException
{
    /**
     * The code returned by the MaxMind web service
     */
    public $error;

    public function __construct(
        $message,
        $error,
        $httpStatus,
        $uri,
        Exception $previous = null
    ) {
        $this->error = $error;
        parent::__construct($message, $httpStatus, $uri, $previous);
    }
}
