<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Link;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class LinkTest extends TestCase
{
    public function testLinkCanBeCreated(): void
    {
    }

    public function testLinkCanBeAltered(): void
    {
    }

    public function testLinkCanBeDropped(): void
    {
        self::assertSame('DROP LINK foo', Link::drop('foo'));
        self::assertSame('DROP ABSTRACT LINK foo', Link::drop('foo', true));
    }
}
