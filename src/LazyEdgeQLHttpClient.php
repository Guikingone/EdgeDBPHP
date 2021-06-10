<?php

declare(strict_types=1);

namespace EdgeDB;

use EdgeDB\Client\EdgeQLHttpClientInterface;
use EdgeDB\Query\HttpResult;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class LazyEdgeQLHttpClient implements EdgeQLHttpClientInterface
{
    private EdgeQLHttpClientInterface $sourceClient;
    private EdgeQLHttpClientInterface $client;
    private bool $initialized = false;

    public function __construct(EdgeQLHttpClientInterface $sourceClient)
    {
        $this->sourceClient = $sourceClient;
    }

    /**
     * {@inheritoc}
     */
    public function get(string $query, array $variables = []): HttpResult
    {
        $this->initialize();

        return $this->client->get($query, $variables);
    }

    /**
     * {@inheritoc}
     */
    public function post(string $query, array $variables = []): HttpResult
    {
        $this->initialize();

        return $this->client->post($query, $variables);
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
