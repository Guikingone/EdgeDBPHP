<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\SchemaDefinition;

use EdgeDB\EdgeQL\SchemaDefinition\Module;
use EdgeDB\EdgeQL\SchemaDefinition\ObjectType;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ModuleTest extends TestCase
{
    public function testModuleCanBeCreated(): void
    {
        self::assertSame('module foo {}', Module::new('foo'));
    }

    public function testModuleCanBeCreatedWithType(): void
    {
        self::assertSame('module foo { type foo {} }', Module::new('foo', ObjectType::new('foo')));
        self::assertSame('module foo { abstract type foo {} }', Module::new('foo', ObjectType::new('foo', true)));
    }
}
