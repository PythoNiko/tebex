<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

trait CachesApiResponses
{
    /**
     * Fetch JSON from external API and cache the response if not already cached.
     *
     * @param string $cacheKey Unique cache key.
     * @param string $url The API endpoint to fetch.
     * @param int $ttl Time to live for cache in seconds (600 = 10mins)
     * @return mixed Decoded JSON response.
     *
     * @throws \RuntimeException If the request or JSON decoding fails.
     */
    public function fetchJsonWithCache(string $cacheKey, string $url, int $ttl = 600): mixed
    {
        // check if API response already cached, else make new request via closure.
        return Cache::remember($cacheKey, $ttl, function () use ($url) {
            try {
                $response = $this->http->get($url);
                return json_decode($response->getBody()->getContents(), false);
            } catch (RequestException $e) {
                Log::error("HTTP request failed for URL {$url}: " . $e->getMessage());
                throw new \RuntimeException("External API call failed.");
            }
        });
    }
}
