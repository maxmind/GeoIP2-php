<?php

declare(strict_types=1);

namespace GeoIp2\Record;

/**
 * Contains data about your account.
 *
 * This record is returned by all location services and databases.
 *
 * @property-read int|null $queriesRemaining The number of remaining queries you
 * have for the service you are calling.
 */
class MaxMind implements \JsonSerializable
{
    public readonly ?int $queriesRemaining;

    public function __construct(array $record)
    {
        $this->queriesRemaining = $record['queries_remaining'] ?? null;
    }

    public function jsonSerialize(): array
    {
        return [
            'queries_remaining' => $this->queriesRemaining,
        ];
    }
}
