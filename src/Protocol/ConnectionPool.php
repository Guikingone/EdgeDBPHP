<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use Countable;
use function count;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ConnectionPool implements Countable
{
    /**
     * @var Connection[]
     */
    private $connections;

    public function add(Connection $connection): void
    {
        $this->connections[] = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->connections);
    }
}
