<?php

namespace App\Services\Lookup;

use App\Contracts\LookupInterface;
use GuzzleHttp\Client;

class SteamLookupService implements LookupInterface
{
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
        if (!empty($params['username'])) {
            throw new \InvalidArgumentException("Steam only supports ID-based lookups.");
        }

        $id = $params['id'] ?? null;
        if (!$id) {
            throw new \InvalidArgumentException("Steam ID is required.");
        }

        $response = $this->http->get("https://ident.tebex.io/usernameservices/4/username/{$id}");
        $data = json_decode($response->getBody()->getContents());

        return [
            'username' => $data->username,
            'id' => $data->id,
            'avatar' => $data->meta->avatar,
        ];
    }
}
