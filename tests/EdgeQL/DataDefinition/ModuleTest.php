<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Module;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ModuleTest extends TestCase
{
    public function testModuleCanBeCreated(): void
    {
        self::assertSame('CREATE MODULE foo;', Module::create('foo'));
        self::assertSame('CREATE MODULE foo IF NOT EXISTS;', Module::create('foo', true));
    }

    public function testModuleCanBeDropped(): void
    {
        self::assertSame('DROP MODULE foo;', Module::drop('foo'));
    }
}
