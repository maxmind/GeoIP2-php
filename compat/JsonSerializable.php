<?php

/**
  * This interface exists to provide backwards compatibility with PHP 5.3
  */
interface JsonSerializable
{
    /**
     * Returns data that can be serialized by json_encode
     *
     */
    public function jsonSerialize();
}
