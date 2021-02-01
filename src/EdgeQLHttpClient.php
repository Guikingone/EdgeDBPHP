<?php

declare(strict_types=1);

namespace EdgeDB;

use EdgeDB\Events\EdgeQLClientQueryEvent;
use EdgeDB\Query\EdgeQLHttpResult;
use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use function json_encode;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class EdgeQLHttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var ClientInterface|null
     */
    private $client;

    /**
     * @var RequestFactoryInterface|null
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactory;

    /**
     * @var EventDispatcherInterface|null
     */
    private $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

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
        try {
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

            $response = $this->client->sendRequest($request);

            $this->dispatch(new EdgeQLClientQueryEvent('POST', $query, $variables));
        } catch (Exception $exception) {
            $this->logCritical('An error occurred when trying to send the request', [
                'query' => $query,
                'variables' => $variables,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $this->logInfo('A query has succeed', [
            'query' => $query,
            'variables' => $variables,
            'method' => 'POST',
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return new EdgeQLHttpResult($body['data'] ?? [], $body['error'] ?? []);
    }

    private function dispatch(StoppableEventInterface $event): void
    {
        if (null === $this->eventDispatcher) {
            return;
        }

        $this->eventDispatcher->dispatch($event);
    }

    private function logInfo(string $message, array $context = []): void
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->info($message, $context);
    }

    private function logCritical(string $message, array $context = []): void
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->critical($message, $context);
    }
}
