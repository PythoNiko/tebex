<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use JsonException;

trait CachesApiResponses
{
    public function fetchJsonWithCache(string $cacheKey, string $url, int $ttl = 600): mixed
    {
        return Cache::remember($cacheKey, $ttl, function () use ($url) {
            try {
                $response = $this->http->get($url);

                try {
                    return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $e) {
                    Log::error("JSON decode failed for URL {$url}: " . $e->getMessage());
                    throw new \RuntimeException("Failed to decode API response.");
                }
            } catch (RequestException $e) {
                Log::error("HTTP request failed for URL {$url}: " . $e->getMessage());
                throw new \RuntimeException("External API call failed.");
            }
        });
    }
}
