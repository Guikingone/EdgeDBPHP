<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\Statement;

use EdgeDB\EdgeQL\Statement\Savepoint;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class SavepointTest extends TestCase
{
    public function testSavepointCanBeDeclared(): void
    {
        self::assertSame('DECLARE SAVEPOINT foo;', Savepoint::create('foo'));
    }

    public function testSavepointCanBeReleased(): void
    {
        self::assertSame('RELEASE SAVEPOINT foo;', Savepoint::release('foo'));
    }

    public function testSavepointCanBeRolledBack(): void
    {
        self::assertSame('ROLLBACK TO SAVEPOINT foo;', Savepoint::rollback('foo'));
    }
}
