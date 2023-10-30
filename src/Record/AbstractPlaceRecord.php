<?php

declare(strict_types=1);

namespace GeoIp2\Record;

abstract class AbstractPlaceRecord extends AbstractNamedRecord
{
    public readonly ?int $confidence;
    public readonly ?int $geonameId;

    /**
     * @ignore
     */
    public function __construct(array $record, array $locales = ['en'])
    {
        parent::__construct($record, $locales);

        $this->confidence = $record['confidence'] ?? null;
        $this->geonameId = $record['geoname_id'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();
        if ($this->confidence !== null) {
            $js['confidence'] = $this->confidence;
        }

        if ($this->geonameId !== null) {
            $js['geoname_id'] = $this->geonameId;
        }

        return $js;
    }
}
