<?php

declare(strict_types=1);

namespace EdgeDB;

use Closure;
use EdgeDB\Events\EdgeQLClientQueryEvent;
use EdgeDB\Exception\ClientRuntimeException;
use EdgeDB\Query\EdgeQLHttpResult;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;
use function is_array;
use function json_decode;
use function json_encode;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class EdgeQLHttpClient implements HttpClientInterface
{
    private string $endpoint;
    private ?ClientInterface $client;
    private ?RequestFactoryInterface $requestFactory;
    private ?StreamFactoryInterface $streamFactory;
    private ?EventDispatcherInterface $eventDispatcher;
    private LoggerInterface $logger;

    public function __construct(
        string $endpoint,
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?EventDispatcherInterface $eventDispatcher = null,
        ?LoggerInterface $logger = null
    ) {
        $this->endpoint = $endpoint;
        $this->client = $client ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger ?: new NullLogger();
    }

    public function post(string $query, array $variables = []): EdgeQLHttpResult
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
                $request = $request->withBody($this->streamFactory->createStream(json_encode([
                    'query' => $query,
                    'variables' => json_encode($variables),
                ])));
            }

            return $request;
        }, $query, $variables);
    }

    public function get(string $query, array $variables = []): EdgeQLHttpResult
    {
        return $this->sendRequest(function () use ($query, $variables): RequestInterface {
            $request = $this->requestFactory->createRequest('GET', $this->endpoint)
                ->withHeader('Accept', 'application/json')
            ;

            $request->getUri()->withQuery(sprintf('?query=%s', $query));

            if ([] !== $variables) {
                $request = $request->getUri()->withQuery(sprintf('&variables=%s', json_encode($variables)));
            }

            return $request;
        }, $query, $variables, 'GET');
    }

    private function sendRequest(Closure $requestClosure, string $query, array $variables = [], string $method = 'POST'): EdgeQLHttpResult
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
            throw new ClientRuntimeException('The response body cannot be parsed');
        }

        return new EdgeQLHttpResult($body['data'] ?? [], $body['error'] ?? []);
    }

    private function dispatch(StoppableEventInterface $event): void
    {
        if (null === $this->eventDispatcher) {
            return;
        }

        $this->eventDispatcher->dispatch($event);
    }
}
