<?php

declare(strict_types=1);

namespace GeoIp2\Record;

/**
 * Contains data for the postal record associated with an IP address.
 *
 * This record is returned by all location databases and services besides
 * Country.
 *
 * @property-read string|null $code The postal code of the location. Postal codes
 * are not available for all countries. In some countries, this will only
 * contain part of the postal code. This attribute is returned by all location
 * databases and services besides Country.
 * @property-read int|null $confidence A value from 0-100 indicating MaxMind's
 * confidence that the postal code is correct. This attribute is only
 * available from the Insights service and the GeoIP2 Enterprise
 * database.
 */
class Postal implements \JsonSerializable
{
    public readonly ?string $code;
    public readonly ?int $confidence;

    /**
     * @ignore
     */
    public function __construct(array $record)
    {
        $this->code = $record['code'] ?? null;
        $this->confidence = $record['confidence'] ?? null;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'confidence' => $this->confidence,
        ];
    }
}
