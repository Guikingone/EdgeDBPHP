<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Migration;
use EdgeDB\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class MigrationTest extends TestCase
{
    public function testMigrationCanBeStarted(): void
    {
    }

    public function testMigrationCannotBeCreatedWithInvalidStatement(): void
    {
    }

    public function testMigrationCanBeCreated(): void
    {
    }

    public function testMigrationCanBeAborted(): void
    {
        self::assertSame('ABORT MIGRATION', Migration::abort());
    }

    public function testMigrationCanBePopulated(): void
    {
        self::assertSame('POPULATE MIGRATION', Migration::populate());
    }

    public function testCurrentMigrationCannotBeDescribedWithInvalidType(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('The format "foo" is not supported');
        self::expectExceptionCode(0);
        Migration::describeCurrent('foo');
    }

    public function testCurrentMigrationCanBeDescribed(): void
    {
        self::assertSame('DESCRIBE CURRENT MIGRATION AS DDL', Migration::describeCurrent('DDL'));
        self::assertSame('DESCRIBE CURRENT MIGRATION AS JSON', Migration::describeCurrent('JSON'));
    }

    public function testMigrationCanBeCommitted(): void
    {
        self::assertSame('COMMIT MIGRATION', Migration::commit());
    }
}
