<?php

declare(strict_types=1);

namespace EdgeDB;

use Closure;
use EdgeDB\Client\AbstractHttpClient;
use EdgeDB\Client\EdgeQLHttpClientInterface;
use EdgeDB\Event\EdgeQLClientQueryEvent;
use EdgeDB\Exception\RuntimeException;
use EdgeDB\Query\HttpResult;
use Psr\Http\Message\RequestInterface;
use Throwable;
use function is_array;
use function json_decode;
use function json_encode;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class EdgeQLHttpClient extends AbstractHttpClient implements EdgeQLHttpClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function get(string $query, array $variables = []): HttpResult
    {
        return $this->sendRequest(function () use ($query, $variables): RequestInterface {
            $request = $this->requestFactory->createRequest('GET', $this->endpoint)
                ->withHeader('Accept', 'application/json')
            ;

            $request->getUri()->withQuery(sprintf('?query=%s', $query));

            if ([] !== $variables) {
                $request->getUri()->withQuery(sprintf('&variables=%s', json_encode($variables)));
            }

            return $request;
        }, $query, $variables, 'GET');
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $query, array $variables = []): HttpResult
    {
        return $this->sendRequest(function () use ($query, $variables): RequestInterface {
            $request = $this->requestFactory->createRequest('POST', $this->endpoint)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/json')
                ->withBody($this->streamFactory->createStream(json_encode([
                    'query' => $query,
                ])))
            ;

            if ([] !== $variables) {
                $request->withBody($this->streamFactory->createStream(json_encode([
                    'query' => $query,
                    'variables' => json_encode($variables),
                ])));
            }

            return $request;
        }, $query, $variables);
    }

    private function sendRequest(Closure $requestClosure, string $query, array $variables = [], string $method = 'POST'): HttpResult
    {
        try {
            $response = $this->client->sendRequest($requestClosure());

            $this->dispatch(new EdgeQLClientQueryEvent($method, $query, $variables));
        } catch (Throwable $exception) {
            $this->logger->critical('An error occurred when trying to send the request', [
                'query' => $query,
                'variables' => $variables,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $this->logger->info('A query has succeed', [
            'query' => $query,
            'variables' => $variables,
            'method' => $method,
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        if (!is_array($body)) {
            throw new RuntimeException('The response body cannot be parsed');
        }

        return new HttpResult($body['data'] ?? [], $body['error'] ?? []);
    }
}
