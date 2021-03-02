<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\Statement;

use EdgeDB\EdgeQL\Statement\Transaction;
use EdgeDB\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class TransactionTest extends TestCase
{
    public function testTransactionCannotBeStartedWithWrongIsolation(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('The isolation mode "ISOLATION FOO" is not allowed');
        self::expectExceptionCode(0);
        Transaction::start('ISOLATION FOO');
    }

    public function testTransactionCannotBeStartedWithWrongRead(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('The read mode "READ DEFERRED" is not allowed');
        self::expectExceptionCode(0);
        Transaction::start(Transaction::ISOLATION_REPEATABLE_READ, 'READ DEFERRED');
    }

    public function testTransactionCanBeStartedWithInvalidDeferrableContext(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('The transaction cannot be deferrable');
        self::expectExceptionCode(0);
        Transaction::start(Transaction::ISOLATION_REPEATABLE_READ, Transaction::READ_ONLY, true);
    }

    public function testTransactionCanBeStarted(): void
    {
        self::assertSame(
            'START TRANSACTION ISOLATION REPEATABLE READ, READ WRITE',
            Transaction::start(Transaction::ISOLATION_REPEATABLE_READ, Transaction::READ_WRITE)
        );
    }

    public function testTransactionCanBeStartedWhenDeferrable(): void
    {
        self::assertSame(
            'START TRANSACTION ISOLATION SERIALIZABLE, READ ONLY, DEFERRABLE',
            Transaction::start(Transaction::ISOLATION_SERIALIZABLE, Transaction::READ_ONLY, true)
        );
    }

    public function testTransactionCanBeCommitted(): void
    {
        self::assertSame('COMMIT', Transaction::commit());
    }

    public function testTransactionCanBeRolledBack(): void
    {
        self::assertSame('ROLLBACK', Transaction::rollback());
    }
}
