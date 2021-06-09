<?php

declare(strict_types=1);

namespace EdgeDB;

use ArrayIterator;
use EdgeDB\Client\ClientPoolInterface;
use EdgeDB\Client\EdgeQLHttpClientInterface;
use EdgeDB\Exception\RuntimeException;
use EdgeDB\Protocol\ClientInterface;
use function count;
use function is_bool;
use function next;
use function prev;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class EdgeQLHttpClientPool implements ClientPoolInterface
{
    /**
     * @var EdgeQLHttpClientInterface[]|ClientInterface[]
     */
    private array $clients;
    private bool $closed = false;

    /**
     * {@inheritdoc}
     */
    public function add(ClientInterface $client): void
    {
        $this->clients[] = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function previous(): EdgeQLHttpClientInterface
    {
        if ([] === $this->clients) {
            throw new RuntimeException('No clients found');
        }

        $nextClient = prev($this->clients);
        if (is_bool($nextClient)) {
            throw new RuntimeException('The previous available client cannot be found');
        }

        return $nextClient;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): EdgeQLHttpClientInterface
    {
        if ([] === $this->clients) {
            throw new RuntimeException('No clients found');
        }

        $nextClient = next($this->clients);
        if (is_bool($nextClient)) {
            throw new RuntimeException('The next available client cannot be found');
        }

        return $nextClient;
    }

    /**
     * {@inheritdoc}
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->clients);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->clients);
    }
}
