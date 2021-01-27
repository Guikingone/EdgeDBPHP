<?php

declare(strict_types=1);

namespace Tests\EdgeDB;

use EdgeDB\Client;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ClientTest extends TestCase
{
    /**
     * @dataProvider provideDsn
     */
    public function testClientCanBeCreated(string $dsn): void
    {
        $client = Client::connect($dsn);

        self::assertNotEmpty($client->getOptions());
    }

    public function provideDsn(): \Generator
    {
        yield 'EdgeDb' => ['edgedb://edgedb@localhost/foo'];
        yield 'EdgeDb with password' => ['edgedb://edgedb:root@localhost/foo'];
        yield 'Named instance' => ['edgedb://edgedb@localhost/bar'];
    }
}
