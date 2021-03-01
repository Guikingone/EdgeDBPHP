<?php

declare(strict_types=1);

namespace Tests\EdgeDB\Protocol;

use EdgeDB\Exception\InvalidArgumentException;
use EdgeDB\Exception\RuntimeException;
use EdgeDB\Protocol\Credentials;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class CredentialsTest extends TestCase
{
    public function testCredentialsCannotBeLoadedFromInvalidFile(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('The credentials file does not exist');
        self::expectExceptionCode(0);
        Credentials::load(__DIR__.'/foo.json');
    }

    public function testCredentialsCannotBeLoadedWithInvalidZeroPort(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('The port is not valid');
        self::expectExceptionCode(0);
        Credentials::load(__DIR__.'/assets/edgedb_invalid_zero_port.json');
    }

    public function testCredentialsCannotBeLoadedWithInvalidHighPort(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('The port is not valid');
        self::expectExceptionCode(0);
        Credentials::load(__DIR__.'/assets/edgedb_invalid_high_port.json');
    }

    public function testCredentialsCannotBeLoadedWithInvalidPortType(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('The port is not valid');
        self::expectExceptionCode(0);
        Credentials::load(__DIR__.'/assets/edgedb_invalid_type_port.json');
    }

    public function testCredentialsCanBeLoadedWithoutPort(): void
    {
        $credentials = Credentials::load(__DIR__.'/assets/edgedb_undefined_port.json');

        self::assertSame('edgedb', $credentials->getUsername());
        self::assertSame('edgedb', $credentials->getPassword());
        self::assertSame(5656, $credentials->getPort());
        self::assertSame('foo', $credentials->getDatabase());
    }
}
