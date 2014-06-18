<?php

namespace GeoIp2\Model;


/**
 * @ignore
 */
abstract class AbstractModel implements \JsonSerializable
{
    protected $raw;

    /**
     * @ignore
     */
    public function __construct($raw)
    {
        $this->raw = $raw;
    }

    /**
     * @ignore
     */
    protected function get($field)
    {
        return isset($this->raw[$field]) ? $this->raw[$field] : null;
    }

    /**
     * @ignore
     */
    public function __get($attr)
    {
        if ($attr != "instance" && property_exists($this, $attr)) {
            return $this->$attr;
        }

        throw new \RuntimeException("Unknown attribute: $attr");
    }

    /**
     * @ignore
     */
    public function __isset($attr)
    {
        return $attr != "instance" && isset($this->$attr);
    }

    public function jsonSerialize()
    {
        return $this->raw;
    }
}
