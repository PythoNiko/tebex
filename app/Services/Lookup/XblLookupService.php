<?php

namespace App\Services\Lookup;

use App\Contracts\LookupInterface;
use GuzzleHttp\Client;

class XblLookupService implements LookupInterface
{
    protected Client $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function supports(string $type): bool
    {
        return $type === 'xbl';
    }

    public function lookup(array $params): array
    {
        $id = $params['id'] ?? null;
        $username = $params['username'] ?? null;

        if (!$id && !$username) {
            throw new \InvalidArgumentException("Xbox Live lookup requires a username or ID.");
        }

        $url = $username
            ? "https://ident.tebex.io/usernameservices/3/username/{$username}?type=username"
            : "https://ident.tebex.io/usernameservices/3/username/{$id}";

        $response = $this->http->get($url);
        $data = json_decode($response->getBody()->getContents());

        return [
            'username' => $data->username,
            'id' => $data->id,
            'avatar' => $data->meta->avatar,
        ];
    }
}
