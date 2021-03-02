<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\Administration;

use EdgeDB\EdgeQL\Administration\Database;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class DatabaseTest extends TestCase
{
    public function testDatabaseCanBeCreated(): void
    {
        self::assertSame('CREATE DATABASE foo', Database::create('foo'));
    }

    public function testDatabaseCanBeDropped(): void
    {
        self::assertSame('DROP DATABASE foo', Database::drop('foo'));
    }
}
