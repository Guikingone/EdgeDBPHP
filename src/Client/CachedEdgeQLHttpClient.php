<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use Closure;
use EdgeDB\Query\HttpResult;
use Psr\Cache\CacheItemPoolInterface;
use function array_key_exists;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class CachedEdgeQLHttpClient implements EdgeQLHttpClientInterface
{
    private EdgeQLHttpClientInterface $client;
    private CacheItemPoolInterface $pool;

    public function __construct(
        EdgeQLHttpClientInterface $client,
        CacheItemPoolInterface $pool
    ) {
        $this->client = $client;
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $query, array $variables = []): HttpResult
    {
        return $this->handleRequest(
            fn ($query, $variables): HttpResult => $this->client->get($query, $variables),
            $query,
            $variables
        );
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $query, array $variables = []): HttpResult
    {
        return $this->handleRequest(
            fn ($query, $variables): HttpResult => $this->client->post($query, $variables),
            $query,
            $variables
        );
    }

    private function handleRequest(Closure $requestFunc, string $query, array $variables): HttpResult
    {
        if (!array_key_exists('cacheKey', $variables)) {
            return $requestFunc($query, $variables);
        }

        $cacheKey = $variables['cacheKey'];
        unset($variables['cacheKey']);

        if ($this->pool->hasItem($cacheKey)) {
            $item = $this->pool->getItem($cacheKey);

            return new HttpResult($item->get()['data'], $item->get()['errors'] ?? []);
        }

        $result = $requestFunc($query, $variables);

        $item = $this->pool->getItem($cacheKey);
        $item->set($result->toArray());
        $this->pool->save($item);

        return $result;
    }
}
