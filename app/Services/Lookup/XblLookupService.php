<?php

namespace App\Services\Lookup;

use App\Contracts\LookupInterface;
use App\Traits\CachesApiResponses;
use GuzzleHttp\Client;

class XblLookupService implements LookupInterface
{
    use CachesApiResponses;

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
        $username = $params['username'] ?? null;
        $id = $params['id'] ?? null;

        if (!$username && !$id) {
            throw new \InvalidArgumentException("XBL lookup requires a username or ID.");
        }

        if ($username) {
            $cacheKey = "xbl_lookup_username_{$username}";
            $url = "https://ident.tebex.io/usernameservices/3/username/{$username}?type=username";
        } elseif ($id) {
            $cacheKey = "xbl_lookup_id_{$id}";
            $url = "https://ident.tebex.io/usernameservices/3/username/{$id}";
        }

        $data = $this->fetchJsonWithCache($cacheKey, $url);

        if (!isset($data->id, $data->username, $data->meta->avatar)) {
            throw new \UnexpectedValueException("Missing expected fields in XBL API response.");
        }

        return [
            'username' => $data->username,
            'id' => $data->id,
            'avatar' => $data->meta->avatar,
        ];
    }
}
