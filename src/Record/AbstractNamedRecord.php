<?php

declare(strict_types=1);

namespace GeoIp2\Record;

abstract class AbstractNamedRecord implements \JsonSerializable
{
    public readonly ?string $name;
    public readonly array $names;

    /**
     * @ignore
     */
    public function __construct(array $record, array $locales = ['en'])
    {
        $this->names = $record['names'] ?? [];

        foreach ($locales as $locale) {
            if (isset($this->names[$locale])) {
                $this->name = $this->names[$locale];

                return;
            }
        }
        $this->name = null;
    }

    public function jsonSerialize(): array
    {
        $js = [];
        if ($this->name !== null) {
            $js['name'] = $this->name;
        }
        if (!empty($this->names)) {
            $js['names'] = $this->names;
        }

        return $js;
    }
}
