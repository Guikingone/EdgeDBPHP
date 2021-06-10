<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use EdgeDB\GraphQLHttpClient;
use EdgeDB\Query\HttpResult;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class CachedGraphQLHttpClient implements GraphQLHttpClientInterface
{
    private GraphQLHttpClient $client;
    private CacheItemPoolInterface $pool;

    public function __construct(
        GraphQLHttpClient $client,
        CacheItemPoolInterface $pool
    ) {
        $this->client = $client;
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $query, ?string $operationName = null, ?array $options = []): HttpResult
    {
        // TODO: Implement get() method.
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $query, ?string $operationName = null, ?array $options = []): HttpResult
    {
        // TODO: Implement post() method.
    }
}
