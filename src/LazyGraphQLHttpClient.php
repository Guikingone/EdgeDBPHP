<?php

declare(strict_types=1);

namespace EdgeDB;

use EdgeDB\Client\GraphQLHttpClientInterface;
use EdgeDB\Query\HttpResult;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class LazyGraphQLHttpClient implements GraphQLHttpClientInterface
{
    private GraphQLHttpClientInterface $sourceClient;
    private GraphQLHttpClientInterface $client;
    private bool $initialized = false;

    public function __construct(GraphQLHttpClientInterface $sourceClient)
    {
        $this->sourceClient = $sourceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $query, ?string $operationName = null, ?array $options = []): HttpResult
    {
        $this->initialize();

        return $this->client->get($query, $operationName, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $query, ?string $operationName = null, ?array $options = []): HttpResult
    {
        $this->initialize();

        return $this->client->post($query, $operationName, $options);
    }


    private function initialize(): void
    {
        if ($this->initialized) {
            return;
        }

        $this->client = $this->sourceClient;
        $this->initialized = true;
    }
}
