<?php

declare(strict_types=1);

namespace Tests\EdgeDB\Protocol;

use EdgeDB\Protocol\BlockingClient;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class BlockingClientTest extends TestCase
{
    /**
     * @dataProvider provideDsn
     */
    public function testClientCanBeCreated(string $dsn): void
    {
        $client = BlockingClient::connect($dsn);

        self::assertNotEmpty($client->getOptions());
        self::assertArrayHasKey('host', $client->getOptions());
        self::assertArrayHasKey('user', $client->getOptions());
        self::assertArrayHasKey('password', $client->getOptions());
    }

    public function provideDsn(): Generator
    {
        yield 'EdgeDb' => ['edgedb://edgedb@localhost/foo'];
        yield 'EdgeDb with password' => ['edgedb://edgedb:root@localhost/foo'];
        yield 'Named instance' => ['edgedb://edgedb@localhost/bar'];
    }
}
