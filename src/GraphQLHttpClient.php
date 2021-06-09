<?php

declare(strict_types=1);

namespace EdgeDB;

use Closure;
use EdgeDB\Client\AbstractHttpClient;
use EdgeDB\Client\GraphQLHttpClientInterface;
use EdgeDB\Query\HttpResult;
use Psr\Http\Message\RequestInterface;
use Throwable;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class GraphQLHttpClient extends AbstractHttpClient implements GraphQLHttpClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function get(string $query, ?string $operationName = null, ?array $options = []): HttpResult
    {
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $query, ?string $operationName = null, ?array $options = []): HttpResult
    {
        return $this->sendRequest(function () use ($query, $operationName, $options): RequestInterface {
            $request = $this->requestFactory->createRequest('POST', $this->getEndpoint())
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/json')
                ->withBody($this->streamFactory->createStream(json_encode([
                    'query' => $query,
                ])))
            ;

            return $request;
        }, $query, $operationName, $options);
    }

    private function sendRequest(Closure $requestClosure, string $query, ?string $operationName = null, ?array $options = []): HttpResult
    {
        try {
            $response = $this->client->sendRequest($requestClosure());
        } catch (Throwable $throwable) {
        }
    }
}
