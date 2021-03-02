<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use EdgeDB\EdgeQLHttpClient;
use EdgeDB\Query\HttpResult;
use Psr\Cache\CacheItemPoolInterface;
use function array_key_exists;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class CachedEdgeQLHttpClient implements EdgeQLHttpClientInterface
{
    private EdgeQLHttpClient $client;
    private CacheItemPoolInterface $pool;

    public function __construct(
        EdgeQLHttpClient $client,
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
        if (!array_key_exists('cacheKey', $variables)) {
            unset($variables['cacheKey']);

            return $this->client->get($query, $variables);
        }

        $cacheKey = $variables['cacheKey'];

        if ($this->pool->hasItem($cacheKey)) {
            $item = $this->pool->getItem($cacheKey);

            return new HttpResult(
                $item->get()['data'],
                $item->get()['errors'] ?? []
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $query, array $variables = []): HttpResult
    {
    }
}
