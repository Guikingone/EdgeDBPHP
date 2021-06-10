<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Property;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class PropertyTest extends TestCase
{
    public function testPropertyCanBeCreated(): void
    {
    }

    public function testPropertyCanBeAltered(): void
    {
    }

    public function testPropertyCanBeDropped(): void
    {
        self::assertSame('DROP PROPERTY foo;', Property::drop('foo'));
        self::assertSame('DROP ABSTRACT PROPERTY foo;', Property::drop('foo', true));
    }
}
