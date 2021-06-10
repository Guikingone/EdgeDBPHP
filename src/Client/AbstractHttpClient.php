<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use EdgeDB\Protocol\Credentials;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class AbstractHttpClient
{
    protected string $endpoint;
    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;
    protected ClientInterface $client;
    protected LoggerInterface $logger;

    private Credentials $credentials;
    private ?EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?LoggerInterface $logger = null,
        ?EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->client = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->logger = $logger ?? new NullLogger();
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function connect(string $credentialsPath, string $host, ?string $format = 'edgeql'): self
    {
        $self = new static();
        $self->credentials = Credentials::load($credentialsPath);
        $self->endpoint = sprintf('http://%s:%d/db/%s/%s', $host, $self->credentials->getPort(), $self->credentials->getDatabase(), $format);

        return $self;
    }

    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    protected function dispatch(StoppableEventInterface $event): void
    {
        if (null === $this->eventDispatcher) {
            return;
        }

        $this->eventDispatcher->dispatch($event);
    }
}
