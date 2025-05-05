<?php

namespace App\Services;

class LookupService
{
    protected array $platform;

    public function __construct(array $platform)
    {
        $this->platform = $platform;
    }

    public function lookup(string $type, array $params): array
    {
        foreach ($this->platform as $platform) {
            if ($platform->supports($type)) {
                return $platform->lookup($params);
            }
        }

        throw new \RuntimeException("No lookup platform found for type: {$type}");
    }
}
