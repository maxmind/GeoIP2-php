<?php

declare(strict_types=1);

namespace GeoIp2\Record;

/**
 * Contains data for one type of anonymizer detection, currently residential
 * proxies. Additional anonymizer types may be added in the future.
 *
 * This record is returned by the GeoIP Insights web service.
 */
class AnonymizerFeed implements \JsonSerializable
{
    /**
     * @var int|null A score ranging from 1 to 99 that represents our percent confidence
     *               that the network is currently part of this anonymizer feed. This
     *               attribute is only available from the GeoIP Insights web service.
     */
    public readonly ?int $confidence;

    /**
     * @var string|null The last day that the network was sighted in our analysis of this
     *                  anonymizer feed, in YYYY-MM-DD format. This attribute is only
     *                  available from the GeoIP Insights web service.
     */
    public readonly ?string $networkLastSeen;

    /**
     * @var string|null The name of the provider associated with the network in this
     *                  anonymizer feed. This attribute is only available from the GeoIP
     *                  Insights web service.
     */
    public readonly ?string $providerName;

    /**
     * @ignore
     *
     * @param array<string, mixed> $record
     */
    public function __construct(array $record)
    {
        $this->confidence = $record['confidence'] ?? null;
        $this->networkLastSeen = $record['network_last_seen'] ?? null;
        $this->providerName = $record['provider_name'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->confidence !== null) {
            $js['confidence'] = $this->confidence;
        }
        if ($this->networkLastSeen !== null) {
            $js['network_last_seen'] = $this->networkLastSeen;
        }
        if ($this->providerName !== null) {
            $js['provider_name'] = $this->providerName;
        }

        return $js;
    }
}
