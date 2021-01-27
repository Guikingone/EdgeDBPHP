<?php

declare(strict_types=1);

namespace EdgeDB;

use EdgeDB\Events\EdgeQLClientEvent;
use EdgeDB\Events\PostQuerySent;
use Exception;
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
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger ?: new NullLogger();
    }

    public function post(string $query, array $variables = [])
    {
        try {
            $request = $this->requestFactory->createRequest('POST', $this->endpoint);
            $request->withHeader('Content-Type', 'application/json');
            $request->withHeader('Accept', 'application/json');
            $request->withBody($this->streamFactory->createStream(json_encode([
                'query' => $query,
                'variables' => $variables,
            ])));

            $response = $this->client->sendRequest($request);
        } catch (Exception $exception) {
            $this->logCritical('An error occurred when trying to send the request', [
                'query' => $query,
                'variables' => $variables,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $this->dispatch(new EdgeQLClientEvent('POST', $query, $variables));
    }

    private function dispatch(StoppableEventInterface $event): void
    {
        if (null === $this->eventDispatcher) {
            return;
        }

        $this->eventDispatcher->dispatch($event);
    }

    private function logCritical(string $message, array $context = []): void
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->critical($message, $context);
    }
}
