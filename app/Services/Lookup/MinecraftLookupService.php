<?php

namespace App\Services\Lookup;

use App\Contracts\LookupInterface;
use App\Traits\CachesApiResponses;
use GuzzleHttp\Client;

class MinecraftLookupService implements LookupInterface
{
    use CachesApiResponses;

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
        $username = $params['username'] ?? null;
        $id = $params['id'] ?? null;

        if ($username) {
            $cacheKey = "minecraft_lookup_username_{$username}";
            $url = "https://api.mojang.com/users/profiles/minecraft/{$username}";
        } elseif ($id) {
            $cacheKey = "minecraft_lookup_id_{$id}";
            $url = "https://sessionserver.mojang.com/session/minecraft/profile/{$id}";
        } else {
            throw new \InvalidArgumentException("Username or ID is required for Minecraft lookup.");
        }

        $data = $this->fetchJsonWithCache($cacheKey, $url);

        if (!isset($data->id, $data->name)) {
            throw new \UnexpectedValueException("Missing expected fields in API response.");
        }

        return [
            'username' => $data->name,
            'id' => $data->id,
            'avatar' => "https://crafatar.com/avatars/{$data->id}",
        ];
    }
}
