<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\SchemaDefinition;

use EdgeDB\EdgeQL\SchemaDefinition\Module;
use EdgeDB\EdgeQL\SchemaDefinition\ObjectType;
use EdgeDB\EdgeQL\SchemaDefinition\SchemaDefinitionBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class SchemaDefinitionBuilderTest extends TestCase
{
    public function testModuleCanBeCreated(): void
    {
        $builder = new SchemaDefinitionBuilder();
        $builder->newModule(Module::new('foo'));

        self::assertSame('module foo {}', $builder->getDefinition());
    }

    public function testModuleCanBeCreatedWithType(): void
    {
        $builder = new SchemaDefinitionBuilder();
        $builder->newModule(Module::new('foo', ObjectType::new('foo')));

        self::assertSame('module foo { type foo {} }', $builder->getDefinition());
    }

    public function testExtraModuleCanBeAddedWithoutExistingModule(): void
    {
        $builder = new SchemaDefinitionBuilder();
        $builder->addNewModule(Module::new('bar'));

        self::assertSame('module bar {}', $builder->getDefinition());
    }

    public function testExtraModuleCanBeAdded(): void
    {
        $builder = new SchemaDefinitionBuilder();
        $builder->newModule(Module::new('foo'));
        $builder->addNewModule(Module::new('bar'));

        self::assertSame('module foo {} module bar {}', $builder->getDefinition());

        $builder = new SchemaDefinitionBuilder();
        $builder->newModule(Module::new('foo', ObjectType::new('bar')));
        $builder->addNewModule(Module::new('bar', ObjectType::new('foo')));

        self::assertSame('module foo { type bar {} } module bar { type foo {} }', $builder->getDefinition());
    }
}
