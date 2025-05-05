<?php

namespace App\Services\Lookup;

use App\Contracts\LookupInterface;
use App\Traits\CachesApiResponses;
use GuzzleHttp\Client;

class SteamLookupService implements LookupInterface
{
    use CachesApiResponses;

    protected Client $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function supports(string $type): bool
    {
        return $type === 'steam';
    }

    public function lookup(array $params): array
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            throw new \InvalidArgumentException("Steam lookup requires an ID.");
        }

        $cacheKey = "steam_lookup_id_{$id}";
        $url = "https://ident.tebex.io/usernameservices/4/username/{$id}";

        $data = $this->fetchJsonWithCache($cacheKey, $url);

        if (!isset($data->id, $data->username, $data->meta->avatar)) {
            throw new \UnexpectedValueException("Missing expected fields in Steam API response.");
        }

        return [
            'username' => $data->username,
            'id' => $data->id,
            'avatar' => $data->meta->avatar,
        ];
    }
}
