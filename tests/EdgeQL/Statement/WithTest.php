<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\Statement;

use EdgeDB\EdgeQL\Statement\With;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class WithTest extends TestCase
{
    public function testStatementCanBeCreated(): void
    {
        self::assertSame('WITH MODULE foo', With::new('foo'));
        self::assertSame('WITH MODULE foo AS MODULE bar', With::new('foo', 'bar'));
    }

    public function testStatementCanBeCreatedWithQueryAlias(): void
    {
        self::assertSame('WITH MODULE foo, U := Issue.owner', With::newWithQueryAlias('foo', 'U := Issue.owner'));
    }
}
