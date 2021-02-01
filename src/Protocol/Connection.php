<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Connection
{
    public function connect()
    {

    }

    public function query(string $query, array $arguments)
    {

    }

    public function queryOne(string $query, array $arguments)
    {

    }

    public function queryJson(string $query, array $arguments)
    {

    }

    public function queryJsonOne(string $query, array $arguments)
    {

    }

    public function execute(string $query)
    {

    }

    public function transaction(bool $isolated, bool $readOnly, bool $deferrable): Transaction
    {

    }
}
