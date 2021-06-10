<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use Countable;
use EdgeDB\Protocol\ClientInterface;
use IteratorAggregate;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ClientPoolInterface extends Countable, IteratorAggregate
{
    /**
     * Add a new @param ClientInterface $client in the pool
     */
    public function add(ClientInterface $client): void;

    /**
     * Return the previous available client.
     */
    public function previous(): ClientInterface;

    /**
     * Return the next available client.
     */
    public function next(): ClientInterface;

    /**
     * Define if the pool is closed.
     */
    public function isClosed(): bool;
}
