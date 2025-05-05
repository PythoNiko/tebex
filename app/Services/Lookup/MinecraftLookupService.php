<?php

namespace App\Services\Lookup;

use App\Contracts\LookupInterface;
use GuzzleHttp\Client;

class MinecraftLookupService implements LookupInterface
{
    protected Client $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function supports(string $type): bool
    {
        return $type === 'minecraft';
    }

    public function lookup(array $params): array
    {
        if (!empty($params['username'])) {
            $response = $this->http->get("https://api.mojang.com/users/profiles/minecraft/{$params['username']}");
        } elseif (!empty($params['id'])) {
            $response = $this->http->get("https://sessionserver.mojang.com/session/minecraft/profile/{$params['id']}");
        } else {
            throw new \InvalidArgumentException("Username or ID is required for Minecraft lookup.");
        }

        $data = json_decode($response->getBody()->getContents());

        return [
            'username' => $data->name,
            'id' => $data->id,
            'avatar' => "https://crafatar.com/avatars/{$data->id}",
        ];
    }
}


