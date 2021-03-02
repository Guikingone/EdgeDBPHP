<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Alias;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class AliasTest extends TestCase
{
    public function testAliasCanBeCreated(): void
    {
    }

    public function testAliasCanBeDropped(): void
    {
        self::assertSame('DROP ALIAS foo', Alias::drop('foo'));
    }
}
