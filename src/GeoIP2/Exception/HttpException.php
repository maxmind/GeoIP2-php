<?php

namespace GeoIP2\Exception;

/**
 *  This class represents an HTTP transport error.
 */

class HttpException extends GenericException
{
    /**
     * The URI queried
     */
    public $uri;

    public function __construct(
        $message,
        $httpStatus,
        $uri,
        Exception $previous = null
    ) {
        $this->uri = $uri;
        parent::__construct($message, $httpStatus, $previous);
    }
}
