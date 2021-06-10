<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\Administration\Configure;
use EdgeDB\EdgeQL\Administration\Database;
use EdgeDB\EdgeQL\Administration\Role;
use EdgeDB\EdgeQL\DataDefinition\Migration;
use EdgeDB\EdgeQL\Statement\Transaction;
use EdgeDB\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class MigrationTest extends TestCase
{
    public function testMigrationCanBeStarted(): void
    {
        self::assertSame(
            "START MIGRATION TO { INSERT User { email := 'user@example.org'; } };",
            Migration::start("INSERT User { email := 'user@example.org'; }")
        );
    }

    public function testMigrationCannotBeCreatedWithDatabaseStatement(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Database related statements are not allowed');
        self::expectExceptionCode(0);
        Migration::create(Database::create('foo'));
    }

    public function testMigrationCannotBeCreatedWithRoleStatement(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Role related statements are not allowed');
        self::expectExceptionCode(0);
        Migration::create(Role::create('foo'));
    }

    public function testMigrationCannotBeCreatedWithConfigureStatement(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Configuration related statements are not allowed');
        self::expectExceptionCode(0);
        Migration::create(Configure::new('CURRENT DATABASE', 'query_work_mem', '4MB'));
    }

    public function testMigrationCannotBeCreatedWithMigrationStatement(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Migration related statements are not allowed');
        self::expectExceptionCode(0);
        Migration::create(Migration::create("INSERT User { email := 'user@example.org'; }"));
    }

    public function testMigrationCannotBeCreatedWithTransactionStatement(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Transaction related statements are not allowed');
        self::expectExceptionCode(0);
        Migration::create(Transaction::start());
    }

    public function testMigrationCanBeCreated(): void
    {
        self::assertSame(
            "CREATE MIGRATION { INSERT User { email := 'user@example.org'; } };",
            Migration::create("INSERT User { email := 'user@example.org'; }")
        );
    }

    public function testMigrationCanBeAborted(): void
    {
        self::assertSame('ABORT MIGRATION;', Migration::abort());
    }

    public function testMigrationCanBePopulated(): void
    {
        self::assertSame('POPULATE MIGRATION;', Migration::populate());
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
        self::assertSame('DESCRIBE CURRENT MIGRATION AS DDL;', Migration::describeCurrent('DDL'));
        self::assertSame('DESCRIBE CURRENT MIGRATION AS JSON;', Migration::describeCurrent('JSON'));
    }

    public function testMigrationCanBeCommitted(): void
    {
        self::assertSame('COMMIT MIGRATION;', Migration::commit());
    }
}
