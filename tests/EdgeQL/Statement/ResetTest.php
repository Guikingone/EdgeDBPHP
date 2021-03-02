<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\Statement;

use EdgeDB\EdgeQL\Statement\Reset;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ResetTest extends TestCase
{
    public function testResetCanResetModule(): void
    {
        self::assertSame('RESET MODULE foo', Reset::resetModule('foo'));
    }

    public function testResetCanResetAlias(): void
    {
        self::assertSame('RESET ALIAS foo', Reset::resetAlias('foo'));
    }

    public function testResetCanResetAllAlias(): void
    {
        self::assertSame('RESET ALIAS *', Reset::resetAllAlias());
    }
}
